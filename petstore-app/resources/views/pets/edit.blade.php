@extends('layouts.app')

@section('content')
    <h1 class="my-4">Edit Pet</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('pets.update', $pet->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ $pet->name }}" required>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" class="form-control" id="status" required>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ $pet->status == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="category_name">Category Name:</label>
            <select name="category_name" class="form-control" id="category_name" required>
                @foreach($categories as $category)
                    <option value="{{ $category->name }}" {{ $pet->category->name == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="tags">Tags:</label>
            <select name="tags[]" class="form-control" id="tags" multiple>
                @foreach($tags as $tag)
                    <option value="{{ $tag->name }}" {{ in_array($tag->name, $pet->tags->pluck('name')->toArray()) ? 'selected' : '' }}>{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>

    <hr>

    <h2 class="my-4">Upload Image</h2>

    <form action="{{ route('pets.uploadImage', $pet->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="file">Upload Image:</label>
            <input type="file" name="file" class="form-control" id="file" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    @if($pet->photo_urls)
        <hr>
        <h2 class="my-4">Existing Images</h2>
        @foreach($pet->photo_urls as $photo_url)
            <div class="mb-2">
                <a href="{{ $photo_url }}" target="_blank">{{ basename($photo_url) }}</a>
            </div>
        @endforeach
    @endif
@endsection
