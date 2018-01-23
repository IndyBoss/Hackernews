@extends('layouts.app')

@section('content')

<?php

    session_start();

    $conn = new PDO( 'mysql:host=localhost;dbname=hackernews', 'Indy', 'Indy' );

    $sql = 'SELECT * FROM posts';

?>



<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            
            <div class="panel panel-default">
                <div class="panel-heading">Article overview</div>

                <div class="panel-content">
                                
                    <ul class="article-overview">

                <?php foreach ( $conn->query($sql) as $post): 
                
                $usersql = 'SELECT * FROM users WHERE id=' . $post['post_id'];

                    foreach ( $conn->query($usersql) as $user):
                        $postedBy =  $user['name'];
                        $postedById = $user['id'];
                    endforeach;

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