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
        Schema::create('collection_points', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nome usado pela equipe para identificar a gaiola.');
            $table->string('address')->comment('Endereço ou referência operacional do ponto.');
            $table->decimal('latitude', 10, 7)->comment('Latitude marcada no mapa.');
            $table->decimal('longitude', 10, 7)->comment('Longitude marcada no mapa.');
            $table->string('plus_code', 16)->index()->comment('Código compartilhável calculado para o ponto.');
            $table->enum('status', ['active', 'maintenance', 'inactive'])
                ->default('active')
                ->index()
                ->comment('Situação operacional atual da gaiola.');
            $table->text('notes')->nullable()->comment('Observação curta para a equipe de campo.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_points');
    }
};
