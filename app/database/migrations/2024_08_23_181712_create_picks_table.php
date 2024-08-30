<?php

use App\Models\Type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('picks', function (Blueprint $table) {
            $table->id(); // eloquent requires a single key over a composite
            $table->foreignId('user_id')->constrained();
            $table->foreignId('round_id')->constrained();
            $table->enum('type', array_column(Type::cases(), 'value'));
            $table->foreignId('driver1_id')->constrained('drivers');
            $table->foreignId('driver2_id')->constrained('drivers');
            $table->foreignId('driver3_id')->constrained('drivers');
            $table->integer('score')->nullable();
            $table->timestamp('scored_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'round_id', 'type'], 'user_round_type_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('picks');
    }
};
