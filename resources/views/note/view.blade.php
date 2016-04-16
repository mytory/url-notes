@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="page-header  page-header--narrow-margin-top">
            <h1 class="h3">
                {{ $note->title }}
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <p>
                    @foreach($tags as $tag)
                        <a href="{{ $tag->url() }}" class="label label-info">{{ $tag->name }}</a>
                    @endforeach
                </p>
                <p>
                    {{ $note->user->name }}
                    {{ $note->created_at }}
                </p>
            </div>
        </div>
        <div class="row  base-spacing">
            <div class="col-xs-12">
                <h2 class="h4">URL</h2>
                @if($note->url)
                <p>
                    <a target="_blank" href="{{ $note->url }}">
                        <i class="glyphicon glyphicon-new-window"></i>
                        {{ urldecode($note->url) }}
                    </a>
                </p>
                @else
                    <p class="text-muted">URL 없음</p>
                @endif

                @if($attachments->count() > 0)
                    <h2 class="h4">첨부파일</h2>
                @endif
                @foreach($attachments as $attachment)
                    <p>
                        <a target="_blank" href="{{ url('attachment/' . $attachment->id) }}">
                            <i class="glyphicon glyphicon-download"></i>
                            {{ urldecode($attachment->filename) }}
                        </a>
                    </p>
                @endforeach

                <h2 class="h4">내용</h2>
                @if($note->content)
                    <div class="view-note-content  panel  panel-default">
                        <div class="panel-body">
                            {!! $Parsedown->text($note->content) !!}
                        </div>
                    </div>
                @else
                    <p class="text-muted">내용 없음</p>
                @endif

            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <p class="text-center">
                    <a href="{{ url('notes') }}" class="btn btn-default">목록</a>
                    <a href="{{ url('note/form/' . $note->id) }}" class="btn btn-default pull-right">수정</a>
                    <a href="{{ url('note/delete/' . $note->id) }}" class="btn btn-danger pull-left" onclick="return confirm('정말로 삭제하시겠어요?');">삭제</a>
                </p>
            </div>
        </div>
        <div class="row">
            <form action="{{ url('comment') }}" class="col-xs-12" method="post">
                {!! csrf_field() !!}
                <input type="hidden" name="note_id" value="{{ $note->id }}">
                <h2 class="h4">댓글</h2>
                <textarea class="form-control  half-spacing" name="content" title="댓글"></textarea>
                <div class="text-right">
                    <button type="submit" class="btn btn-xs btn-primary">저장</button>
                </div>
            </form>
            @foreach($comments as $comment)
                <div class="col-xs-12" id="comment-{{ $comment->id }}">
                    <h3 class="h4">
                        {{ $comment->user->name }}
                        <small><a href="#comment-{{ $comment->id }}">{{ $comment->created_at }}</a></small>
                    </h3>
                    <div class="panel  panel-default">
                        <div class="panel-body">
                            {!! $Parsedown->text($comment->content) !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection