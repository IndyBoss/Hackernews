@extends('layouts.app')

@section('content')

    <?php

    $MSG = "";
    $commentCounter = "";
    $postID ="";
    $voteCounter = "";
    $number ="";

    if (null !== Auth::user()) {
        $postedById = Auth::user()->id;
    }

    if (isset($_POST['function'])) {
        if($_POST['function'] =='Add'){
            $title = $_POST['title'];
            $url = $_POST['url'];
            $dateToPost = date('Y-m-d H:i:s');
            try {
                DB::insert("INSERT INTO posts (title, url, user_id, created_at) VALUES ('$title', '$url', '$postedById', '$dateToPost')");
                DB::commit();
                $MSG = "Article" . '"' . $title . '"' . "created succesfully.";
            } catch (\Exception $e) {
                DB::rollback();
                $MSG = "Article" . '"' . $title . '"' . "failed creating. " . $e->getMessage() . "";
            }
        }
        if($_POST['function'] =='Edit'){
            $title = $_POST['title'];
            $url = $_POST['url'];
            $postID = $_POST['post_id'];
            try {
                DB::update("UPDATE posts SET title = '$title', url = '$url' WHERE post_id = '$postID' ");
                DB::commit();
                $MSG = "Article" . '"' . $title . '"' . "edited succesfully.";
            } catch (\Exception $e) {
                DB::rollback();
                $MSG = "Article" . '"' . $title . '"' . "failed editing. " . $e->getMessage() . "";
            }
        }
        if($_POST['function'] == 'Delete'){
            $postID = $_POST['post_id'];
            $button = $_POST['button'];
            $title = $_POST['title'];
            $dateToPost = date('Y-m-d H:i:s');
            if($button == 'delete') {
                try {
                    DB::update("UPDATE posts SET deleted_at = '$dateToPost' WHERE post_id = '$postID' ");
                    DB::commit();
                    $MSG = "Article" . '"' . $title . '"' . "deleted succesfully.";
                } catch (\Exception $e) {
                    DB::rollback();
                    $MSG = "Article" . '"' . $title . '"' . "failed deleting. " . $e->getMessage() . "";
                }
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


            $results = DB::select("SELECT COUNT(vote) as count FROM votes WHERE post_id= '$postID' AND user_id = '$postedById' ");
                foreach($results as $result){
                    $r = $result->count;
                    if($r == 0){
                        try {
                            DB::insert("INSERT INTO votes (vote, user_id, post_id) VALUES ('$vote', '$postedById', '$postID')");
                            DB::commit();
                            $MSG = "Voted succesfully.";
                        } catch (\Exception $e) {
                            DB::rollback();
                            $MSG = "Voting failed. " . $e->getMessage() . "";
                        }
                    }
                    else {
                        try {
                            DB::update("UPDATE votes SET vote = '$vote' WHERE post_id = '$postID' AND user_id = '$postedById' ");
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
    ?>



    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

            <?php if($MSG !== "") {echo "<div class='bg-success'>" .$MSG. "</div>";} ?>

                <div class="panel panel-default">
                    <div class="panel-heading">Article overview</div>

                    <div class="panel-content">
                                    
                        <ul class="article-overview">

                    <?php 
                    $postResults = DB::select('SELECT * FROM posts WHERE deleted_at IS NULL ORDER BY created_at');
                    foreach($postResults as $postResult) {
                        $voteCounter = $postResult->points;
                        $postedID = $postResult->post_id;
                        $voted = "";

                            if(null !== Auth::user()) {
                                $userVotes = DB::select("SELECT vote, user_id FROM votes WHERE post_id = '$postedID'");
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

                        $comments = DB::select("SELECT COUNT(comment_id) as count FROM comments WHERE post_id = '$postResult->post_id' AND deleted_at IS NULL");
                        foreach ($comments as $comment) {
                            $commentCounter = $comment->count;
                        }

                        $users =  DB::select("SELECT * FROM users WHERE id='$postResult->user_id'");

                        foreach ( $users as $user){
                            $postedBy =  $user->name;
                            $postedById = $user->id;
                        }
                        ?>

                            @if(null !== Auth::user())
                                <li>
                                <form action='/public/home' method='POST' class='vote'>
                                    <input type='hidden' name='_token' value='{{ csrf_token() }}'>
                                    <input type='hidden' name='function' value='Vote'>
                                    <input type='hidden' name='post_id' value="<?php echo $postedID; ?>">
                        
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
                                <li>

                                <div class='vote'>
                        
                                    <div class='form-inline upvote'>

                                        <i class='fa fa-btn fa-caret-up disabled upvote' title='You need to be logged in to upvote'></i>
                                    
                                    </div>
                
                                    <div class='form-inline upvote'>

                                        <i class='fa fa-btn fa-caret-down disabled downvote' title='You need to be logged in to downvote'></i>
                                    
                                    </div>

                                </div>
                            @endif
                            
                            <?php
                            echo "<div class='url'>
                                
                                <a href='$postResult->url' class='urlTitle'> $postResult->title</a>";
                                if (null !== Auth::user()) {
                                    if ($postedById == Auth::user()->id){
                                    echo "<a href='/public/article/edit/$postResult->post_id' class='btn btn-primary btn-xs edit-btn'>edit</a>";
                                    }
                                }
                                

                            echo "</div> 
                            
                            
                            <div class='info'>
                                $voteCounter points  | posted by $postedBy | <a href='/public/comments/$postResult->post_id'>$commentCounter comments</a>
                            </div></li>";
                    }
                    ?>

                        </ul>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection