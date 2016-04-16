<?php

namespace App\Console\Commands;

use App\Comment;
use App\Note;
use App\Tag;
use Illuminate\Console\Command;

class ImportFromWunderlist extends Command
{
    protected $tag_pattern = '/#([가-힣a-zA-Z0-9_]+)/';
    protected $url_pattern1 = '/(?<url>http(s){0,1}:\/\/[^ \n\(\)]*)/';
    protected $url_pattern2 = '/(?<url>www\.[^ \n\(\)]+)/';
    protected $user_match = [
        // wunderlist_user_id => user_id,
        // user_nickname => user_id,
        // below is example.
        // 12345 => 1,
        // 22211 => 2,
        // 33322 => 3,
        // 'james' => 1,
        // 'ki' => 2,
        // 'slime' => 3
    ];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:wunderlist {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import from Wunderlist backup file.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if(empty($user_match)){
            $this->info('You should fill $user_match array on line 15 in this file(' . __DIR__ . DIRECTORY_SEPARATOR . __FILE__ . ').');
            return false;
        }

        $path = $this->argument('path');
        $this->info("Importing $path...");
        $wunderlist = json_decode(file_get_contents($path));

        $tasks = collect($wunderlist->data->tasks);
        $memos = collect($wunderlist->data->notes);
        $link_items = $tasks->where('list_id', 153635530)->all();

        foreach ($link_items as $item) {
            $parsed_title = $this->parse_title($item->title);
            $parsed_memo = $this->parse_memo($this->get_memo($memos, $item)->content);
            $parsed = $this->merge_with_removing_empty_value($parsed_title, $parsed_memo);

            if(empty($parsed['url'])){
                $this->line("{$item->title}은 URL이 없습니다.");
            }

            $duplication = Note::whereTitle($parsed['title'])->get()->where('url', $parsed['url'])->count();
            if($duplication > 0){
                continue;
            }

            $note = new Note;
            $note->created_at = date('Y-m-d H:i:s', strtotime($item->created_at));
            $note->title = $parsed['title'];
            $note->content = (!empty($parsed['content'])) ? $parsed['content'] : '';
            $note->url = $parsed['url'];
            $note->user_id = $this->user_match[$item->created_by_id];

            $note->save();

            if(!empty($parsed['tags'])){
                foreach ($parsed['tags'] as $tag_name) {
                    $tag = new Tag;
                    $tag->name = $tag_name;
                    $tag->note_id = $note->id;
                    $tag->save();
                }
            }
        }

        $this->save_comments();
    }

    public function parse_title($title){
        $result = [
            'tags' => [],
            'url' => '',
            'title' => '',
        ];

        $result['url'] = $this->extract_first_url($title);
        $result['tags'] = $this->extract_tags($title);
        $result['title'] = $this->extract_title($title, $result['url']);

        if(!in_array(substr($result['url'], 0, 3), ['htt', 'ftp', 'chr'])){
            $result['url'] = 'http://' . $result['url'];
        }

        return $result;
    }

    private function get_memo ($memos, $item) {
        $memo = $memos->filter(function($memo) use ($item) {
            if($memo->task_id != $item->id){
                return false;
            }
            if(empty($memo->content)){
                return false;
            }
            return true;
        })->first();
        return ($memo) ?: (object)['content' => ''];
    }

    public function parse_memo($content) {
        $result = [
            'url' => '',
            'content' => '',
        ];
        $result['url'] = $this->extract_first_url($content);
        $result['content'] = $content;
        return $result;
    }

    private function extract_first_url ($content) {

        preg_match_all($this->url_pattern1, $content, $matches);
        if(!empty($matches['url'])){
            return $matches['url'][0];
        }

        preg_match_all($this->url_pattern2, $content, $matches);
        if(!empty($matches['url'])){
            return $matches['url'][0];
        }

        return '';
    }

    private function extract_tags ($title) {
        preg_match_all($this->tag_pattern, $title, $matches);
        $tags = [];
        if(!empty($matches[1])){
            $tags = $matches[1];
        }
        if(in_array('uuk9letter', $tags)){
            if($key = array_search('uuk9letter', $tags) !== false){
                unset($tags[$key]);
            }
        }
        return $tags;
    }

    private function extract_title ($title, $url) {
        $title = trim(str_replace($url, '', $title));
        if(mb_substr($title, 0, 1) == '#'){
            $title = preg_replace($this->tag_pattern, '', $title);
        }else{
            $title = str_replace('#', '', $title);
        }
        return trim($title);
    }

    private function merge_with_removing_empty_value($parsed_title, $parsed_memo)
    {
        return array_merge(['url' => '', 'content' => ''], array_filter($parsed_title), array_filter($parsed_memo));
    }

    /**
     * 2016-04-16 current, Wunderlist does not support exporting comments.
     * So, I import comments manually. Below is only one example.
     * In use, you extend this code. Reference comments with the code.
     */
    private function save_comments()
    {
        // find note import comment.
        $note = Note::where('title', 'like', '%card sort generator%')->first();

        // if the note has comment, do not import.
        if(!Comment::whereNoteId($note->id)->count()){

            // create comment object and save.
            $comment = new Comment();
            $comment->note_id = $note->id;
            $comment->content = "Wow, great.";
            $comment->user_id = $this->user_match['user_nickname'];
            $comment->created_at = $note->created_at;
            $comment->save();

            $comment = new Comment();
            $comment->note_id = $note->id;
            $comment->content = "Thanks.";
            $comment->user_id = $this->user_match['user_nickname'];
            $comment->created_at = $note->created_at;
            $comment->save();
        }

        // you should repeat above code for import all comments.
    }
}
