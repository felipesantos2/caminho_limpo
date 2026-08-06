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
            $table->string('title', 60);
            $table->string('content', 1000)->nullable(); // podemos gerar um html para ser rederizado
            $table->string('media')->nullable();
            $table->string('slug', 30)->nullable();
            $table->string('url', 30)->nullable();
            $table->enum('status', ['published', 'draft', 'pending'])->default('draft');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
