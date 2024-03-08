<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Seeder;
use App\Models\Artist;

class YouTubeService extends Seeder
{
    public function getVideosIds($channel_id = null) 
    {
        if (!$channel_id) return false;
        $channel_items  = $this->_getChannelDetails($channel_id);
        $playlist_id    = $channel_items[0]->contentDetails->relatedPlaylists->uploads;
        $playlist_items = $this->_getPlaylistItems($playlist_id);
        return $playlist_items;

        $filteredIds = [];

        foreach ($playlist_items as $item) {
            if (isset($item->snippet->resourceId->videoId)) {
                $filteredIds[] = $item->snippet->resourceId->videoId;
            }
        }
        return $filteredIds;
    }

    private function _getPlaylistItems($playlist_id) {
        $client   = new \GuzzleHttp\Client();
        $headers  = ["Accept" => "application/json"];
        $url      = 'https://www.googleapis.com/youtube/v3/playlistItems';
        $response = $client->request('GET', $url, [
            'query'   => [
                'part' => 'snippet',
                'maxResults' => '30',
                'playlistId' => $playlist_id,
                'key' => config('mw.google_api_key'),
            ],
            'headers' => $headers,
            'verify'  => false,
        ]);
        $responseBody = json_decode($response->getBody());
        return $responseBody->items;
    }

    private function _getChannelDetails($channelId) {
        $client   = new \GuzzleHttp\Client();
        $headers  = ["Accept" => "application/json"];
        $url      = 'https://www.googleapis.com/youtube/v3/channels';
        $response = $client->request('GET', $url, [
            'query' => [
                'id' => $channelId,
                'part' => 'snippet,contentDetails',
                'key' => config('mw.google_api_key'),
            ],
            'headers' => $headers,
        ]);
        $responseBody = json_decode($response->getBody());
        return $responseBody->items;
    }

    /* private function _getVideoDetails($video_ids) {
        $client = new \GuzzleHttp\Client();
        $headers = [
            //"Authorization" => "Bearer {$token}",
            "Accept"        => "application/json",
        ];
        $url = 'https://www.googleapis.com/youtube/v3/videos';
        $response = $client->request('GET', $url, [
            'query'   => [
                'part' => 'snippet',
                'id' => $video_ids, // example 'dQw4w9WgXcQ,abZPh29bH6o'
                'key' => config('mw.google_api_key'),
            ],
            'headers' => $headers,
            'verify'  => false,
        ]);
        $responseBody = json_decode($response->getBody());
        dd($responseBody);
        return $responseBody->items;
    } */

    /* private function _getChannelId($channelName) {
        $client = new \GuzzleHttp\Client();
        $headers = ["Accept" => "application/json"];
        $url = 'https://www.googleapis.com/youtube/v3/channels';
        $response = $client->request('GET', $url, [
            'query' => [
                'forUsername' => $channelName, // Use the channel Name here
                'part' => 'id',
                'key' => config('mw.google_api_key'),
            ],
            'headers' => $headers,
        ]);
        $responseBody = json_decode($response->getBody());
        dd($responseBody);
        return $responseBody->items[0]->contentDetails->relatedPlaylists->uploads; // This gets the uploads playlist ID
    } */

    
}
