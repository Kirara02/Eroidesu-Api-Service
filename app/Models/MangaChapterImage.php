<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MangaChapterImage extends Model
{
    use HasFactory;
    protected $table = 'manga_chapter_images';
    protected $guarded = ['id'];

}
