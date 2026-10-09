@extends('layouts.app')

@section('title', 'Manage Services | M. Cares')

@section('content')

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">ADMIN · SERVICES</span>
        <h1>Manage beauty services</h1>
        <p>Add services with a photo, update pricing, and control availability.</p>
    </div>
</section>

<section class="section compact">

    <div class="container admin-two-col">

        {{-- =====================================================
             ADD A NEW SERVICE
             ===================================================== --}}
        <div class="panel">

            <span class="eyebrow">ADD SERVICE</span>
            <h2>New service</h2>

            <form method="POST"
                  action="{{ route('admin.services.store') }}"
                  class="form-stack"
                  enctype="multipart/form-data">

                @csrf

                <label>Category
                    <select name="category" required>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </label>

                <label>Name
                    <input name="name" value="{{ old('name') }}" required>
                </label>

                <label>Description
                    <textarea name="description" rows="4">{{ old('description') }}</textarea>
                </label>

                <div class="svc-photo-field">

                    <span class="svc-photo-label">Service photo</span>

                    <div class="svc-photo-preview" id="new-service-preview">
                        <span>No photo selected</span>
                    </div>

                    <input type="file"
                           name="image"
                           id="new-service-image"
                           accept="image/jpeg,image/png,image/webp"
                           class="svc-file-input">

                    <small>JPG, PNG or WEBP · up to 4 MB</small>

                </div>

                <div class="form-grid two">
                    <label>Price
                        <input type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" required>
                    </label>

                    <label>Duration (min)
                        <input type="number" min="15" max="600" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" required>
                    </label>
                </div>

                <button class="primary-button full">Add service</button>

            </form>

        </div>


        {{-- =====================================================
             CURRENT SERVICES
             ===================================================== --}}
        <div class="panel">

            <span class="eyebrow">SERVICE LIST</span>
            <h2>Current services</h2>

            <div class="admin-list">

                @forelse($services as $service)

                    <div class="admin-item svc-item">

                        <div class="svc-summary">

                            @if($service->image_url)
                                <img class="svc-thumb" src="{{ $service->image_url }}" alt="{{ $service->name }}" loading="lazy">
                            @else
                                <span class="svc-thumb svc-thumb-empty">No photo</span>
                            @endif

                            <div>
                                <strong>{{ $service->name }}</strong>
                                <p>
                                    ₱{{ number_format($service->price, 2) }}
                                    · {{ $service->duration_minutes }} min
                                    · {{ $service->is_available ? 'Available' : 'Hidden' }}
                                </p>
                                <small>{{ $service->category }}</small>
                            </div>

                        </div>

                        <details class="svc-details">

                            <summary>Edit</summary>

                            {{-- the same photo + description clients see on the Services page --}}
                            <div class="svc-preview">

                                @if($service->image_url)
                                    <img src="{{ $service->image_url }}" alt="{{ $service->name }}" loading="lazy">
                                @else
                                    <div class="svc-preview-empty">No photo yet</div>
                                @endif

                                <p>{{ $service->effective_description ?: 'No description yet.' }}</p>

                            </div>

                            <form method="POST"
                                  action="{{ route('admin.services.update', $service) }}"
                                  class="form-stack mini"
                                  enctype="multipart/form-data">

                                @csrf
                                @method('PUT')

                                <select name="category" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" @selected($service->category === $category)>{{ $category }}</option>
                                    @endforeach
                                </select>

                                <input name="name" value="{{ $service->name }}" required>

                                <textarea name="description" rows="5" placeholder="Description">{{ $service->effective_description }}</textarea>

                                <div class="svc-photo-field">
                                    <span class="svc-photo-label">Change photo</span>
                                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="svc-file-input">
                                </div>

                                <div class="form-grid two">
                                    <input type="number" step="0.01" min="0" name="price" value="{{ $service->price }}" required>
                                    <input type="number" min="15" name="duration_minutes" value="{{ $service->duration_minutes }}" required>
                                </div>

                                <label class="check-row">
                                    <input type="checkbox" name="is_available" value="1" @checked($service->is_available)>
                                    Available
                                </label>

                                <button class="small-button">Save</button>

                            </form>

                            <form method="POST" action="{{ route('admin.services.destroy', $service) }}">
                                @csrf
                                @method('DELETE')
                                <button class="danger-link"
                                        onclick="return confirm('Delete this service? This cannot be undone.')">
                                    Delete
                                </button>
                            </form>

                        </details>

                    </div>

                @empty

                    <p>No services yet.</p>

                @endforelse

            </div>

            <div class="pagination">{{ $services->links() }}</div>

        </div>

    </div>

</section>


<style>
    .svc-item { align-items: flex-start; }

    .svc-summary { display: flex; gap: 14px; align-items: center; }

    .svc-thumb {
        flex: none;
        width: 64px;
        height: 64px;
        object-fit: cover;
        border-radius: 14px;
        border: 1px solid var(--line, #ddd);
    }
    .svc-thumb-empty {
        display: grid;
        place-items: center;
        font-size: .68rem;
        text-align: center;
        color: var(--text-muted, #7d7074);
        background: var(--surface-soft, #fff1f7);
    }

    .svc-details { flex: 1 1 340px; }

    .svc-preview {
        margin: 14px 0;
        padding: 12px;
        border-radius: 18px;
        background: var(--surface-soft, #fff1f7);
        border: 1px solid var(--line, #ddd);
    }
    .svc-preview img {
        width: 100%;
        max-height: 220px;
        object-fit: cover;
        border-radius: 14px;
        display: block;
    }
    .svc-preview-empty {
        padding: 36px 0;
        text-align: center;
        color: var(--text-muted, #7d7074);
    }
    .svc-preview p {
        margin: 12px 2px 2px;
        font-size: .92rem;
        line-height: 1.55;
        color: var(--text, #222);
    }

    .svc-photo-field { display: grid; gap: 8px; }
    .svc-photo-label { font-weight: 700; color: var(--heading, #62444D); }
    .svc-photo-field small { color: var(--text-muted, #7d7074); }

    .svc-photo-preview {
        display: grid;
        place-items: center;
        min-height: 150px;
        overflow: hidden;
        border-radius: 18px;
        border: 2px dashed var(--line, #ccc);
        background: var(--surface-soft, #fff1f7);
        color: var(--text-muted, #7d7074);
    }
    .svc-photo-preview img { width: 100%; max-height: 240px; object-fit: cover; display: block; }

    .svc-file-input {
        padding: 10px;
        border-radius: 14px;
        border: 1px solid var(--input-border, #ded5d9);
        background: var(--input-bg, #fff);
        color: var(--text, #222);
        width: 100%;
    }
</style>

<script>
    // shows the chosen photo before the service is created
    (function () {
        const input = document.getElementById('new-service-image');
        const box = document.getElementById('new-service-preview');

        if (!input || !box) { return; }

        input.addEventListener('change', function () {
            const file = input.files && input.files[0];

            if (!file) {
                box.innerHTML = '<span>No photo selected</span>';
                return;
            }

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.alt = 'Selected photo';
            box.innerHTML = '';
            box.appendChild(img);
        });
    })();
</script>

@endsection
