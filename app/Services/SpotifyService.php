<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Seeder;
use App\Models\Artist;
use App\Models\Album;
use App\Models\AlbumArtist;
use App\Models\SpotifyUser;

class SpotifyService extends Seeder
{
    public function getSpotifyArtist($artist_id = null, $limit = 20) {
        if (!$artist_id) return false;
        $url   = "https://api.spotify.com/v1/artists/{$artist_id}";
        $response = $this->_getSpotifyCallback($url);
        return $response;
    }

    public function getSpotifyAlbums($artist_id = null, $limit = 20) {
        if (!$artist_id) return false;
        $url   = "https://api.spotify.com/v1/artists/{$artist_id}/albums";
        $response = $this->_getSpotifyCallback($url);
        return collect($response->items);
    }

    public function getSpotifyAlbum($album_id = null, $limit = 20) {
        if (!$album_id) return false;
        $url   = "https://api.spotify.com/v1/albums/{$album_id}";
        $params = [
            "limit"          => $limit,
            "include_groups" => "single"
        ];
        $response = $this->_getSpotifyCallback($url);
        return $response;
    }

    public function getSpotifyAlbumTracks($album_id = null, $limit = 20) {
        if (!$album_id) return false;
        $url   = "https://api.spotify.com/v1/albums/{$album_id}/tracks";
        $response = $this->_getSpotifyCallback($url);
        return collect($response->items);
    }

    private function _getSpotifyCallback($url = '', $params = []) {
        $client = new Client();
        $token = $this->_getSpotifyToken();
        $headers = [
            "Authorization" => "Bearer {$token}",
            "Accept"        => "application/json",
        ];
        $response = $client->request('GET', $url, [
            'query'   => $params,
            'headers' => $headers,
            'verify'  => false,
        ]);
        $responseBody = json_decode($response->getBody());
        return $responseBody;
    }

    private function _getSpotifyToken() {
        $client  = new Client();
        $url     = "https://accounts.spotify.com/api/token";
        $base_64 = base64_encode(config('mw.spotify_client_id').":".config('mw.spotify_client_secret'));

        $response = Http::withHeaders([
            "Authorization" => "Basic ".$base_64,
        ])->asForm()->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'client_credentials',
        ]);
        return $response->json()["access_token"];
    }

    public function getToken($code = '') {
        return SpotifyUser::first()->access_token;
    }

}
