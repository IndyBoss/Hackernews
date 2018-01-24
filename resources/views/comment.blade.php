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
$commentName = "";
$dateToPost = date('Y-m-d H:i:s');
$userIdToPost = Auth::user()->id;


$conn = new PDO( 'mysql:host=localhost;dbname=hackernews', 'Indy', 'Indy' );

    $postSql = "SELECT * FROM posts WHERE post_id = '$postID' ";
    foreach ( $conn->query($postSql) as $post){
        $title =  $post['title'];
        $url =  $post['url'];
        $postedByID = $post['user_id'];
    }

    $usersSql = "SELECT name FROM users WHERE id = '$postedByID'";
        $result = $conn->prepare($usersSql); 
        $result->execute(); 
        $name = $result->fetchColumn();


    


    // *********************************************************************************** //
    // ********************************ADD - DELETE - EDIT******************************** //
    // *********************************************************************************** //
    // *********************************************************************************** //

    if (isset($_POST['function'])) {
        if($_POST['function'] =='Add'){
            $body = $_POST['body'];
            try 
            {
                $conn = new PDO( 'mysql:host=localhost;dbname=hackernews', 'Indy', 'Indy' );
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "INSERT INTO comments (comment, post_id, user_id, created_at) VALUES ('$body', '$postID', '$userIdToPost', '$dateToPost')";
                $conn->exec($sql);
                $MSG = "Comment created succesfully.";
            }
            catch(PDOException $e)
            {
                $MSG = "Coment failed creating. " . $e->getMessage() . "";
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

    // *********************************************************************************** //
    // ***********************************COMMENT COUNT*********************************** //
    // *********************************************************************************** //
    // *********************************************************************************** //

    $conn = new PDO( 'mysql:host=localhost;dbname=hackernews', 'Indy', 'Indy' );
    $commentCountSql = "SELECT COUNT(comment_id) FROM comments WHERE post_id = '$postID'";
        $result = $conn->prepare($commentCountSql); 
        $result->execute(); 
        $commentCounter = $result->fetchColumn();

?>

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

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

                                        $conn = new PDO( 'mysql:host=localhost;dbname=hackernews', 'Indy', 'Indy' );
                                        $commentsSql = "SELECT * FROM comments WHERE post_id = '$postID' ";

                                        foreach ( $conn->query($commentsSql) as $comment){
                                            $text =  $comment['comment'];
                                            $date =  $comment['created_at'];
                                            $userID = $comment['user_id'];
                                            $commentID = $comment['comment_id'];

                                                $usersSql = "SELECT name FROM users WHERE id = '$userID'";
                                                $result = $conn->prepare($usersSql); 
                                                $result->execute(); 
                                                $commentName = $result->fetchColumn();


                                            echo "<li><div class='comment-body'>$text</div>
                                            <div class='comment-info'>
                                                Posted by $commentName on $date.";
                                            
                                                if ($userID == Auth::user()->id) {
                                                    echo "<a href='/public/comments/edit/$commentID' class='btn btn-primary btn-xs edit-btn'>edit</a>

                                                    <a href='/public/comments/delete/$commentID' class='btn btn-danger btn-xs edit-btn'>
                                                        <i class='fa fa-btn fa-trash' title='delete'></i> delete 
                                                    </a>";
                                                }
                                            echo "</div>
                                        </li> ";

                                        }

                                    ?>       
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