<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('municipality_geofences', function (Blueprint $table) {
            $table->id();
            $table->enum('municipality', [
                'novo_cruzeiro',
                'aguas_formosas',
                'teofilo_otoni',
                'itaipe',
                'catuji',
            ])->unique()->comment('Município delimitado pela geocerca.');
            $table->decimal('center_latitude', 10, 7)->comment('Latitude do centro do círculo.');
            $table->decimal('center_longitude', 10, 7)->comment('Longitude do centro do círculo.');
            $table->decimal('radius_km', 6, 2)->comment('Raio do círculo em quilômetros.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipality_geofences');
    }
};
