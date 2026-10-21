<?php

 
 

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;


class TrainingCenterController extends Controller
{
    private function fetchDataFromApi($url)
    {
        $response = Http::get($url);
        return $response->json();
    }

    public function index()  
    {

        $url = env('URL_SERVER_API');

        $teacher = $this->fetchDataFromApi($url . '/trainig');

        return view('trainigs.index', compact('trainigs'));
    }

    public function show($id)
    {

        $url = env('URL_SERVER_API');

        $training = $this->fetchDataFromApi($url . '/trainings/' . $id);

        return view('trainings.show', compact('training'));
    }
}