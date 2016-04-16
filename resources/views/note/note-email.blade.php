<header>
    <p style="margin: 0;">URL 노트 {{ $type }}</p>
    <h1 style="font-size: 1.5em; margin-top: 0;">
        <a href="{{ $note->self_url() }}">{{ $note->title }}</a>
    </h1>
</header>
<ul>
    <li>URL: <a href="{{ $note->url }}">{{ $note->url }}</a></li>
    <li>태그: {{ $tag_names_string }}</li>
    <li>작성자: {{ $note->user->name }}</li>
</ul>
{!! $content !!}