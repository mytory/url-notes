<header>
    <h1 style="margin: 0;">댓글</h1>
    <p style="font-size: 1.5em; margin-top: 0;">
        <a href="{{ $note->self_url() }}">
            노트: {{ $note->title }}
        </a>
    </p>
</header>

@foreach($comments as $comment)
    <h2 style="font-size: 1.2em;">
        {{ $comment->user->name }}
        <small><a href="{{ $note->self_url() }}#comment-{{ $comment->id }}">{{ $comment->created_at }}</a></small>
    </h2>
    {!! $Parsedown->text($comment->content) !!}

    <hr>
@endforeach

<ul>
    <li>URL: <a href="{{ $note->url }}">{{ $note->url }}</a></li>
    <li>
        태그: {{ $tags->implode('name', ', ') }}
    </li>
</ul>
{!! $Parsedown->text($note->content) !!}