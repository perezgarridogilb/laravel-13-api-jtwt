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
    Schema::create(table: 'movies', callback: function (Blueprint $table): void {
        $table->id();
        $table->string(column: 'title')->unique();
        $table->string(column: 'slug')->unique();
        $table->text(column: 'synopsis')->nullable();
        $table->smallInteger(column: 'year');
        $table->decimal(column: 'rating', total: 3, places: 1);
        $table->string(column: 'director')->nullable();
        $table->integer(column: 'duration')->nullable()->comment(comment: 'duración en minutos');
        $table->string(column: 'poster')->nullable();
        $table->boolean(column: 'is_active')->default(value: true);
        $table->timestamps();
        $table->softDeletes();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
