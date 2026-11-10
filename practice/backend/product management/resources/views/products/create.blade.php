@extends('layout')

@section('content')
<form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-5">
    <div class="form-field">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-field">
        <label for="price" class="form-label">Price</label>
        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}">
        @error('price')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-field">
        <label for="category" class="form-label">Category</label>
        <select class="form-control @error('category') is-invalid @enderror" id="category" name="category">
            <option {{ old('category') === null ? 'selected' : '' }} hidden value="">Select a category</option>
            @foreach (App\Models\Category::all() as $category)
            <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-field">
        <label for="image" class="form-label">Image</label>
        <input type="file" accept="image/png,image/jpeg,image/webp" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
        @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn-solid theme-primary">Add Product</button>
</form>
@endsection