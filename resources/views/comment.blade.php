@extends('layouts.app')

@section('content')

    <?php

        $MSG = "";
        $title ="";
        $url ="";
        $postedByID ="";
        $commentCounter ="";
        $name ="";
        $text ="";
        $DEL ="";
        $commentName = "";
        $dateToPost = date('Y-m-d H:i:s');
        $userIdToPost = Auth::user()->id;

        if(isset($_POST['DEL'])) {
            $DEL = $_POST['DEL'];
        }

            $posts = DB::select("SELECT * FROM posts WHERE post_id = '$postID' ");
                foreach ($posts as $post){
                    $title =  $post->title;
                    $url =  $post->url;
                    $postedByID = $post->user_id;
                }


            $users = DB::select("SELECT name FROM users WHERE id = '$postedByID'");
                foreach ($users as $user){
                    $name =  $user->name;
                }


            


            // *********************************************************************************** //
            // ********************************ADD - DELETE - EDIT******************************** //
            // *********************************************************************************** //
            // *********************************************************************************** //

            if (isset($_POST['function'])) {
                if($_POST['function'] =='Add'){
                    $body = $_POST['body'];
                    $bodyReplaced = str_replace("'", "''", $body);
                    try {
                        DB::insert("INSERT INTO comments (comment, post_id, user_id, created_at) VALUES ('$bodyReplaced', '$postID', '$userIdToPost', '$dateToPost')");
                        DB::commit();
                        $MSG = "Comment created succesfully.";
                    } catch (\Exception $e) {
                        DB::rollback();
                        $MSG = "Coment failed creating. " . $e->getMessage() . "";
                    }
                }
                if($_POST['function'] =='Edit'){
                    $bodyToEdit = $_POST['body'];
                    $bodyReplaced = str_replace("'", "''", $bodyToEdit);
                    $ID = $_POST['comment_id'];
                    try {
                        DB::update("UPDATE comments SET comment = '$bodyReplaced' WHERE comment_id = '$ID' ");
                        DB::commit();
                        $MSG = "Comment edited succesfully.";
                    } catch (\Exception $e) {
                        DB::rollback();
                        $MSG = "Comment failed editing. " . $e->getMessage() . "";
                    }
                }
                if($_POST['function'] == 'Delete'){
                    $ID = $_POST['comment_id'];
                    try {
                        DB::delete("DELETE FROM comments WHERE comment_id = '$ID' ");
                        DB::commit();
                        $MSG = "Comment deleted succesfully.";
                    } catch (\Exception $e) {
                        DB::rollback();
                        $MSG = "Comment failed deleting. " . $e->getMessage() . "";
                    }
                }
            }

            // *********************************************************************************** //
            // ***********************************COMMENT COUNT*********************************** //
            // *********************************************************************************** //
            // *********************************************************************************** //


                $commentsCounters = DB::select("SELECT COUNT(comment_id) as count FROM comments WHERE post_id = '$postID'");
                    foreach ($commentsCounters as $commentCounter) {
                        $commentCounter = $commentCounter->count;
                    }

    ?>

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                @if($DEL == 'pushed')

                   <div class="bg-danger clearfix">             
                        Are you sure you want to delete this comment? 

                        <form action="/public/comments/<?php echo $postID; ?>" method="POST" class="pull-right">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="function" value="Delete">
                            <input type="hidden" name="comment_id" value="<?php echo $_POST['ID']; ?>">


                            <button name="button" class="btn btn-danger" value="delete">
                                <i class="fa fa-btn fa-trash" title="delete"></i> confirm delete
                            </button>

                            <a href="/public/comments/<?php echo $postID; ?>" class="btn">
                                <i class="fa fa-btn fa-trash" title="delete"></i> cancel
                            </a>

                        </form>
                    </div>
                @endif

            <?php if($MSG !== "") {echo "<div class='bg-success'>" .$MSG. "</div>";} ?>

                <div class="breadcrumb">
                    
                    <a href="/">← back to overview</a>

                </div>
                
                <div class="panel panel-default">
                    <div class="panel-heading clearfix"><?php echo $title ?></div>
                        <div class="panel-content">

                            <div class="vote">                   
                                <form action="/public/vote/up" method="POST" class="form-inline upvote">
                                    <input type="hidden" name="_token" value="qF7UhzckMPKOUI22oV5f767oTXIXdfLCLCtqmhJR">

                                    <button name="article_id" value="1">
                                        <i class="fa fa-btn fa-caret-up" title="upvote"></i>
                                    </button>

                                </form>
                                        
                                <form action="/public/vote/down" method="POST" class="form-inline downvote">
                                    <input type="hidden" name="_token" value="qF7UhzckMPKOUI22oV5f767oTXIXdfLCLCtqmhJR">

                                    <button name="article_id" value="1">
                                        <i class="fa fa-btn fa-caret-down" title="downvote"></i>
                                    </button>

                                </form>   
                            </div>
                                
                            <div class="url">
                                <a href="<?php echo $url ?>" class="urlTitle"><?php echo $title ?></a>
                            </div> 
                                
                                
                            <div class="info">
                                2 points  | posted by <?php echo $name ?> | <?php echo $commentCounter ?> comments
                            </div>

                            <div class="comments">
                                <ul>
                                    <?php

                                        $comments = DB::select("SELECT * FROM comments WHERE post_id = '$postID' "); ?>

                                        @foreach ( $comments as $comment)
                                            <?php $text =  $comment->comment;
                                            $date =  $comment->created_at;
                                            $userID = $comment->user_id;
                                            $commentID = $comment->comment_id;

                                                $usersName = DB::select("SELECT name FROM users WHERE id = '$userID'");
                                                    foreach ($usersName as $userName) {
                                                        $commentName = $userName->name;
                                                    }
                                            ?>


                                            <li><div class='comment-body'><?php echo $text ?></div>
                                                <div class='comment-info'>
                                                    Posted by <?php echo $commentName ?> on <?php echo $date ?>.
                                                
                                                    @if($userID == Auth::user()->id) 
                                                        <a href='/public/comments/edit/<?php echo $commentID ?>' class='btn btn-primary btn-xs edit-btn'>edit</a>

                                                        <form action='/public/comments/<?php echo $postID ?>' method='POST' class='btn btn-danger btn-xs edit-btn'>
                                                            <input type='hidden' name='_token' value='{{ csrf_token() }}'>
                                                            <input type='hidden' name='DEL' value='pushed'>
                                                            <input type='hidden' name='ID' value='<?php echo $commentID ?>'>
                                                            <button name='button' value='delete'>
                                                                <i class='fa fa-btn fa-trash' title='delete'></i> delete
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach  
                                </ul>
                            </div>

                                
                                
                                <!-- New Task Form -->
                            <form action="/public/comments/<?php echo $postID ?>" method="POST" class="form-horizontal">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="function" value="Add">

                                <!-- Comment data -->
                                <div class="form-group">
                                    <label for="body" class="col-sm-3 control-label">Comment</label>

                                    <div class="col-sm-6">
                                        <textarea type="text" name="body" id="body" class="form-control"></textarea>
                                    </div>
                                </div>

                                <!-- Add comment -->
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-6">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fa fa-plus"></i> Add comment
                                        </button>
                                    </div>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>
@endsection