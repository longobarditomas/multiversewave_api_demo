<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Artist;

class Solist extends Artist
{
    use HasFactory;

    public function ensembles() 
    {
        return $this->hasManyThrough('App\Models\Ensemble', 'App\Models\ArtistEnsemble', 'solist_id', 'id', 'id', 'ensemble_id');
    }

}
