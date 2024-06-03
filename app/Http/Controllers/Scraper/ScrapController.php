<?php

namespace App\Http\Controllers\Scraper;

use App\Http\Controllers\Controller;
use App\Models\Manga;
use App\Models\MangaChapter;
use App\Models\MangaChapterImage;
use Illuminate\Http\Request;
use Goutte\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ScrapController extends Controller
{
    public function getListManga(){
        return view('scraper.getmanga');
    }

    public function getMangaDetail(){
        return view('scraper.getmangadetail');
    }

    public function apiGetListManga(Request $request) {

        $client = new Client();
        $url = 'https://bacakomik.moe/daftar-komik/';
        $html = $client->request('GET', $url);
    

        $html->filter('.soralist .blix ul li a.series')->each(function ($node) use (&$allAnimes) {
            $animeTitle = $node->text();
            $animeLink = $node->attr('href');

            Manga::updateOrCreate([
                'baseurl' => $animeLink,
                'name' => $animeTitle,
            ]);
        });

        return response()->json(['success' =>true]);
    }      

    public function apiGetMangaDetail(Request $request)
    {
        $index = $request->input('index', 0); 

        $mangas = Manga::select('id', 'baseurl')->get();
        $client = new Client();

        if($index < count($mangas)){
            try {
                $manga = $mangas[$index];
                $html = $client->request('GET', $manga->baseurl);

                $html->filter('article')->each(function ($node) use ($manga) {
                    //ANCHOR - genres
                    $genres = [];
                    $node->filter('.infox .wd-full span.mgen a')->each(function ($genreNode) use (&$genres) {
                        $genreText = $genreNode->text();
                        $genres[] = $genreText;
                    });

                    //ANCHOR - thumbnail
                    $thImage = $node->filter('.thumbook .thumb');
                    $hasImage = $thImage->filter('img')->count() > 0;
                    if($hasImage){
                        $thumbnail = $node->filter('.thumbook .thumb img')->attr('src');
                        Manga::findOrFail($manga->id)->update([
                            'thumbnail' => $thumbnail,
                        ]);
                    }
                    
                    //ANCHOR - rating
                    $rating = $node->filter('.thumbook .rt .rating .rating-prc .num')->text(); 
                    
                    //ANCHOR - status
                    $status = $node->filter('.thumbook .rt .tsinfo .imptdt i')->text();
                
                    //ANCHOR - type
                    $type = $node->filter('.thumbook .rt .tsinfo .imptdt a')->text();

                    //ANCHOR - sinopsis
                    $sinop = $node->filter('.infox .wd-full .entry-content');
                    $hasSinop = $sinop->filter('p')->count() > 0;
                    $sinopsis = "Not have sinopsis.";
                    if($hasSinop){
                        $sinopsis = $node->filter('.infox .wd-full .entry-content p')->text();
                    }

                    //ANCHOR - author & rilis
                    $author = '';
                    $rilis = '';

                    $node->filter('.infox .flex-wrap .fmed')->each(function ($node2) use(&$author, &$rilis){
                        $title = $node2->filter('b')->text();
                        $text = trim($node2->filter('span')->text());

                        if ($title === 'Author') {
                            $author = $text;
                        } elseif ($title === 'Rilis') {
                            $rilis = $text;
                        }
                    });

                    //ANCHOR - INSERT Manga
                    Manga::findOrFail($manga->id)->update([
                        'genre' => $genres,
                       
                        'rating' => $rating,
                        'status' => $status,
                        'type' => $type,
                        'sinopsis' => $sinopsis,
                        'author' => $author,
                        'rilis' => $rilis,
                    ]);
                        
                });

                return response()->json([
                    'success' => true,
                    'manga_id' => $manga->id,
                    'nextIndex' => $index + 1, // Indeks manga berikutnya
                ]);
            } catch (\Exception $e) {
                // Tangani kesalahan permintaan HTTP di sini, seperti mencatat atau melanjutkan
                Log::error('HTTP request error: ' . $e->getMessage());

                return response()->json(['error' => $e->getMessage(),'currentIndex' => $index]);
            }
        }

        // Tidak ada manga lagi untuk diproses
        return response()->json([
            'success' => true,
            'nextIndex' => null, // Tandai bahwa tidak ada manga lagi untuk diproses
        ]);
    }

    public function getMangaChapter(Request $request){
        $index = $request->input('index', 0); // Ambil indeks dari request atau gunakan 0 sebagai nilai default

        $mangas = Manga::select('id', 'baseurl')->get();
        $client = new Client();

        if($index < count($mangas)){
            try {
                $manga = $mangas[$index];

                $html = $client->request('GET', $manga->baseurl);

                $html->filter('article')->each(function ($node) use ($manga) {

                    $node->filter('.bixbox .eplister ul li .chbox .eph-num')->each(function ($chapters) use ($manga) {
                        $chapter = $chapters->filter('a span.chapternum')->text();
                        $url = $chapters->filter('a')->attr('href');
                        $rilis = $chapters->filter('a span.chapterdate')->text();

                        //ANCHOR - INSERT MangaChapter
                        $mangaChapter = MangaChapter::updateOrCreate([
                            'chapter' => $chapter,
                            'url' => $url,
                            'rilis' => $rilis,
                            'manga_id' => $manga->id,
                        ]);

                        //ANCHOR - Get ImageChapter
                        $clientImage = new Client();
                        $htmlImage = $clientImage->request('GET', $mangaChapter->url);

                        $htmlImage->filter('.entry-content.entry-content-single.maincontent p img')->each(function ($images) use ($mangaChapter) {
                            $imageUrl = $images->attr('src');
                            $imageAlt = $images->attr('alt');

                            //ANCHOR - INSERT MangaChapterImage
                            MangaChapterImage::updateOrCreate([
                                'image' => $imageUrl,
                                'alt' => $imageAlt,
                                'chapter_id' => $mangaChapter->id,
                            ]);
                        });
                    });
                });


            } catch (\Exception $e) {
                // Tangani kesalahan permintaan HTTP di sini, seperti mencatat atau melanjutkan
                Log::error('HTTP request error: ' . $e->getMessage());

                return response()->json(['error' => $e->getMessage(),'currentIndex' => $index]);
            }
        }

        // Tidak ada manga lagi untuk diproses
        return response()->json([
            'success' => true,
            'nextIndex' => null, // Tandai bahwa tidak ada manga lagi untuk diproses
        ]);
    }
}

