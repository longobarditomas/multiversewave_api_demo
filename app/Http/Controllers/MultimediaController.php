<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ArtistRequest;
use App\Models\Artist;
use App\Models\ArtistSocial;
use App\Models\Multimedia;
use App\Services\ArtistService;
use App\Services\MultimediaService;

class MultimediaController extends Controller
{
    protected $multimedia;
    protected $multimediaService;

    public function __construct(Multimedia $multimedia, MultimediaService $multimediaService)
    {
        $this->multimedia = $multimedia;
        $this->service    = $multimediaService;
    }
    
    public function index(Request $request) 
    {
        $multimedia = Multimedia::all();
        return response()->json($multimedia);
    }

    public function show(Request $request, $id = null)
    {
        $multimedia = Multimedia::where('id', $id)->firstOrFail();
        return response()->json($multimedia);
    }
}
