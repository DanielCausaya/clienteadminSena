<?php

 
 


namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;


class ProductController extends Controller
{
    private function fetchDataFromApi($url)
    {
        $response = Http::get($url);
        return $response->json();

    }

    public function index()  
    {

        $url = env('URL_SERVER_API');

        $products = $this->fetchDataFromApi($url . '/products');
        return $products;
        //return view('areas.index', compact('products'));
    }

    public function show($id)
    {

        $url = env('URL_SERVER_API');

        $product = $this->fetchDataFromApi($url . '/products/' . $id);

        return view('products.show', compact('product'));
    }
}