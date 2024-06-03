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
        Schema::create('manga_chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manga_id')->constrained('mangas')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('chapter');
            $table->string('url');
            $table->string('rilis');
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
        Schema::dropIfExists('manga_chapters');
    }
};
