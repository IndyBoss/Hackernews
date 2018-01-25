<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/public/home', function () {
    return view('home');
});

Route::get('/public', function () {
    return view('home');
});

Route::get('/public/instructies', function () {
    return view('instructies');
});

Route::get('/public/article/add', function () {
    return view('article-add');
})->middleware('auth');

Route::get('/home', function () {
    return view('home');
});

Route::get('/logout', function () {
    return view('home');
});

Route::get('/', function () {
    return view('home');
});


Route::get('/public/comments/{post}', function($post)
{
    return View::make('comment', array('postID' => $post));
});




Route::group(['middleware' => 'auth'], function () {
    Route::post('/home', function () {
        return view('home');
    });


    // COMMENTS

    Route::post('/public/comments/{post}', function($post)
    {
        return View::make('comment', array('postID' => $post));
    });

    Route::get('/public/comments/edit/{comment}', function($comment)
    {
        return View::make('commentEdit', array('number' => $comment, 'delete' => 'no'));
    });

    Route::get('/public/comments/delete/{comment}', function($comment)
    {
        return View::make('commentEdit', array('number' => $comment, 'delete' => 'yes'));
    });




    // ARTICLE
    Route::get('/public/article/add', function()
    {
        return View::make('article', array('title' => 'Add','link' => '/article/add'));
    });

    Route::get('/public/article/edit/{post}', function($post)
    {
        return View::make('article', array('title' => 'Edit', 'delete' => 'no', 'link' => '/article/edit', 'number' => $post));
    });

    Route::get('/public/article/delete/{post}', function($post)
    {
        return View::make('article', array('title' => 'Edit', 'delete' => 'yes', 'number' => $post));
    });
});

Auth::routes();