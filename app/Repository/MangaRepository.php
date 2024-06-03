<?php

namespace App\Repository;

use App\Interface\MangaInterface;
use App\Models\Manga;
use App\Models\MangaChapterImage;

class MangaRepository implements MangaInterface {
    public function getMangas($key, $genreParam, $type)
    {   
        $genres = json_decode($genreParam);
        $query = Manga::query();

        if ($key != null) {
            $query->where('name', 'like', '%' . $key . '%');
        }

        if ($type != null) {
            $query->where('type', $type);
        }

        if (!empty($genres)) {
            $query->where(function($query) use ($genres) {
                foreach ($genres as $genre) {
                    $query->orWhere('genre', 'like', '%' . $genre . '%');
                }
            });
        }

        return $query;
    }


    public function getMangaById($id)
    {
        $manga =  Manga::find($id);
        $manga->load('chapters');

        return $manga;
    }

    public function getMangaByName($name)
    {
        $manga =  Manga::where('name','=',$name)->first();

        return $manga;
    }

    public function getListChapters($id){

        $manga = Manga::with(['chapters' => function($query) {
            $query->orderBy('id', 'asc');
        }])->find($id);

        if (!$manga) {
            return null;
        }
    
        return $manga->chapters;
    }

    public function getImage($id)
    {
        return MangaChapterImage::where('chapter_id',$id)->get();
    }

    public function getPopularManga()
    {
        return Manga::orderBy('rating','desc');
    }
}

