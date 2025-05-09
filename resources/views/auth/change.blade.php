@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10">
  @if(session('status'))
    <div class="mb-4 text-green-600">{{ session('status') }}</div>
  @endif

  <form method="POST" action="{{ route('password.change.post') }}">
    @csrf

    <!-- Current Password -->
    <div class="mb-4">
      <label for="current_password">Current Password</label>
      <input id="current_password" type="password" name="current_password" required
             class="w-full border rounded p-2 @error('current_password') border-red-500 @enderror">
      @error('current_password')
        <p class="text-red-500 text-sm">{{ $message }}</p>
      @enderror
    </div>

    <!-- New Password -->
    <div class="mb-4">
      <label for="password">New Password</label>
      <input id="password" type="password" name="password" required
             class="w-full border rounded p-2 @error('password') border-red-500 @enderror">
      @error('password')
        <p class="text-red-500 text-sm">{{ $message }}</p>
      @enderror
    </div>

    <!-- Confirm New Password -->
    <div class="mb-4">
      <label for="password_confirmation">Confirm New Password</label>
      <input id="password_confirmation" type="password" name="password_confirmation" required
             class="w-full border rounded p-2">
    </div>

    <button type="submit"
            class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
      Change Password
    </button>
  </form>
</div>
@endsection