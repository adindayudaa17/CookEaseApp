<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek dulu tabel udah ada atau blm, biar ga error jika dijalankan di DB yang sudah punya tabelnya
        if (!Schema::hasTable('favorites')) {
            Schema::create('favorites', function (Blueprint $table) {
                $table->id(); // Primary key (bigint unsigned auto_increment)
                $table->unsignedBigInteger('user_id'); // Untuk foreign key ke users.id (BIGINT UNSIGNED)
                $table->integer('recipe_id');      // Untuk foreign key ke recipes.recipe_id (SIGNED INT)
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('recipe_id')->references('recipe_id')->on('recipes')->onDelete('cascade');

                // Unique constraint
                $table->unique(['user_id', 'recipe_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};