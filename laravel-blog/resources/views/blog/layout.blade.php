<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Laravel Blog')
    </title>

    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 0 auto;
            padding: 30px;
            background: #f5f5f5;
        }

        nav {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        a {
            text-decoration: none;
        }

        .article {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px;
            box-sizing: border-box;
        }

        textarea {
            min-height: 200px;
        }

        button,
        .button {
            padding: 10px 16px;
            border: none;
            background: #222;
            color: white;
            cursor: pointer;
            border-radius: 4px;
        }

        .danger {
            background: #b91c1c;
        }

        .success {
            background: #dcfce7;
            padding: 12px;
            margin-bottom: 20px;
        }

        .error {
            background: #fee2e2;
            padding: 12px;
            margin-bottom: 20px;
        }

        .meta {
            color: #666;
            font-size: 14px;
        }
    </style>
</head>

<body>

<nav>
    <div>
        <a href="{{ route('blog.index') }}">
            <strong>Laravel Blog</strong>
        </a>
    </div>

    <div>
        <a
            class="button"
            href="{{ route('blog.create') }}"
        >
            New Article
        </a>
    </div>
</nav>

@if(session('success'))
    <div class="success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="error">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@yield('content')

</body>
</html>