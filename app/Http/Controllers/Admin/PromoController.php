<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PromoController extends Controller
{
    /** All promos (live and hidden) plus the live preview. */
    public function index()
    {
        return view('admin.promo', [
            'promos' => Promo::query()->with('service')->orderBy('id')->get(),
            'promoServices' => Service::where('category', 'Promo')->orderBy('name')->get(),
        ]);
    }

    /** Add a new promo. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => ['required', 'string', 'max:150'],
            'bar_text'  => ['nullable', 'string', 'max:150'],
            'image'     => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'is_active' => ['nullable', 'boolean'],
            'service_id' => ['nullable', 'integer', Rule::exists('services', 'id')->where('category', 'Promo')],
        ]);

        Promo::create([
            'service_id' => $validated['service_id'] ?? null,
            'title'     => $validated['title'],
            'bar_text'  => $validated['bar_text'] ?? null,
            'image'     => $this->saveImage($request->file('image')),
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Promo added.');
    }

    /** Edit one promo. */
    public function update(Request $request, Promo $promo)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:150'],
            'bar_text'     => ['nullable', 'string', 'max:150'],
            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'],
            'is_active'    => ['nullable', 'boolean'],
            'service_id'   => ['nullable', 'integer', Rule::exists('services', 'id')->where('category', 'Promo')],
        ]);

        $data = [
            'service_id' => $validated['service_id'] ?? null,
            'title'     => $validated['title'],
            'bar_text'  => $validated['bar_text'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('image')) {
            $this->deleteImage($promo->image);
            $data['image'] = $this->saveImage($request->file('image'));
        } elseif ($request->boolean('remove_image')) {
            // go back to the original poster
            $this->deleteImage($promo->image);
            $data['image'] = null;
        }

        $promo->fill($data)->save();

        return back()->with('success', 'Promo updated.');
    }

    /** Delete one promo and its uploaded poster. */
    public function destroy(Promo $promo)
    {
        $this->deleteImage($promo->image);

        $promo->delete();

        return back()->with('success', 'Promo deleted.');
    }

    private function saveImage(UploadedFile $file): string
    {
        $directory = public_path('images/promos');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = 'promo-' . time() . '-' . Str::lower(Str::random(6)) . '.' . $file->extension();

        $file->move($directory, $filename);

        return 'images/promos/' . $filename;
    }

    /** Only uploaded promos are deleted; the original promo.jpg is never touched. */
    private function deleteImage(?string $path): void
    {
        if ($path && str_starts_with($path, 'images/promos/')) {
            @unlink(public_path($path));
        }
    }
}
