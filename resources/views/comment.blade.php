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

        if(null !== Auth::user()) {
            $userIdToPost = Auth::user()->id;
        }

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
                    try {
                        DB::insert("INSERT INTO comments (comment, post_id, user_id, created_at) VALUES ('$body', '$postID', '$userIdToPost', '$dateToPost')");
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
                    $dateToPost = date('Y-m-d H:i:s');
                    try {
                        DB::update("UPDATE comments SET deleted_at = '$dateToPost' WHERE comment_id = '$ID' ");
                        DB::commit();
                        $MSG = "Comment deleted succesfully.";
                    } catch (\Exception $e) {
                        DB::rollback();
                        $MSG = "Comment failed deleting. " . $e->getMessage() . "";
                    }
                }
                if($_POST['function'] == 'Vote'){
                    $vote = 0;
                    $postID = $_POST['post_id'];
                    $pointsToPost = 0;
                    $postPoints = 0;
                    
                    if($_POST['vote'] == 'up'){
                        $vote = 1;
                    }
                    else {
                        $vote = 0-1;
                    }
        
        
                    $results = DB::select("SELECT COUNT(vote) as count FROM votes WHERE post_id= '$postID' AND user_id = '$userIdToPost' ");
                        foreach($results as $result){
                            $r = $result->count;
                            if($r == 0){
                                try {
                                    DB::insert("INSERT INTO votes (vote, user_id, post_id) VALUES ('$vote', '$userIdToPost', '$postID')");
                                    DB::commit();
                                    $MSG = "Voted succesfully.";
                                } catch (\Exception $e) {
                                    DB::rollback();
                                    $MSG = "Voting failed. " . $e->getMessage() . "";
                                }
                            }
                            else {
                                try {
                                    DB::update("UPDATE votes SET vote = '$vote' WHERE post_id = '$postID' AND user_id = '$userIdToPost' ");
                                    DB::commit();
                                    $MSG = "Vote updated succesfully.";
                                } catch (\Exception $e) {
                                    DB::rollback();
                                    $MSG = "Vote failed updating. " . $e->getMessage() . "";
                                }
                            }
                            $points = DB::select("SELECT SUM(vote) as sum FROM votes WHERE post_id= '$postID' ");
                                foreach($points as $point) {
                                    $postPoints = $point->sum;
                                    $pointsToPost = $postPoints;
                                }
                            DB::update("UPDATE posts SET points = '$pointsToPost' WHERE post_id = '$postID' ");
                            DB::commit();
                        }
                }
            }

            // *********************************************************************************** //
            // ***********************************COMMENT COUNT*********************************** //
            // *********************************************************************************** //
            // *********************************************************************************** //


                $commentsCounters = DB::select("SELECT COUNT(comment_id) as count FROM comments WHERE post_id = '$postID' AND deleted_at IS NULL");
                    foreach ($commentsCounters as $commentCounter) {
                        $commentCounter = $commentCounter->count;
                    }


                            if(null !== Auth::user()) {
                                $userVotes = DB::select("SELECT vote, user_id FROM votes WHERE post_id = '$postID'");
                                foreach ($userVotes as $userVote) {
                                    $votedUser = $userVote->vote;
                                    $userID = $userVote->user_id;
                                    
                                    if($votedUser != 0) {
                                        if ($userID == Auth::user()->id){
                                            if($votedUser == 1) {
                                                $voted = 'up';
                                            }
                                            elseif($votedUser == -1) {
                                                $voted = 'down';
                                            }
                                            else {$voted = '';}
                                        }
                                        else {$voted = '';}
                                    }
                                }
                            }


             $postResults = DB::select("SELECT SUM(points) as p FROM posts WHERE post_id = '$postID' AND deleted_at IS NULL");
                foreach($postResults as $postResult) {
                    $voteCounter = $postResult->p;
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
                    
                    <a href="/home">← back to overview</a>

                </div>
                
                <div class="panel panel-default">
                    <div class="panel-heading clearfix"><?php echo $title ?></div>
                        <div class="panel-content">

                                @if(null !== Auth::user())
                                    <form action='/public/comments/<?php echo $postID ?>' method='POST' class='vote'>
                                        <input type='hidden' name='_token' value='{{ csrf_token() }}'>
                                        <input type='hidden' name='function' value='Vote'>
                                        <input type='hidden' name='post_id' value="<?php echo $postID; ?>">
                            
                                        @if($voted == 'up')
                                            <i class='fa fa-btn fa-caret-up disabled upvote' title='You can only upvote once'></i>
                                        @else
                                            <button name='vote' class='form-inline upvote' value='up'>
                                                <i class='fa fa-btn fa-caret-up upvote' title='upvote'></i>
                                            </button>
                                        @endif

                                        @if($voted == 'down')
                                            <i class='fa fa-btn fa-caret-down disabled downvote' title='You can only downvote once'></i>
                                        @else
                                            <button name='vote' class='form-inline downvote' value='down'>
                                                <i class='fa fa-btn fa-caret-down downvote' title='downvote'></i>
                                            </button>
                                        @endif

                                    </form>
                                
                                    @else
                                        <div class='vote'>
                                
                                            <div class='form-inline upvote'>

                                                <i class='fa fa-btn fa-caret-up disabled upvote' title='You need to be logged in to upvote'></i>
                                            
                                            </div>
                        
                                            <div class='form-inline upvote'>

                                                <i class='fa fa-btn fa-caret-down disabled downvote' title='You need to be logged in to downvote'></i>
                                            
                                            </div>

                                        </div>
                                    @endif

                                
                            <div class="url">
                                <a href="<?php echo $url ?>" class="urlTitle"><?php echo $title ?></a>
                            </div> 
                                
                                
                            <div class="info">
                                <?php echo $voteCounter ?> points  | posted by <?php echo $name ?> | <?php echo $commentCounter ?> comments
                            </div>

                            <div class="comments">
                                <ul>
                                    <?php

                                        $comments = DB::select("SELECT * FROM comments WHERE post_id = '$postID' AND deleted_at IS NULL"); ?>

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
                                                    @if (null !== Auth::user())
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
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach  
                                </ul>
                            </div>

                                
                            @if (null !== Auth::user())
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
                            @else
                                <div>   
                                    <p>You need to be <a href="/login">logged in</a> to comment</p>
                                </div>
                            @endif
                            
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>
@endsection