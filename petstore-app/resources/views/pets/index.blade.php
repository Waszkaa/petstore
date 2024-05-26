@extends('layouts.app')

@section('content')
    <h1 class="my-4">Pets</h1>

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

    <form action="{{ route('pets.index') }}" method="GET" class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" name="search" placeholder="Search" aria-label="Search" value="{{ request()->query('search') }}">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>

    <hr>

    <a class="btn btn-primary" href="{{ route('pets.create') }}">Add Pet</a>

    @if($pets->count())
        <div class="list-group mt-4">
            @foreach($pets as $pet)
                <div class="list-group-item">
                    <h5>{{ $pet->name }}</h5>
                    <p>Status: {{ $pet->status }}</p>
                    <a href="{{ route('pets.edit', $pet->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('pets.destroy', $pet->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            @endforeach
        </div>
    <!-- Dodanie paginacji -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $pets->links('pagination::bootstrap-4') }}
    </div>
    @else
    <p>No pets found.</p>
@endif
@endsection
