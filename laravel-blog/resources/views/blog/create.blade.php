@extends('blog.layout')

@section('title', 'Create Article')

@section('content')

<div class="container">

    <h1>Create Article</h1>

    <form
        method="POST"
        action="{{ route('blog.store') }}"
    >
        @csrf

        <label>
            Title
        </label>

        <input
            type="text"
            name="title"
            value="{{ old('title') }}"
            required
        >

        <label>
            Author
        </label>

        <input
            type="text"
            name="author"
            value="{{ old('author') }}"
            required
        >

        <label>
            Content
        </label>

        <textarea
            name="content"
            required
        >{{ old('content') }}</textarea>

        <button type="submit">
            Create Article
        </button>

        <a href="{{ route('blog.index') }}">
            Cancel
        </a>

    </form>

</div>

@endsection