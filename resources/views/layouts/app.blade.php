<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ !empty($title) ? $title . ' | URL Notes' : 'URL Notes' }}</title>
    <link rel="icon" type="image/png" href="{{ url('images/note.png') }}" />
    <link rel="icon" href="{{ url('images/note.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ url('images/note.png') }}">
    <link href="{{ elixir('css/app.css') }}" rel="stylesheet">
    @yield('head')

</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Menu</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    URL Notes
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/') }}">목록</a></li>
                    <li><a href="{{ url('/tags') }}">태그 목록</a></li>
                    <li><a href="{{ url('/scriptlet') }}">스크립틀릿</a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">로그인</a></li>
                        <li><a href="{{ url('/register') }}">가입</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>로그아웃</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>

                <form action="{{ url('notes') }}" class="navbar-form navbar-right" role="search">
                    <div class="form-group">
                        <input type="text" name="q" class="form-control" size="10" value="{{ $q or '' }}">
                    </div>
                    <button type="submit" class="btn btn-default">검색</button>
                </form>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
