@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header  page-header--narrow-margin-top">
            <h1 class="h3">
                {{ $title }}
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
            <p>
                @foreach ($tags as $tag)
                    <a href="{{ $tag->url() }}" class="btn btn-default" style="margin-bottom: .5em;">{{ $tag->name }}</a>
                @endforeach
            </p>
            </div>
        </div>
    </div>
@endsection