<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manga extends Model
{
    use HasFactory;

    protected $table = 'mangas';
    protected $guarded = ['id'];

    public function chapters()
    {
        return $this->hasMany(MangaChapter::class);
    }
}
