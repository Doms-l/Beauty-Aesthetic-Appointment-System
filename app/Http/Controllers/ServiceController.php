<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceController extends Controller
{
    /**
     * Display all available services grouped by category.
     */
    public function index()
    {
        $services = Service::where('is_available', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        $groupedServices = $services->groupBy('category');

        return view('services.index', compact(
            'services',
            'groupedServices'
        ));
    }
}