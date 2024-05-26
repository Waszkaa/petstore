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
            <input type="text" name="status" class="form-control" id="status" value="{{ $pet->status }}" required>
        </div>
        <div class="form-group">
            <label for="category_name">Category Name:</label>
            <input type="text" name="category_name" class="form-control" id="category_name" value="{{ $pet->category_name }}">
        </div>
        <div class="form-group">
            <label for="tags">Tags (comma separated):</label>
            <input type="text" name="tags" class="form-control" id="tags" value="{{ implode(',', $pet->tags ?? []) }}">
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
            <img src="{{ $photo_url }}" alt="Pet Image" class="img-thumbnail">
        @endforeach
    @endif
@endsection
