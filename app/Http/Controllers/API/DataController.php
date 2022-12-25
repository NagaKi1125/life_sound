<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Category;
use App\Models\Music;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

use function PHPUnit\Framework\isEmpty;

class DataController extends Controller
{
    // fetch category
    public function fetchGenres(Request $req)
    {
        $token = $this->getSpotifyAccessToken($req->id, $req->password);
        $url = 'https://api.spotify.com/v1/recommendations/available-genre-seeds';
        $data = Http::withHeaders(['Authorization' => $token])->get($url)->json();
        foreach ($data['genres'] as $d) {
            $category = new Category();
            $category->category = $d;
            $category->save();
        }

        // WARNING to delete all data if needed
        // Category::truncate();
        return $data;

    }


    public function fetchArtists(Request $req)
    {
        $url = 'http://127.0.0.1:5000/auto-gen/artists';
        $result = Http::timeout(500)->get($url)->json();

        foreach ($result as $r) {
            $a = Author::where('spotify_id', $r['spotify_id'])->first();
            if ($a) {
            } else {
                $author = new Author();
                $author->name = $r['name'];
                $author->thumbnail = $r['thumbnail'];
                $author->popularity = $r['popularity'];
                $author->spotify_id = $r['spotify_id'];

                $author->save();
            }
        }
        return $result;
    }

    public function fetchTracks(Request $req)
    {
        $url = 'http://127.0.0.1:5000/auto-gen/fetch-track';
        $results = Http::timeout(500)->get($url)->json();

        foreach ($results as $result) {
            $artists = $result['artists'];
            $artist_list = '';
            // ---check and save author if not exits
            foreach ($artists as $artist) {
                $author = Author::where('spotify_id', $artist['spotify_id'])->first();
                if ($author) {
                    $artist_list = $artist_list . '_' . $author->id;
                } else {
                    $a = new Author();
                    $a->spotify_id = $artist['spotify_id'];
                    $a->name = $artist['name'];
                    $a->thumbnail = $artist['thumbnail'];
                    $a->popularity = $artist['popularity'];
                    if ($a->save()) {
                        $artist_list = $artist_list . '_' . $a->id;
                    }
                }
            }

            // check and save music if not exits
            $music = Music::where('spotify_id', $result['spotify_id'])->first();
            if ($music) {

            } else {
                $m = new Music();
                $m->authors = $artist_list;
                $m->preview_url = $result['preview_url'];
                $m->duration = $result['duration'];
                $m->name = $result['name'];
                $m->spotify_id = $result['spotify_id'];
                $m->year = $result['year'];
                $m->url = '';
                $m->category = '';
                $m->thumbnail = '';

                $m->save();
            }

        }

        return $results;
        // Music::truncate();   
    }

    public function updateTrackGenres()
    {

        $music = Music::all();

        foreach ($music as $m) {
            $m->category = Category::all()->random()->id;
            $m->update();
        }

        return $music;
    }

    public function getSpotifyAccessToken($id, $password)
    {
        $spotify_data_url = 'https://accounts.spotify.com/api/token';
        $token_data = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($id . ':' . $password),
        ])
            ->asForm()
            ->post($spotify_data_url, [
                'grant_type' => 'client_credentials'
            ])
            ->json();

        $token = "Bearer " . $token_data['access_token'];
        return $token;
    }
}