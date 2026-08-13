@extends('blog.layout')

@section('title', 'Blog')

@section('content')

<h1>Articles</h1>

<form method="GET" action="{{ route('blog.index') }}">
    <input
        type="text"
        name="q"
        value="{{ $search }}"
        placeholder="Search articles..."
    >

    <button type="submit">
        Search
    </button>

    @if($search)
        <a href="{{ route('blog.index') }}">
            Clear
        </a>
    @endif
</form>

<br>

@forelse($articles as $article)

    <div class="article">

        <h2>
            <a href="{{ route('blog.show', $article) }}">
                {{ $article->title }}
            </a>
        </h2>

        <p class="meta">
            By {{ $article->author }}
            |
            {{ $article->created_at->format('Y-m-d H:i') }}
        </p>

        <p>
            {{ Str::limit($article->content, 200) }}
        </p>

        <a href="{{ route('blog.show', $article) }}">
            Read more →
        </a>

    </div>

@empty

    <p>
        No articles found.
    </p>

@endforelse

{{ $articles->withQueryString()->links() }}

@endsection