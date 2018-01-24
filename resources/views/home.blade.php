@extends('layouts.app')

@section('content')

<?php $postedById = 0; ?>

@if($postedById = Auth::user()->id)
@endif




<?php

$MSG = null;

if (isset($_POST['function'])) {
    $title = $_POST['title'];
    $url = $_POST['url'];
    if($_POST['function']=='Add'){
        try 
        {
            $conn = new PDO( 'mysql:host=localhost;dbname=hackernews', 'Indy', 'Indy' );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO posts (title, url, user_id)
        VALUES ('$title', '$url', '$postedById')";
            $conn->exec($sql);
            $MSG = "article" . '"' . $title . '"' . "created succesfully.";
        }
        catch(PDOException $e)
        {
            $MSG = "article" . '"' . $title . '"' . "failed creating. " . $e->getMessage() . "";
        }

        $conn = null;
    }
    if($_POST['function']=='Edit'){
        try 
        {
            $conn = new PDO( 'mysql:host=localhost;dbname=hackernews', 'Indy', 'Indy' );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE posts SET title = '$title', url = '$url' WHERE title = '$title' ";
            $conn->exec($sql);
            $MSG = "article" . '"' . $title . '"' . "edited succesfully.";
        }
        catch(PDOException $e)
        {
            $MSG = "article" . '"' . $title . '"' . "failed editing. " . $e->getMessage() . "";
        }

        $conn = null;
    }
}

    session_start();
    $conn = new PDO( 'mysql:host=localhost;dbname=hackernews', 'Indy', 'Indy' );
    $sql = 'SELECT * FROM posts';
?>



<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

        <div class='bg-success'><?php echo "".(null !== $MSG ?   $MSG : "") ?></div>

            <div class="panel panel-default">
                <div class="panel-heading">Article overview</div>

                <div class="panel-content">
                                
                    <ul class="article-overview">

                <?php foreach ( $conn->query($sql) as $post): 
                
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
                                <a href="public/article/edit/<?php echo $post['post_id'];?>" class="btn btn-primary btn-xs edit-btn">edit</a>
                                @endif
                            @endif

                        </div> 
                        
                        
                        <div class="info">
                            2 points  | posted by <?php echo $postedBy ?> | <a href="comments/2">1 comment</a>
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