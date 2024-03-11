<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreArtistRequest;
use App\Http\Requests\UpdateArtistRequest;
use App\Http\Requests\ArtistMultimediaRequest;
use App\Models\Artist;
use App\Models\ArtistSocial;
use App\Models\Tag;
use App\Services\ArtistService;
use App\Services\MultimediaService;

class ArtistController extends Controller
{
    protected $artist;
    protected $artistService;
    protected $multimediaService;

    public function __construct(Artist $artist, ArtistService $artistService, MultimediaService $multimediaService)
    {
        $this->artist  = $artist;
        $this->service = $artistService;
        $this->multimediaService = $multimediaService;
    }
    
    public function index(Request $request) 
    {
        $artists = Artist::with('tags')->get();
        return response()->json($artists);
    }
    
    public function index_by_tags(Request $request) 
    {
        $artists = Artist::getByTag();
        return response()->json($artists);
    }

    public function youtube(Request $request) 
    {
        return response()->json($this->youTubeService->getVideosIds());
    }

    public function show(Artist $artist)
    {
        return response()->json($artist->buildWithRelations());
    }

    public function store(StoreArtistRequest $request)
    {
        $user = $request->user() ?: null;
        $data = [
            'name'        => $request->input('name', null),
            'user_id'     => $user->id, 
            'active'      => 1, 
            'is_ensemble' => $request->input('is_ensemble', 0), 
            'about'       => $request->input('about', null), 
            'country_id'  => 1, 
            'city_id'     => 0, 
            'image'       => $request->hasFile('image') ? $request->file('image') : null,
            'tags'        => $request->input('tags', []),
        ];
        if ($data['is_ensemble'] == 0) {
            $exists = Artist::where('user_id', $user->id)->where('is_ensemble', 0)->first();
            if ($exists) return response()->json(["message" => "Already has a solist artist"], 409);
        }
        $artist = $this->service->create($data);
        return response()->json(["artist" => $artist]);
    }

    public function update(UpdateArtistRequest $request, Artist $artist)
    {
        $user = $request->user() ?: null;
        $data = [
            'name'  => $request->input('name', null),
            'about' => $request->input('about', null), 
            'image' => $request->hasFile('image') ? $request->file('image') : null,
            'tags'  => $request->input('tags', []),
        ];
        $this->service->update($artist, $data);
        $artist->refresh();
        return response()->json(["artist" => $artist]);
    }

    public function store_spotify_albums(ArtistMultimediaRequest $request)
    {
        $artist = Artist::findOrFail($request->artist_id);
        $this->authorize('update', $artist);
        $spotify_id = $request->input('external_id');
        $artist_social = $this->service->storeArtistSocial($artist->id, 1, $spotify_id);
        return response()->json($this->multimediaService->addSpotifyAlbums($artist->id, $spotify_id));
    }

    public function store_youtube_videos(ArtistMultimediaRequest $request)
    {
        $artist = Artist::findOrFail($request->artist_id);
        $this->authorize('update', $artist);
        $youtube_id = $request->input('external_id');
        $artist_social = $this->service->storeArtistSocial($artist->id, 5, $youtube_id);
        return response()->json($this->multimediaService->addYoutubeVideos($artist->id, $youtube_id));
    }
    
    public function store_member(Request $request, Artist $artist) {
        $artist = Artist::findOrFail($request->artist_id);
        $this->authorize('update', $artist);
        $solist_id = $request->input('solist_id');
        $artist_social = $this->service->storeMember($artist->id, $solist_id);
        return response()->json(Artist::with('members')->where('id', $artist->id)->first());
    }

    public function all_tags(Request $request) 
    {
        if ($request->query('is_ensemble') === '1') return response()->json(Tag::ensembles()->orderBy('name')->get());
        else return response()->json(Tag::solists()->orderBy('name')->get());
    }
}
