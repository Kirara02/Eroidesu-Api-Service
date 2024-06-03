<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MangaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Menghitung jumlah chapter
        $totalChapters = $this->chapters->count();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'thumbnail' => $this->thumbnail,
            'genre' => json_decode($this->genre),
            'author' => $this->author,
            'type' => $this->type,
            'sinopsis' => $this->sinopsis,
            'status' => $this->status,
            'rating' => $this->rating,
            'rilis' => $this->rilis,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'total_chapter' => $totalChapters,  // Menambahkan kunci total_chapter
            'chapter' => $this->chapter,
        ];
    }
}
