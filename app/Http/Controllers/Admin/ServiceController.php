<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Support\ServiceCatalog;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('category')
            ->orderBy('name')
            ->paginate(12);

        $categories = ServiceCatalog::CATEGORIES;

        return view('admin.services', compact('services', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => ['required', Rule::in(ServiceCatalog::CATEGORIES)],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'duration_minutes' => ['required', 'integer', 'min:15', 'max:600'],
        ]);

        unset($validated['image']);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->saveImage($request->file('image'), $validated['name']);
        }

        Service::create($validated + ['is_available' => true]);

        return back()->with('success', 'Service created successfully.');
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'category' => ['required', Rule::in(ServiceCatalog::CATEGORIES)],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'duration_minutes' => ['required', 'integer', 'min:15', 'max:600'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        unset($validated['image']);

        // A new photo replaces the old uploaded one
        if ($request->hasFile('image')) {
            $this->deleteImage($service->image);
            $validated['image'] = $this->saveImage($request->file('image'), $validated['name']);
        }

        $validated['is_available'] = $request->boolean('is_available');
        $service->update($validated);

        return back()->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        // A service that already has bookings is kept for the records
        if ($service->appointments()->exists()) {
            return back()->withErrors([
                'service' => 'This service already has appointment records, so it cannot be deleted. Untick "Available" to hide it from clients instead.',
            ]);
        }

        $this->deleteImage($service->image);

        $service->delete();

        return back()->with('success', 'Service deleted successfully.');
    }


    /**
     * Saves an uploaded photo in public/images/services and returns its path.
     */
    private function saveImage(UploadedFile $file, string $name): string
    {
        $directory = public_path('images/services');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = Str::slug($name) . '-' . time() . '.' . $file->extension();

        $file->move($directory, $filename);

        return 'images/services/' . $filename;
    }

    /**
     * Deletes a photo that was uploaded by the admin
     * (the original folder photos are never touched).
     */
    private function deleteImage(?string $path): void
    {
        if ($path && str_starts_with($path, 'images/services/')) {
            @unlink(public_path($path));
        }
    }
}
