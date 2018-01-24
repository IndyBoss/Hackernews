@extends('layouts.app')

@section('content')

    <?php

    $MSG = "";
    $commentCounter = "";
    $postID ="";

    if (null !== Auth::user()) {
        $postedById = Auth::user()->id;
    }

    if (isset($_POST['function'])) {
        if($_POST['function'] =='Add'){
            $title = $_POST['title'];
            $url = $_POST['url'];
            try {
                DB::insert("INSERT INTO posts (title, url, user_id) VALUES ('$title', '$url', '$postedById')");
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
            if($button == 'delete') {
                try {
                    DB::delete("DELETE FROM posts WHERE post_id = '$postID' ");
                    DB::commit();
                    $MSG = "Article" . '"' . $title . '"' . "deleted succesfully.";
                } catch (\Exception $e) {
                    DB::rollback();
                    $MSG = "Article" . '"' . $title . '"' . "failed deleting. " . $e->getMessage() . "";
                }
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
                    $postResults = DB::select('SELECT * FROM posts ORDER BY title');

                    foreach($postResults as $postResult) {
                        $a = array();
                        array_push($a, $postResult->post_id, $postResult->title, $postResult->url, $postResult->user_id);
                        $postID = $postResult->post_id;

                        $comments = DB::select("SELECT COUNT(comment_id) as count FROM comments WHERE post_id = '$postID'");
                        foreach ($comments as $comment) {
                            $commentCounter = $comment->count;
                        } 

                        $users =  DB::select('SELECT * FROM users WHERE id=' . $postResult->user_id);

                        foreach ( $users as $user){
                            $postedBy =  $user->name;
                            $postedById = $user->id;
                        }

                        
                        echo "<li>

                            <div class='vote'>
                    
                                <div class='form-inline upvote'>

                                    <i class='fa fa-btn fa-caret-up disabled upvote' title='You need to be logged in to upvote'></i>
                                
                                </div>
            
                                <div class='form-inline upvote'>

                                    <i class='fa fa-btn fa-caret-down disabled downvote' title='You need to be logged in to downvote'></i>
                                
                                </div>

                            </div>
                        
                            <div class='url'>
                                
                                <a href='$postResult->url' class='urlTitle'> $postResult->title</a>";
                                if (null !== Auth::user()) {
                                    if ($postedById == Auth::user()->id){
                                    echo "<a href='/public/article/edit/$postID' class='btn btn-primary btn-xs edit-btn'>edit</a>";
                                    }
                                }
                                

                            echo "</div> 
                            
                            
                            <div class='info'>
                                2 points  | posted by $postedBy | <a href='/public/comments/$postID'>$commentCounter comments</a>
                            </div>
                        
                        </li>";
                        }
                    ?>

                        </ul>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection