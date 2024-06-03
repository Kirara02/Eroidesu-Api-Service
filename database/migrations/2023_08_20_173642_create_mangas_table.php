<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mangas', function (Blueprint $table) {
            $table->id();
            $table->string('baseurl')->nullable();
            $table->string('name')->nullable();
            $table->string('thumbnail')->default('https://w7.pngwing.com/pngs/348/914/png-transparent-anime-manga-drawing-female-chibi-manga-face-black-hair-hand-thumbnail.png');
            $table->text('genre')->nullable();
            $table->string('type')->default('-');
            $table->text('author')->nullable();
            $table->string('status')->nullable();
            $table->text('sinopsis')->nullable();
            $table->string('rating')->nullable();
            $table->string('rilis')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animes');
    }
};
