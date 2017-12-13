@extends('layouts.app')
<?php
$scriptlet = <<<scriptlet
(function () {
    var title = document.getElementsByTagName('title')[0].innerText;
    var url = location.href;
    location.href='{{URL}}?title=' + encodeURIComponent(title) + '&url=' + encodeURIComponent(url);;
}());
scriptlet;
$scriptlet = str_replace('{{URL}}', url('/note/form'), $scriptlet);
$scriptlet = preg_replace('/[\n\r]*/', '', $scriptlet);
?>
@section('content')
    <div class="container">
        <div class="page-header  page-header--narrow-margin-top">
            <h1 class="h3">URL 노트 생성 스크립틀릿</h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <p>아래 스크립트를 즐겨찾기에 등록하시면 됩니다.</p>
                <p><textarea readonly onclick="this.select();" rows="5" class="form-control">javascript:{{ $scriptlet }}</textarea></p>
                <p>혹은 아래 버튼을 즐겨찾기 바에 들어다 놓으세요.</p>
                <a href="javascript:{!! urlencode($scriptlet) !!}" class="btn btn-default">URL 노트</a>
           </div>
        </div>
    </div>
@endsection