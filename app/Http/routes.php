<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
use App\Attachment;

Route::group(['middleware' => ['web']], function () {
    Route::auth();

    Route::get('/', function () {
        return redirect('/notes');
    });

    Route::group(['middleware' => ['auth']], function () {
        Route::get('/tag/{tag_name}', 'NoteController@notesByTag');
        Route::get('/tags', 'NoteController@tagList');

        Route::get('/notes/{page?}', 'NoteController@notes');
        Route::get('/note/form/{note?}', 'NoteController@form');
        Route::get('/note/delete/{note}', 'NoteController@delete');
        Route::get('/note/{note}', 'NoteController@note');

        Route::post('/note', 'NoteController@save');
        Route::put('/note', 'NoteController@save');

        Route::get('/comments/{page?}', function ($page) {});
        Route::post('/comment', 'CommentController@save');

        Route::post('/api/note', function () {});

        Route::get('/scriptlet', function(){
            return view('note.scriptlet');
        });
        Route::get('/attachment/{id}', function($id){
            $attachment = Attachment::find($id);
            $path = config('filesystems.disks.local.root') . DIRECTORY_SEPARATOR . $attachment->path;
            return response()->download($path, $attachment->filename);
        });
    });
});
