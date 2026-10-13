<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use App\Support\SocialPlatforms;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SocialLinkController extends Controller
{
    public function index()
    {
        return view('admin.socials', [
            'links'     => SocialLink::query()->orderBy('sort_order')->orderBy('id')->get(),
            'platforms' => SocialPlatforms::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $data['sort_order'] ??= ((int) SocialLink::query()->max('sort_order')) + 1;

        SocialLink::create($data);

        return back()->with('success', 'Social account added. It is now in the footer.');
    }

    public function update(Request $request, SocialLink $social)
    {
        $data = $this->validated($request);

        $data['sort_order'] ??= $social->sort_order;

        $social->update($data);

        return back()->with('success', 'Social account updated.');
    }

    public function destroy(SocialLink $social)
    {
        $social->delete();

        return back()->with('success', 'Social account deleted.');
    }

    /**
     * Validate the form, pick the platform (and so the logo) automatically
     * when "Auto-detect" is chosen, and clean the link / number / email.
     */
    private function validated(Request $request): array
    {
        $input = $request->validate([
            'platform'   => ['nullable', 'string', Rule::in(array_merge(['auto'], array_keys(SocialPlatforms::all())))],
            'label'      => ['required', 'string', 'max:100'],
            'url'        => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active'  => ['nullable', 'boolean'],
        ]);

        $platform = $input['platform'] ?? 'auto';

        if ($platform === '' || $platform === 'auto') {
            $platform = SocialPlatforms::detect($input['url']);
        }

        [$value, $error] = SocialPlatforms::clean($platform, $input['url']);

        if ($error !== null) {
            throw ValidationException::withMessages(['url' => $error]);
        }

        return [
            'platform'   => $platform,
            'label'      => trim($input['label']),
            'url'        => $value,
            'sort_order' => isset($input['sort_order']) ? (int) $input['sort_order'] : null,
            'is_active'  => $request->boolean('is_active'),
        ];
    }
}
