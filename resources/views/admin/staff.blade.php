@extends('layouts.app')

@section('title', 'Manage Staff | M. Cares')

@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">ADMIN · STAFF</span><h1>Clinic staff</h1><p>Create staff accounts and keep assigned roles organized.</p></div></section>
<section class="section compact"><div class="container admin-two-col">
<div class="panel"><span class="eyebrow">ADD STAFF</span><h2>Staff account</h2><form method="POST" action="{{ route('admin.staff.store') }}" class="form-stack">@csrf<div class="form-grid two"><label>First name<input name="first_name" required></label><label>Last name<input name="last_name" required></label></div><label>Email<input type="email" name="email" required></label><label>Phone<input name="phone"></label><div class="form-grid two"><label>Position<input name="position" placeholder="Beauty Specialist" required></label><label>Specialization<input name="specialization"></label></div><div class="form-grid two"><label>Password<input type="password" name="password" minlength="8" required></label><label>Confirm password<input type="password" name="password_confirmation" minlength="8" required></label></div><button class="primary-button full">Create staff</button></form></div>
<div class="panel"><span class="eyebrow">STAFF LIST</span><h2>Current staff</h2><div class="admin-list">@forelse($staff as $member)<div class="admin-item"><div><strong>{{ $member->user->full_name }}</strong><p>{{ $member->position }} · {{ $member->specialization ?: 'General' }}</p><small>{{ $member->user->email }}</small></div><span class="status {{ $member->is_available ? 'confirmed' : 'cancelled' }}">{{ $member->is_available ? 'Available' : 'Unavailable' }}</span></div>@empty<p>No staff yet.</p>@endforelse</div></div>
</div></section>
@endsection
