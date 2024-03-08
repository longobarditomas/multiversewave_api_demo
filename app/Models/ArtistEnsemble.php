<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtistEnsemble extends Model
{
    use HasFactory;
    protected $table = 'artist_ensembles';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'solist_id', 'ensemble_id', 'created_at', 'updated_at'];
}
