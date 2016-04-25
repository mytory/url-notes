<?php

namespace App\Http\Controllers;

use App\Attachment;
use Illuminate\Http\Request;
use Storage;
use Validator;
use Auth;
use Mail;

use App\Http\Requests;
use App\User;
use App\Note;
use App\Tag;

class NoteController extends Controller {
    function notes(Request $request) {
        $query = Note::orderBy('notes.created_at', 'DESC')
            ->join('users', 'notes.user_id', '=', 'users.id')
        ->select(['notes.*', 'users.name']);
        $page_title = 'URL Note 목록';
        if ($q = $request->input('q')) {
            $page_title = '검색결과: ' . $q;
            $query->orWhere('title', 'like', "%$q%")
                ->orWhere('url', 'like', "%$q%")
                ->orWhere('content', 'like', "%$q%")
                ->orWhere('users.name', 'like', "%$q%")
            ;
        }

        return view('note.list', [
            'notes' => $query->paginate(20),
            'q' => $q,
            'title' => $page_title,
            'page' => $request->get('page'),
        ]);
    }

    function notesByTag($tag_name, Request $request){
        $query = Note::orderBy('notes.created_at', 'DESC')
            ->join('tags', 'notes.id', '=', 'tags.note_id')
            ->select(['notes.*', 'tags.name'])
            ->where('tags.name', '=', $tag_name);
        $page_title = '태그: ' . $tag_name;

        return view('note.list', [
            'notes' => $query->paginate(20),
            'tag_name' => $tag_name,
            'title' => $page_title,
            'page' => $request->get('page'),
        ]);
    }

    function tagList(){
        $tags = Tag::select('name')->distinct()->get();
        return view('note.tags', [
            'title' => '태그 목록',
            'tags' => $tags,
        ]);
    }

    function note(Note $note){
        $tags = $note->tags()->get();
        $comments = $note->comments()->get()->sortByDesc('created_at');
        $attachments = $note->attachments()->get()->sortByDesc('created_at');

        return view('note.view', [
            'title' => $note->title,
            'note' => $note,
            'tags' => $tags,
            'comments' => $comments,
            'attachments' => $attachments,
            'Parsedown' => new \Parsedown(),
        ]);
    }

    function form(Note $note, Request $request){
        $tag_names = [];
        if(empty($note)){
            $note = new Note;
        }

        $note->title = (($note->title) ?: trim($request->get('title')));
        $note->url = (($note->url) ?: trim($request->get('url')));

        $tags = Tag::whereNoteId($note->id)->get();
        if(!$tags->isEmpty()){
            $tmp = [];
            foreach ($tags as $tag) {
                $tmp[] = $tag->name;
            }
            $tag_names = $tmp;
        }

        return view('note.form', [
            'note' => $note,
            'tag_names' => $tag_names,
            'all_tag_names' => Tag::select('name')->distinct()->get(),
        ]);
    }

    function save(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'tag_names' => 'required',
        ]);
        if($validator->fails()){
            return redirect('note/form/' . $request->get('id'))
                ->withInput()
                ->withErrors($validator);
        }

        $note = Note::find($request->get('id'));
        $type = '수정';
        if(! $note){
            $note = new Note;
            $type = '생성';
        }
        $note->title = $request->get('title');
        $note->url = $request->get('url');
        $note->content = $request->get('content');
        if(!$note->user_id){
            $note->user_id = Auth::user()->id;
        }
        $note->save();

        // 태그
        Tag::whereNoteId($note->id)->delete();
        $tag_names = $request->get('tag_names');
        foreach ($tag_names as $name) {
            $tag = new Tag;
            $tag->note_id = $note->id;
            $tag->name = trim($name);
            $tag->save();
        }

        // 첨부파일
        $sub_dir = date('Y/m');
        if(!Storage::disk('local')->exists($sub_dir)){
            Storage::disk('local')->makeDirectory($sub_dir);
        }

        if($request->file('attachment')){
            $attachment = new Attachment;
            $attachment->filename = $request->file('attachment')->getClientOriginalName();

            $attachment->path = $this->getAttachmentPath($sub_dir);
            $attachment->note_id = $note->id;
            $attachment->user_id = Auth::user()->id;
            Storage::disk('local')->put(
                $attachment->path,
                file_get_contents($request->file('attachment')->getRealPath())
            );
            $attachment->save();
        }

        // 메일 발송
        if($type == '생성'){
            $users = User::where('id', "!=", Auth::user()->id)->get();

            $Parsedown = new \Parsedown();
            $params = [
                'note' => $note,
                'type' => $type,
                'tag_names_string' => implode(', ', $request->get('tag_names')),
                'content' => $Parsedown->text($note->content),
            ];
            Mail::send('note.note-email', $params, function($message) use ($note, $users, $type)
            {
                foreach ($users as $user) {
                    $message->to($user->email, $user->name)->subject("[URL 노트 $type] $note->title");
                };
            });
        }

        return redirect('note/' . $note->id);
    }

    function delete(Note $note) {
        $note->delete();
        return redirect('notes');
    }

    private function getAttachmentPath($dir)
    {
        $store_name = md5(microtime() . Auth::user()->email . rand(0,100));
        $path = $dir . '/' . $store_name;
        $path = $this->avoid_duplication($path);
        return $path;
    }

    public function avoid_duplication($path)
    {
        $i = 0;
        while(Storage::disk('local')->exists($path)){
            $i++;
            $path = $path . '-' . $i;
        }
        return $path;
    }
}
