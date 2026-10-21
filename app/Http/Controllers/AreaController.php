<?php

 
 


namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;


class AreaController extends Controller
{
    private function fetchDataFromApi($url)
    {
        $response = Http::get($url);
        return $response->json();

    }

    public function index()  
    {

        $url = env('URL_SERVER_API');

        $areas = $this->fetchDataFromApi($url . '/areas');
        return $areas;
        //return view('areas.index', compact('areas'));
    }

    public function show($id)
    {

        $url = env('URL_SERVER_API');

        $area = $this->fetchDataFromApi($url . '/areas/' . $id);

        return view('areas.show', compact('area'));
    }
}