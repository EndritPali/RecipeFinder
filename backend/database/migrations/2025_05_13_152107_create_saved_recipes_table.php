<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('saved_recipes', function (Blueprint $table) {
            $table->id(); // primary key
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('recipe_id');
            $table->timestamp('created_at')->useCurrent();

            // Unique constraint to prevent duplicate saves
            $table->unique(['user_id', 'recipe_id']);

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('recipe_id')->references('id')->on('recipes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_recipes');
    }
};
