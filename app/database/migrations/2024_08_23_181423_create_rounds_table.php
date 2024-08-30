<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rounds', function (Blueprint $table) {
            $table->id(); // eloquent requires a single key over a composite
            $table->integer('year');
            $table->integer('round');
            $table->string('name');
            $table->timestamp('sprint_qualifying_at')->nullable();
            $table->timestamp('sprint_race_at')->nullable();
            $table->timestamp('race_qualifying_at');
            $table->timestamp('race_at');
            $table->timestamps();

            $table->unique(['year', 'round'], 'year_round_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rounds');
    }
};
