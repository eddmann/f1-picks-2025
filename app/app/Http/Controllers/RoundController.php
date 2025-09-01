<?php

namespace App\Http\Controllers;

use App\Models\Pick;
use App\Models\Result;
use App\Models\Round;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class RoundController
{
    public function index(Request $request)
    {
        $year = $request->input('year') ?: date('Y');
        $years = Round::groupBy('year')->pluck('year');

        $now = now();

        // Determine the active round: the next upcoming race in the selected year,
        // or the last round if the season has finished.
        $activeRound = Round::where('year', $year)
            ->where('race_at', '>=', $now)
            ->orderBy('race_at', 'asc')
            ->first();

        if (! $activeRound) {
            $activeRound = Round::where('year', $year)
                ->orderBy('round', 'desc')
                ->first();
        }

        // If no explicit page is provided, default the pagination to the page that
        // contains the active round.
        if (! $request->has('round_page') && $activeRound) {
            $perPage = 5;
            $position = Round::where('year', $year)
                ->where('round', '<=', $activeRound->round)
                ->count();
            $defaultPage = max(1, (int) ceil($position / $perPage));

            Paginator::currentPageResolver(function (string $pageName) use ($defaultPage) {
                return $pageName === 'round_page' ? $defaultPage : null;
            });
        }

        $rounds = Round::orderBy('year', 'desc')
            ->where('year', $year)
            ->orderBy('round', 'asc')
            ->simplePaginate(5, pageName: 'round_page')
            ->withQueryString();

        $scores = DB::select('
            SELECT users.id, users.name, SUM(picks.score) as score
            FROM users
            INNER JOIN picks ON users.id = picks.user_id
            INNER JOIN rounds ON picks.round_id = rounds.id
            WHERE picks.score IS NOT NULL
            AND rounds.year = :year
            GROUP BY users.id, users.name
            ORDER BY score DESC
        ', compact('year'));

        $activeRoundId = $activeRound?->getKey();

        return view('rounds.index', compact('year', 'years', 'rounds', 'scores', 'activeRoundId'));
    }

    public function show(Round $round)
    {
        $picks = Pick::where(['user_id' => auth()->id(), 'round_id' => $round->id])->get();
        $score = $picks->isNotEmpty() ? $picks->sum(fn ($pick) => $pick->score) : null;

        $types = array_reduce(Type::cases(), function (array $types, Type $type) use ($round) {
            $at = $round->getTypeAt($type);

            if ($at === null) {
                return $types;
            }

            return [
                ...$types,
                [
                    'id' => $type,
                    'at' => $at,
                    'pickWindow' => $round->getPickWindow($type),
                    'isOpenForPicks' => auth()->id() && $round->isOpenForPicks($type),
                    'picks' => Pick::where(['round_id' => $round->id, 'type' => $type->value])->orderBy('score', 'desc')->get(),
                    'result' => Result::where(['round_id' => $round->id, 'type' => $type->value])->first(),
                ],
            ];
        }, []);

        return view('rounds.show', compact('round', 'score', 'types'));
    }
}
