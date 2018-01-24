@extends('layouts.app')

@section('content')

    <?php

    $body = "";
    $postId = "";
    $postedByID = Auth::user()->id;

        $comments = DB::select("SELECT * FROM comments WHERE comment_id='$number'");
            foreach ($comments as $comment) {
                $body =  $comment->comment;
                $postedByID = $comment->user_id;
                $postId = $comment->post_id;
            } 

    ?>

    @if ($postedByID == Auth::user()->id)
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                        @if($delete == 'yes')
                            <div class="bg-danger clearfix">             
                                Are you sure you want to delete this comment? 

                                <form action="/public/comments/<?php echo $postId; ?>" method="POST" class="pull-right">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="function" value="Delete">
                                    <input type="hidden" name="comment_id" value="<?php echo $number; ?>">


                                    <button name="button" class="btn btn-danger" value="delete">
                                        <i class="fa fa-btn fa-trash" title="delete"></i> confirm delete
                                    </button>

                                    <a href="/public/comments/edit/<?php echo $number; ?>" class="btn">
                                        <i class="fa fa-btn fa-trash" title="delete"></i> cancel
                                    </a>

                                </form>
                            </div>
                        @endif

                    <div class="breadcrumb">
                        
                        <a href="/public/comments/<?php echo $postId; ?>">← back to overview</a>

                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading clearfix">
                                <a href="/public/comments/delete/<?php echo $number;?>" class="btn btn-danger btn-xs pull-right">
                                    <i class="fa fa-btn fa-trash" title="delete"></i> delete comment
                                </a>
                        </div>

                        <div class="panel-content">
                            
                            

                            <!-- New Task Form -->
                            <form action="/public/comments/<?php echo $postId ?>" method="POST" class="form-horizontal">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="comment_id" value="<?php echo $number ?>">
                                <input type="hidden" name="function" value="Edit">

                                <!-- Article data -->
                                <div class="form-group">
                                    <label for="body" class="col-sm-3 control-label">Comment</label>

                                    <div class="col-sm-6">
                                        <textarea type="text" name="body" id="body" class="form-control"><?php echo $body ?></textarea>
                                    </div>
                                </div>

                                <!-- Add Article Button -->
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-6">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fa fa-pencil-square-o"></i> edit comment
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
    <h1>This is not your comment.</h>
    @endif
@endsection