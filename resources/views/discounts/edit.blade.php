@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Discount</h1>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('discounts.update', $discount->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="form-group">
        <label>Code</label>
        <input type="text" name="code" value="{{ old('code', $discount->code) }}" class="form-control" maxlength="20" required>
      </div>

      <div class="form-group">
        <label>Percent</label>
        <input type="number" name="percent" value="{{ old('percent', $discount->percent) }}" class="form-control" min="0" max="100" required>
      </div>

      <div class="form-group">
        <label>Max Value</label>
        <input type="number" name="max_value" value="{{ old('max_value', $discount->max_value) }}" class="form-control" min="0" required>
      </div>

      <div class="form-group">
        <label>Min Purchase</label>
        <input type="number" name="min_purchase" value="{{ old('min_purchase', $discount->min_purchase) }}" class="form-control" min="0" required>
      </div>

      <button class="btn btn-primary mt-3">Update Discount</button>
      <a href="{{ route('discounts.index') }}" class="btn btn-secondary mt-3">Cancel</a>
    </form>
</div>
@endsection