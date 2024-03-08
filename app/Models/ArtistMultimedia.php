<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtistMultimedia extends Model
{
    use HasFactory;
    protected $table = 'artist_multimedia';
    protected $primaryKey = 'id';
    protected $fillable = ['artist_id', 'multimedia_id'];

    public function multimedia() 
    {
        return $this->hasOne('App\Models\Multimedia', 'id', 'multimedia_id');
    }
}
