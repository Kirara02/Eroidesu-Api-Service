<?php 

namespace App\Interface;

interface MangaInterface {
    public function getMangas($key, $genreParam, $type);
    public function getMangaById($id);
    public function getMangaByName($name);
    public function getListChapters($id);
    public function getImage($id);
    public function getPopularManga();
}