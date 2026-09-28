<?php

namespace App\Http\Controllers;

use App\Models\Service;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     */
    public function index()
    {
        $services = Service::where('is_available', true)
            ->latest()
            ->take(3)
            ->get();

        return view('home', compact('services'));
    }
}