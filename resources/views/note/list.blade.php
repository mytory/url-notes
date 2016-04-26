@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header  page-header--narrow-margin-top">
            <h1 class="h3">
                {{ $title }}
                @if($page)
                    <small>{{ $page }}페이지</small>
                @endif
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <p class="text-center"><a href="{{url('note/form')}}" class="btn btn-primary btn-lg">URL 노트 생성</a></p>
            </div>

            @foreach ($notes as $note)
                <div class="col-xs-12  col-lg-6">
                    <div class="well  list-note">
                        <h2 class="list-note__title  list-note__ellipsis  list-note__ellipsis--none-mobile">
                            <a title="{{ $note->title }}" href="{{ $note->self_url() }}">{{ $note->title }}</a>
                        </h2>
                        <p><small>
                            @foreach($note->tags()->get() as $tag)
                                <a href="{{ $tag->url() }}" class="label label-info">{{ $tag->name }}</a>
                            @endforeach
                        </small></p>
                        <p class="list-note__ellipsis">
                            <a target="_blank" href="{{ $note->url }}">
                                <i class="glyphicon glyphicon-new-window"></i>
                                {{ urldecode($note->url) }}
                            </a>
                        </p>
                        <p class="list-note__ellipsis  text-muted  small">{{ strip_tags($note->content) }}</p>
                        <p class="text-right">
                            <small>
                                <a href="{{ url('notes/user/' . $note->user->id) }}">{{ $note->user->name  }}</a>
                                {{ $note->created_at  }} <a href="{{ url('note/form/' . $note->id) }}">수정</a>
                            </small>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row  text-center">
            {!! $notes->links() !!}
        </div>
    </div>
@endsection