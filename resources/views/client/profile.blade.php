@extends('layouts.app')

@section('title', 'My Profile | M. Cares')

@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">MY PROFILE</span><h1>Keep your client information updated.</h1><p>This information is used when managing your appointments.</p></div></section>
<section class="section compact"><div class="container narrow-panel">
<form method="POST" action="{{ route('client.profile.update') }}" class="form-stack">
@csrf @method('PUT')
<div class="form-grid two"><label>First name<input name="first_name" value="{{ old('first_name', $user->first_name) }}" required></label><label>Last name<input name="last_name" value="{{ old('last_name', $user->last_name) }}" required></label></div>
<div class="form-grid two"><label>Email<input type="email" name="email" value="{{ old('email', $user->email) }}" required></label><label>Phone<input name="phone" value="{{ old('phone', $user->phone) }}" required></label></div>
<div class="form-grid two"><label>Date of birth<input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d')) }}"></label><label>Address<input name="address" value="{{ old('address', $user->address) }}"></label></div>
<button class="primary-button" type="submit">Save changes</button>
</form></div></section>
@endsection
