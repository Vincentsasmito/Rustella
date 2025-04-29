@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Create New Order</h1>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('orders.store') }}" method="POST">
    @csrf

    @include('orders._form-fields')

    <button type="submit" class="btn btn-success mt-3">Submit Order</button>
    <a href="{{ route('orders.index') }}" class="btn btn-secondary mt-3">Back to List</a>
  </form>
</div>
@endsection