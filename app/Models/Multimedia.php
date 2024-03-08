<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Multimedia extends Model
{
    use HasFactory;
    protected $table = 'multimedia';
    protected $primaryKey = 'id';
    protected $fillable = ['title', 'type', 'source', 'external_id', 'image_url', 'release_date'];

    public function artist() 
    {
        return $this->hasOneThrough('App\Models\Artist', 'App\Models\ArtistMultimedia', 'multimedia_id', 'id', 'id', 'artist_id');
    }
}
