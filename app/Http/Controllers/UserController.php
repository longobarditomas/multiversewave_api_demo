<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function __construct(User $user) 
    {
        //
    }

    public function index(Request $request) 
    {
        return User::with(['artists', 'artists.spotifyAlbums', 'artists.youtubeVideos', 'artists.socials', 'artists.tags', 'artists.ensembles', 'artists.members'])->where('id', $request->user()->id)->first(['id', 'name', 'email']);
        return $request->user();
    }
}
