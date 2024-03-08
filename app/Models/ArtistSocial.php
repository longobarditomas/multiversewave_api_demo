<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtistSocial extends Model
{
    use HasFactory;
    protected $table = 'artist_socials';
    protected $fillable = ['artist_id', 'social_id', 'code', 'created_at', 'updated_at'];
    // private $socials = [0 => 'spotify', 1 => 'instagram', 2 => 'facebook', 3 => 'soundcloud'];

    public function social() 
    {
        return $this->belongsTo('App\Models\Social', 'social_id', 'id');
    }
}
