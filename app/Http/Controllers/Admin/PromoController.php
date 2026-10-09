<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class PromoController extends Controller
{
    public function edit()
    {
        return view('admin.promo', ['promo' => Promo::current()]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:150'],
            'bar_text'     => ['nullable', 'string', 'max:150'],
            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'],
            'is_active'    => ['nullable', 'boolean'],
        ]);

        $promo = Promo::query()->first() ?? new Promo();

        $data = [
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

        return back()->with('success', 'Promo updated. It is now live on the website and dashboards.');
    }

    private function saveImage(UploadedFile $file): string
    {
        $directory = public_path('images/promos');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = 'promo-' . time() . '.' . $file->extension();

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
