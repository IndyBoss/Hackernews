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
        try 
        {
            $conn = new PDO( 'mysql:host=localhost;dbname=hackernews', 'Indy', 'Indy' );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO posts (title, url, user_id)
        VALUES ('$title', '$url', '$postedById')";
            $conn->exec($sql);
            $MSG = "Article" . '"' . $title . '"' . "created succesfully.";
        }
        catch(PDOException $e)
        {
            $MSG = "Article" . '"' . $title . '"' . "failed creating. " . $e->getMessage() . "";
        }

        $conn = null;
    }
    if($_POST['function'] =='Edit'){
        $title = $_POST['title'];
        $url = $_POST['url'];
        $postID = $_POST['post_id'];
        try 
        {
            $conn = new PDO( 'mysql:host=localhost;dbname=hackernews', 'Indy', 'Indy' );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE posts SET title = '$title', url = '$url' WHERE post_id = '$postID' ";
            $conn->exec($sql);
            $MSG = "Article" . '"' . $title . '"' . "edited succesfully.";
        }
        catch(PDOException $e)
        {
            $MSG = "Article" . '"' . $title . '"' . "failed editing. " . $e->getMessage() . "";
        }

        $conn = null;
    }
    if($_POST['function'] == 'Delete'){
        $postID = $_POST['post_id'];
        $button = $_POST['button'];
        $title = $_POST['title'];
        if($button == 'delete') {
            try 
            {
                $conn = new PDO( 'mysql:host=localhost;dbname=hackernews', 'Indy', 'Indy' );
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "DELETE FROM posts WHERE post_id = '$postID' ";
                $conn->exec($sql);
                $MSG = "Article" . '"' . $title . '"' . "deleted succesfully.";
            }
            catch(PDOException $e)
            {
                $MSG = "Article" . '"' . $title . '"' . "failed deleting. " . $e->getMessage() . "";
            }
    
            $conn = null;
        }
    }
}

    session_start();
    $conn = new PDO( 'mysql:host=localhost;dbname=hackernews', 'Indy', 'Indy' );
    $sql = 'SELECT * FROM posts ORDER BY title';
?>



<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

        <?php if($MSG !== "") {echo "<div class='bg-success'>" .$MSG. "</div>";} ?>

            <div class="panel panel-default">
                <div class="panel-heading">Article overview</div>

                <div class="panel-content">
                                
                    <ul class="article-overview">

                <?php foreach ( $conn->query($sql) as $post): 
                    $postID = $post['post_id'];

                $commentsql = "SELECT COUNT(comment_id) FROM comments WHERE post_id = '$postID'";
                    $result = $conn->prepare($commentsql); 
                    $result->execute(); 
                    $commentCounter = $result->fetchColumn(); 

                $usersql = 'SELECT * FROM users WHERE id=' . $post['user_id'];

                    foreach ( $conn->query($usersql) as $user){
                        $postedBy =  $user['name'];
                        $postedById = $user['id'];
                    }

                ?>
                    <li>

                        <div class="vote">
                
                            <div class="form-inline upvote">

                                <i class="fa fa-btn fa-caret-up disabled upvote" title="You need to be logged in to upvote"></i>
                            
                            </div>
        
                            <div class="form-inline upvote">

                                <i class="fa fa-btn fa-caret-down disabled downvote" title="You need to be logged in to downvote"></i>
                            
                            </div>

                        </div>
                    
                        <div class="url">
                            
                            <a href="<?= $post['url'] ?>" class="urlTitle"><?= $post['title'] ?></a>
                            @if (null !== Auth::user())
                                @if ($postedById == Auth::user()->id)
                                <a href="/public/article/edit/<?php echo $postID;?>" class="btn btn-primary btn-xs edit-btn">edit</a>
                                @endif
                            @endif

                        </div> 
                        
                        
                        <div class="info">
                            2 points  | posted by <?php echo $postedBy ?> | <a href="/public/comments/<?php echo $postID;?>"><?php echo $commentCounter;?> comment</a>
                        </div>
                    
                    </li>
                <?php endforeach;?>

                    </ul>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection