@extends('layout')

@php
    $sortColumns = [ 'created_at' => 'created at', 'updated_at' => 'updated at', 'name' => 'name', 'price' => 'price' ];
@endphp

@section('content')
<form action="{{ route('home') }}" method="GET" class="d-flex flex-column gap-5">
    <div class="form-field">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ request('name') ?? '' }}">
    </div>
    <div class="d-flex gap-2">
        <div class="form-field flex-grow-1">
            <label for="min_price" class="form-label">Minimum Price</label>
            <input type="number" step="0.01" class="form-control @error('min_price') is-invalid @enderror" id="min_price" name="min_price" value="{{ request('min_price') ?? '' }}">
        </div>
        <div class="form-field flex-grow-1">
            <label for="max_price" class="form-label">Maximum Price</label>
            <input type="number" step="0.01" class="form-control @error('max_price') is-invalid @enderror" id="max_price" name="max_price" value="{{ request('max_price') ?? '' }}">
        </div>
        <div class="form-field flex-grow-1">
            <label for="category" class="form-label">Category</label>
            <select class="form-control @error('category') is-invalid @enderror" id="category" name="category">
                <option {{ request('category') === null ? 'selected' : '' }} value="">All categories</option>
                @foreach (App\Models\Category::all() as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }} >{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="d-flex align-items-end gap-2">
        <div class="form-field">
            <label for="order-by" class="form-label">Order By</label>
            <select name="order-by" id="order-by" class="form-control">
                @foreach ($sortColumns as $column => $label)
                <option value="{{ $column }}" {{ request('order-by') === $column ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-field">
            <input class="radio" name="order-by-direction" type="radio" id="order-by-direction-desc" value="desc" @checked(request('order-by-direction') === 'desc' || is_null(request('order-by-direction'))) />
            <label for="order-by-direction-desc">descending</label>
        </div>
            <div class="form-field">
            <input class="radio" name="order-by-direction" type="radio" id="order-by-direction-asc" value="asc" @checked(request('order-by-direction') === 'asc') />
            <label for="order-by-direction-asc">ascending</label>
        </div>
    </div>
    <button type="submit" class="btn-solid theme-primary w-100">Search</button>
    @foreach ($errors->all() as $error)
        <div class="invalid-feedback d-block">{{ $error }}</div>
    @endforeach
</form>
<hr>
<div class="grid grid-cols-4 text-center">
    @foreach ($products as $product)
    <section class="card">
        <img src="{{ Storage::url($product->image_url) }}" class="card-img-top" style="height: 15rem" alt="{{ $product->name }}">
        <div class="card-body text-center">
            <h4 class="card-title">{{ $product->name }}</h4>
            <p class="card-text">${{ $product->price }}</p>
            <span class="badge theme-primary w-fit mx-auto">{{ $product->category->name }}</span>
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('product.edit', $product) }}" class="btn-solid theme-primary flex-grow-1">Edit</a>
                <form action="{{ route('product.destroy', $product) }}" method="POST" class="flex-grow-1">
                    @method('DELETE')
                    <button type="submit" class="btn-solid theme-danger w-100" onclick="return confirm('are you sure you want to delete this product?')">Delete</button>
                </form>
            </div>
        </div>
    </section>
    @endforeach
</div>
@endsection