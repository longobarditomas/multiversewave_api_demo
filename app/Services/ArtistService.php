<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

use App\Models\Artist;
use App\Models\ArtistSocial;
use App\Models\ArtistTag;
use App\Models\AlbumArtist;
use App\Models\ArtistEnsemble;

class ArtistService
{

    public function create($data = []) 
    {
        if (count($data) === 0) return false;
        $artist = Artist::create($data);

        if ($data['image']) $this->_storeImage($artist->id, $data['image']);
        if ($data['tags']) $this->_storeTags($artist->id, $data['tags']);
        return $artist;
    }


    public function update($artist = null, $data = []) 
    {
        if (!$artist || count($data) === 0) return false;
        $artist->update($data);

        if ($data['image']) $this->_storeImage($artist->id, $data['image']);
        if ($data['tags']) $this->_storeTags($artist->id, $data['tags']);
        return $artist;
    }

    public function storeArtistSocial($artist_id = 0 , $social_id = 0, $code = '') 
    {
        $artist_social = ArtistSocial::updateOrCreate([
            'artist_id'  => $artist_id,
            'social_id'  => $social_id,
        ],[
            'code'       => $code,
        ]);
        return $artist_social;
    }

    public function storeMember($artist_id = 0, $solist_id = 0) 
    {
        $artist_ensemble = ArtistEnsemble::create([
            'solist_id'    => $solist_id,
            'ensemble_id'  => $artist_id,
        ]);
        return $artist_ensemble;
    }

    private function _storeImage($artist_id = 0, $file = null)
    {
        if (!$artist_id || !$file) return false;
        //$file_name = 'artist_' . $artist->id . "." . $file->getClientOriginalExtension();
        $file_name = 'artist_' . $artist_id . ".jpg";
        $file_path = 'images/artists/';
        if (\App::environment('production')) $path = $file->storeAs($file_path, $file_name, 's3');
        else $path = $file->storeAs($file_path, $file_name, 'public');
        return $path;
    }

    private function _storeTags($artist_id = 0, $tags = []) 
    {
        if (!$artist_id || count($tags) === 0) return false;
        ArtistTag::where('artist_id', $artist_id)->whereNotIn('tag_id', $tags)->delete();
        foreach ($tags as $tag_id) {
            ArtistTag::updateOrCreate(
                ['artist_id' => $artist_id, 'tag_id' => $tag_id],
                ['is_primary' => 0]
            );
        }
        ArtistTag::where('tag_id', $tags)->get();
    }

}
