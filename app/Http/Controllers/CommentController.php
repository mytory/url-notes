<?php
/**
 * User: ahw
 * Date: 2016-04-10
 * Time: 오전 1:32
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Comment;
use \App\User;
use Auth;
use Mail;
use Parsedown;

class CommentController extends Controller {
    function save (Request $request){
        $comment = new Comment;
        $comment->user_id = Auth::user()->id;
        $comment->content = $request->get('content');
        $comment->note_id = $request->get('note_id');
        $comment->save();

        $users = User::whereRaw('1 = 1')->get();
        $note = $comment->note;
        $tags = $note->tags()->get();
        $params = [
            'note' => $note,
            'tags' => $tags,
            'comments' => $note->comments()->get()->sortByDesc('created_at')->slice(0, 10),
            'Parsedown' => new Parsedown,
        ];

        Mail::send('note.comment-email', $params, function($message) use ($comment, $users, $note)
        {
            foreach ($users as $user) {
                $message->to($user->email, $user->name)->subject("[URL 노트 댓글] {$comment->user->name}, $note->title");
            };
        });

        return redirect('note/' . $comment->note_id);
    }

}
