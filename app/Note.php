<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Note
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $url
 * @property integer $user_id
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Note whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Note whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Note whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Note whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Note whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Note whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Note whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Note whereUpdatedAt($value)
 */
class Note extends Model
{
    public function self_url(){
        return url('note/' . $this->id);
    }

    /**
     * $note = Note::find(1);
     * echo $note->user->name;
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * $note = Note::find(1);
     * $comments = $note->comments()->get();
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function tags()
    {
        return $this->hasMany('App\Tag');
    }

    public function attachments()
    {
        return $this->hasMany('App\Attachment');
    }
}
