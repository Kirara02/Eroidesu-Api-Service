<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChapterResource;
use App\Http\Resources\MangaChapterResource;
use App\Http\Resources\MangaPaginationResource;
use App\Http\Resources\MangaResource;
use App\Interface\MangaInterface;
use App\Models\Manga;
use Illuminate\Http\Request;

class MangaController extends Controller
{
    protected $manga;

    public function __construct(MangaInterface $manga)
    {
        $this->manga = $manga;
    }

    public function index(Request $request)
    {
        $key = $request->input('search');
        $genreParam = $request->input('genre'); 
        $type = $request->input('type');
        
        $data = $this->manga->getMangas($key,$genreParam,$type);

        return $this->success(MangaPaginationResource::collection($data->paginate(20)),'Data berhasil diambil',true);
    }



    public function showById($id){
        $data = $this->manga->getMangaById($id);

        if ($data === null) {
            return $this->error("Manga dengan ID $id tidak ditemukan",404);
        }
        
        return $this->success(new MangaResource($data), "Manga dengan ID $id berhasil ditemukan");
    }

    public function showByName($name){
        $data = $this->manga->getMangaByName($name);

        if ($data === null) {
            return $this->error("Manga dengan Judul $name tidak ditemukan",404);
        }
        
        return $this->success(new MangaResource($data), "Manga dengan Judul $name berhasil ditemukan");
    }

    public function getListChapters($id){
        $data = $this->manga->getListChapters($id);

        if ($data === null) {
            return $this->error("Chapter dengan MangaID $id tidak ditemukan",404);
        }
        
        return $this->success(ChapterResource::collection($data), "Chapter dengan MangaID $id berhasil ditemukan");
    }

    public function getChapterImage($id)
    {
        $data = $this->manga->getImage($id);

        return $this->success(MangaChapterResource::collection($data));
    }

    public function getPopularComic(){
        $data = $this->manga->getPopularManga();

        return $this->success(MangaPaginationResource::collection($data->paginate(10)), 'Data berhasil diambil',true);
    }
}

