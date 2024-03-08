<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtistTag extends Model
{
    use HasFactory;
    protected $table = 'artist_tags';
    protected $primaryKey = 'id';
    protected $fillable = ['artist_id', 'tag_id', 'created_at', 'updated_at'];

    public function tag() 
    {
        return $this->belongsTo('App\Models\Tag', 'tag_id', 'id');
    }
}
