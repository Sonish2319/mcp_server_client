@extends('blog.layout')

@section('title', 'Edit Article')

@section('content')

<div class="container">

    <h1>Edit Article</h1>

    <form
        method="POST"
        action="{{ route('blog.update', $article) }}"
    >
        @csrf
        @method('PUT')

        <label>
            Title
        </label>

        <input
            type="text"
            name="title"
            value="{{ old('title', $article->title) }}"
            required
        >

        <label>
            Author
        </label>

        <input
            type="text"
            name="author"
            value="{{ old('author', $article->author) }}"
            required
        >

        <label>
            Content
        </label>

        <textarea
            name="content"
            required
        >{{ old('content', $article->content) }}</textarea>

        <button type="submit">
            Update Article
        </button>

        <a href="{{ route('blog.show', $article) }}">
            Cancel
        </a>

    </form>

</div>

@endsection