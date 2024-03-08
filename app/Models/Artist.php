<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Solist;
use App\Models\Ensemble;
use App\Models\ArtistTag;

class Artist extends Model
{
    use HasFactory;
    protected $table = 'artists';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'name', 'user_id', 'active', 'is_ensemble', 'about', 'country_id', 'created_at', 'updated_at'];

    public function build() 
    {
        return $this->is_ensemble == 1 ? Ensemble::find($this->id) : Solist::find($this->id);
    }

    public function buildWithRelations()
    {
        $relations = ['user', 'spotifyAlbums', 'youtubeVideos', 'socials', 'socials.social', 'tags', 'country'];
        
        if ($this->is_ensemble) {
            $relations[] = 'members';
            $relations[] = 'members.tags';
            return \App\Models\Ensemble::where('id', $this->id)->with($relations)->first();
        } else {
            $relations[] = 'ensembles';
            $relations[] = 'ensembles.tags';
            return \App\Models\Solist::where('id', $this->id)->with($relations)->first();
        }
    }

    public static function getByTag() 
    {
        $tags = ArtistTag::with('tag')->select('tag_id', \DB::raw('count(*) as counta'))
                ->groupBy('tag_id')
                ->orderBy('counta', 'desc')
                ->get()->take(5)->pluck('tag_id', 'tag.name')->toArray();
        foreach ($tags as $key => $tag_id) {
            $tags[$key] = Artist::whereHas('tags', function ($q) use ($tag_id) {
                return $q->where('tags.id', $tag_id);
            })->get();
        }
        return $tags;
    }

    public function scopeActive() 
    {
        return $this->where('active', 1);
    }

    public function scopeSolist() 
    {
        return $this->where('is_ensemble', 0)->where('active', 1);
    }
    
    public function scopeEnsemble() 
    {
        return $this->where('is_ensemble', 1)->where('active', 1);
    }

    public function user() 
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function socials() 
    {
        return $this->hasMany('App\Models\ArtistSocial', 'artist_id', 'id');
    }

    public function spotify() 
    {
        return $this->hasOne('App\Models\ArtistSocial', 'artist_id', 'id')->where('social_id', 1);
    }

    public function multimedia() 
    {
        return $this->hasManyThrough('App\Models\Multimedia', 'App\Models\ArtistMultimedia', 'artist_id', 'id', 'id', 'multimedia_id');
    }

    public function spotifyAlbums() 
    {
        return $this->hasManyThrough('App\Models\Multimedia', 'App\Models\ArtistMultimedia', 'artist_id', 'id', 'id', 'multimedia_id')->where('source', 'spotify')->orderBy('id', 'DESC');
    }

    public function youtubeVideos() 
    {
        return $this->hasManyThrough('App\Models\Multimedia', 'App\Models\ArtistMultimedia', 'artist_id', 'id', 'id', 'multimedia_id')->where('source', 'youtube');
    }
    
    public function country() 
    {
        return $this->belongsTo('App\Models\Country', 'country_id', 'id');
    }

    public function tags() 
    {
        return $this->hasManyThrough('App\Models\Tag', 'App\Models\ArtistTag', 'artist_id', 'id', 'id', 'tag_id');
    }

    // Should only exist in Ensemble but error in $user->('artist.ensembles')
    public function members()
    {
        return $this->hasManyThrough('App\Models\Ensemble', 'App\Models\ArtistEnsemble', 'ensemble_id', 'id', 'id', 'solist_id');
    }
    
    // Should only exist in Solist but error in $user->('artist.ensembles')
    public function ensembles() 
    {
        return $this->hasManyThrough('App\Models\Ensemble', 'App\Models\ArtistEnsemble', 'solist_id', 'id', 'id', 'ensemble_id');
    }

}
