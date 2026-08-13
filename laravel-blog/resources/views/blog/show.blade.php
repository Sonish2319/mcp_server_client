@extends('blog.layout')

@section('title', $article->title)

@section('content')

<div class="container">

    <h1>
        {{ $article->title }}
    </h1>

    <p class="meta">
        By {{ $article->author }}
        |
        {{ $article->created_at->format('Y-m-d H:i') }}
    </p>

    <hr>

    <p>
        {!! nl2br(e($article->content)) !!}
    </p>

    <hr>

    <a
        class="button"
        href="{{ route('blog.edit', $article) }}"
    >
        Edit
    </a>

    <a
        href="{{ route('blog.index') }}"
    >
        Back
    </a>

    <form
        method="POST"
        action="{{ route('blog.destroy', $article) }}"
        style="display:inline"
        onsubmit="return confirm('Delete this article?');"
    >
        @csrf
        @method('DELETE')

        <button class="danger" type="submit">
            Delete
        </button>
    </form>

</div>

@endsection