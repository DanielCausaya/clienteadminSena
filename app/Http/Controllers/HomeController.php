<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{


    public function index(){

        return view('home');

    }

    public function mision_vision(){

        return view('mision_vision');

    }
    public function contact(){

        return view('contact');

    }




}