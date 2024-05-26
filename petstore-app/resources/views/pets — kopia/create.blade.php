@extends('layouts.app')

@section('content')
    <h1 class="my-4">Add Pet</h1>

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

    <form action="{{ route('pets.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" class="form-control" id="name" required>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <input type="text" name="status" class="form-control" id="status" required>
        </div>
        <div class="form-group">
            <label for="category_name">Category Name:</label>
            <input type="text" name="category_name" class="form-control" id="category_name">
        </div>
        <div class="form-group">
            <label for="tags">Tags (comma separated):</label>
            <input type="text" name="tags" class="form-control" id="tags">
        </div>
        <div class="form-group">
            <label for="file">Upload Image:</label>
            <input type="file" name="file" class="form-control" id="file">
        </div>
        <button type="submit" class="btn btn-primary">Add</button>
    </form>
@endsection
