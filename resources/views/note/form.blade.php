@extends('layouts.app')
@section('head')
    <script src="https://code.jquery.com/jquery-2.2.3.min.js"
            integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="
            crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
    <script>
        jQuery(document).ready(function($){
            $('[name="tag_names[]"]').select2({
                tags: true,
                tokenSeparators: [',']
            })
        });
    </script>
@endsection
@section('content')
    <div class="container">
        <div class="page-header">
            <h1 class="h3">URL 노트</h1>
        </div>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <form class="col-xs-12" action="{{ url('note') }}" method="post" enctype="multipart/form-data">
                {!! csrf_field() !!}
                @if ($note->id)
                    {{ method_field('PUT') }}
                @endif
                <input type="hidden" name="id" value="{{ old('id') ?: $note->id }}">
                <div class="form-group">
                    <label for="title">제목</label>
                    <input required class="form-control" type="text" name="title" id="title" value="{{ old('title') ?: $note->title }}">
                </div>
                <div class="form-group">
                    <label for="url">URL</label>
                    <input class="form-control" type="url" name="url" id="url" value="{{ old('url') ?: $note->url }}">
                </div>
                <div class="form-group">
                    <label for="tag_names">태그</label>
                    <select class="form-control" name="tag_names[]" id="tag_names" multiple="multiple">
                        @foreach($all_tag_names as $tag_name)
                            <option {{ in_array($tag_name->name, $tag_names) ? 'selected="selected"' : '' }}>{{ $tag_name->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="attachment">첨부파일</label>
                    <input style="width: 100%;" type="file" name="attachment" id="attachment" value="">
                </div>
                <div class="form-group">
                    <label for="content">내용</label>
                    <textarea class="form-control" name="content" id="content" rows="5" placeholder="마크다운 사용 가능">{{ old('content') ?: $note->content}}</textarea>
                </div>
                <p class="text-center">
                    <input type="submit" value="저장" class="btn btn-primary">
                </p>
            </form>
        </div>
    </div>
@endsection