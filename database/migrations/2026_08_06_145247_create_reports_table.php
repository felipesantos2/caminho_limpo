<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('protocol', 40)->unique()->comment('Código público usado para consultar o relato.');
            // Values stay here so future enum changes do not rewrite this schema history.
            $table->enum('category', [
                'household_waste',
                'construction_debris',
                'bulky_item',
                'recyclable',
                'hazardous_waste',
                'sewage',
                'abandoned_area',
                'other',
            ])
                ->index()
                ->comment('Tipo de problema ambiental observado no local.');
            $table->text('description')->comment('Descrição original informada sobre o local.');
            $table->string('address')->comment('Endereço aproximado ou referência para encontrar o local.');
            $table->decimal('latitude', 10, 7)->nullable()->comment('Latitude opcional do ponto informado.');
            $table->decimal('longitude', 10, 7)->nullable()->comment('Longitude opcional do ponto informado.');
            $table->enum('status', ['received', 'triage', 'published', 'restricted', 'rejected'])
                ->default('received')
                ->index()
                ->comment('Etapa atual do relato e regra que controla sua publicação.');
            $table->string('image_path')->comment('Caminho da foto armazenada no disco público.');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
