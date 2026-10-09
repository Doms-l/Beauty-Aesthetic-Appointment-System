<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Support\ServiceCatalog;

class ServiceController extends Controller
{
    /**
     * Display all available services grouped by category.
     * Promo comes first, then the regular categories.
     */
    public function index()
    {
        $services = Service::where('is_available', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        $order = array_flip(ServiceCatalog::CATEGORIES);

        $groupedServices = $services
            ->groupBy('category')
            ->sortBy(fn ($group, $category) => $order[$category] ?? 99);

        return view('services.index', compact(
            'services',
            'groupedServices'
        ));
    }
}
