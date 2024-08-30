<?php

use App\Models\Type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id(); // eloquent requires a single key over a composite
            $table->foreignId('round_id')->constrained();
            $table->enum('type', array_column(Type::cases(), 'value'));
            $table->foreignId('driver1_id')->constrained('drivers');
            $table->foreignId('driver2_id')->constrained('drivers');
            $table->foreignId('driver3_id')->constrained('drivers');
            $table->timestamps();

            $table->unique(['round_id', 'type'], 'round_type_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
