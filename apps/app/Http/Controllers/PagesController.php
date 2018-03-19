<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    function index(){
        $title = 'Welcome to Laravel Apps';
        return view('pages.index')->with('title',$title);
    }

    function about(){
        $title = 'About Us';
        return view('pages.about')->with('title', $title);
    }

    function services(){
        $data = array(
                'title' => 'Our Services',
                'services' => ['Web Developments', 'Web Design', 'Mobile Apps']
        );
        return view('pages.services')->with($data);
    }
}
