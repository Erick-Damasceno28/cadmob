<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;


class HelloWorldController extends Controller
{
    // This method will return the HelloWorld view
    public function index() {
        return Inertia::render('HelloWorld');
    } 
}
