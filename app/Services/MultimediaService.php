<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

use App\Models\Multimedia;
use App\Models\ArtistMultimedia;
use App\Models\Album;
use App\Models\AlbumArtist;

class MultimediaService
{
    protected $spotifyService;
    protected $youTubeService;

    public function __construct(SpotifyService $spotifyService, YouTubeService $youTubeService)
    {
        $this->spotifyService = $spotifyService;
        $this->youTubeService = $youTubeService;
    }
        
    public function addSpotifyAlbums($artist_id, $spotify_id) {
        $albums = $this->spotifyService->getSpotifyAlbums($spotify_id);
        $album_ids = [];
        $this->_cleanArtistMultimedia($artist_id, 'spotify');
        foreach($albums as $album){
            $exist = Multimedia::where('external_id', $album->id)->first();
            if (!isset($exist))
                $new_album = $this->_storeSpotifyAlbum($artist_id, $album);
            $id = $exist ? $exist->id : $new_album->id;
            $this->_storeArtistMultimedia($artist_id, $id);
            $album_ids[] = $id;
        }
        return Multimedia::with('artist')->whereIn('id', $album_ids)->get();
    }

    private function _storeSpotifyAlbum($artist_id = 0, $album = []) {
        $data = [
            'title'        => $album->name,
            'image_url'    => $album->images[1]->url,
            'source'       => "spotify",
            'external_id'  => $album->id,
            'type'         => $album->type,
            'release_date' => date('Y-m-d', strtotime($album->release_date)),
        ];
        $multimedia = Multimedia::create($data);
        return $multimedia;
    }

    private function _storeMultimedia($artist_id = 0, $data = []) {
        $new_multimedia = Multimedia::create($data);
        ArtistMultimedia::firstOrCreate([
            'multimedia_id' => $new_multimedia->id,
            'artist_id'     => $artist_id,
        ]);
        return $new_multimedia;
    }

    private function _cleanArtistMultimedia($artist_id = 0, $source = '') {
        $artist_multimedia = ArtistMultimedia::where('artist_id', $artist_id)->whereHas('multimedia', function($q) use ($source) {
            return $q->where('source', $source);
        })->delete();
        return $artist_multimedia;
    }

    private function _storeArtistMultimedia($artist_id = 0, $id = 0) {
        $artist_multimedia = ArtistMultimedia::firstOrCreate([
            'multimedia_id' => $id,
            'artist_id'     => $artist_id,
        ]);
        return $artist_multimedia;
    }

    public function addYoutubeVideos($artist_id, $playlist_id) {
        $youtube_videos = $this->youTubeService->getVideosIds($playlist_id);
        $video_ids = [];
        $this->_cleanArtistMultimedia($artist_id, 'youtube');
        foreach($youtube_videos as $video){
            $video_id = $video->snippet->resourceId->videoId;
            $exist = Multimedia::where('external_id', $video_id)->first();
            if (!isset($exist))
                $new_video = $this->_storeYouTubeVideo($artist_id, $video);
            $id = $exist ? $exist->id : $new_video->id;
            $this->_storeArtistMultimedia($artist_id, $id);
            $video_ids[] = $id;
        }
        return Multimedia::with('artist')->whereIn('id', $video_ids)->get();
    }

    private function _storeYouTubeVideo($artist_id = 0, $video = []) {
        $data = [
            'title'        => $video->snippet->title,
            'image_url'    => isset($video->snippet->thumbnails->standard) ? $video->snippet->thumbnails->standard->url : '',
            'source'       => "youtube",
            'external_id'  => $video->snippet->resourceId->videoId,
            'type'         => 'video',
            'release_date' => date('Y-m-d', strtotime($video->snippet->publishedAt)),
        ];
        $multimedia = Multimedia::create($data);
        return $multimedia;
    }

}
