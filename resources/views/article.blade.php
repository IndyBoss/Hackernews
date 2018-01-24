@extends('layouts.app')

@section('content')

    <?php

    $titleValue = "";
    $urlValue = "";
    $postedByID = Auth::user()->id;

    if($title == 'Edit') {

        $posts = DB::select("SELECT * FROM posts WHERE post_id='$number'");

        foreach ($posts as $row) {
            $titleValue =  $row->title;
            $urlValue = $row->url;
            $postedByID = $row->user_id;
        }
    }

    ?>

    @if ($postedByID == Auth::user()->id)
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    @if($title == 'Edit')
                        @if($delete == 'yes')
                            <div class="bg-danger clearfix">             
                                Are you sure you want to delete this article? 

                                <form action="/home" method="POST" class="pull-right">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="function" value="Delete">
                                    <input type="hidden" name="post_id" value="<?php echo $number; ?>">
                                    <input type="hidden" name="title" value="<?php echo $titleValue; ?>">


                                    <button name="button" class="btn btn-danger" value="delete">
                                        <i class="fa fa-btn fa-trash" title="delete"></i> confirm delete
                                    </button>

                                    <a href="/public/article/edit/<?php echo $number; ?>" class="btn">
                                        <i class="fa fa-btn fa-trash" title="delete"></i> cancel
                                    </a>

                                </form>
                            </div>
                        @endif
                    @endif

                    <div class="breadcrumb">
                        
                        <a href="/">← back to overview</a>

                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading clearfix">
                            <?php echo $title; ?> article
                            @if($title == 'Edit')
                                <a href="/public/article/delete/<?php if($title == 'Edit') {echo $number;}?>" class="btn btn-danger btn-xs pull-right">
                                    <i class="fa fa-btn fa-trash" title="delete"></i> delete article
                                </a>
                            @endif
                        </div>

                        <div class="panel-content">
                            
                            

                            <!-- New Task Form -->
                            <form action="/home" method="POST" class="form-horizontal">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="function" value="<?php echo $title; ?>">
                                <input type="hidden" name="post_id" value="<?php if($title == 'Edit') {echo $number;}?>">


                                <!-- Article data -->
                                <div class="form-group">
                                    <label for="article-title" class="col-sm-3 control-label">Title (max. 255 characters)</label>

                                    <div class="col-sm-6">
                                        <input type="text" name="title" id="article-title" class="form-control" value="<?php if($title == 'Edit') {echo $titleValue;}?>">
                                    </div>
                                </div>

                                
                                <!-- Article url -->
                                <div class="form-group">
                                    <label for="article-url" class="col-sm-3 control-label">URL</label>

                                    <div class="col-sm-6">
                                        <input type="text" name="url" id="article-url" class="form-control" value="<?php if($title == 'Edit') {echo $urlValue;}?>">
                                    </div>
                                </div>


                                <!-- Add Article Button -->
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-6">
                                        <button type="submit" class="btn btn-default">
                                            <?php echo $title;?> Article
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    @else
    <h1>This is not your article.</h>
    @endif
@endsection