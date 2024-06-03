<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MangaChapter extends Model
{
    use HasFactory;
    protected $table = 'manga_chapters';
    protected $guarded = ['id'];

    public function image()
    {
        return $this->hasMany(MangaChapterImage::class);
    }
}
