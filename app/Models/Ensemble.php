<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Artist;

class Ensemble extends Artist
{
    use HasFactory;

    public function members() 
    {
        return $this->hasManyThrough('App\Models\Ensemble', 'App\Models\ArtistEnsemble', 'ensemble_id', 'id', 'id', 'solist_id');
    }
}
