<?php
/**
 * Created by PhpStorm.
 * User: Ashiqur Rahman
 * Date: 25-Dec-16
 * Time: 7:18 PM
 */
ob_start();
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
}

$uid = $_SESSION['user_id'];
$query_user_id = $uid;
include 'backend/database_connection.php';
include 'backend/database_profile_details.php';
include 'backend/database_posts.php';

?>



<script type="text/javascript">

    function showDiv(divName) {
        if(document.getElementById(divName).style.display != "block"){
            document.getElementById(divName).style.display = "block";

            if(divName != 'top-stories'){
                document.getElementById('top-stories').style.display = "none";
            }
            if(divName != 'comm-posts'){
                document.getElementById('comm-posts').style.display = "none";
            }
            if(divName != 'frnd-posts'){
                document.getElementById('frnd-posts').style.display = "none";
            }
        }
    }

</script>

<!DOCTYPE HTML>
<html>
<head>
    <title>Timeline</title>
    <link rel="stylesheet" href="sceleton-workspace.css" type="text/css">
</head>

<body>
<div class="container">

    <div class="header-container">

        <header class="header">
            <div class="branding">
                <div class="brand-logo"><a href="index.php"><img src="images/logoFinal.png"></a></div>
                <h1 class="brand-name">Cyber Society</h1>
            </div>

            <div class="pro-pic-div">
                <?php
                if($allFromProfileRow['pro_pic_id'] == null){
                    echo '<img src="images/default_pro_pic.png">';
                }
                else{
                    echo '<img src="pictures/'.$allFromProfileRow['pro_pic_id'].'_pro_pic.jpg">';
                }
                ?>
            </div>
        </header>

        <nav class="nav" id="nav">
            <?php
            include 'backend/top_nav.php';
            ?>
        </nav>

    </div>

    <section class="content_area">
        <div class="banner">

            <div class="col col-1">

                <div class="left-nav" >

                    <ul>
                        <li><a href="javascript:showDiv('top-stories')">Top Stories</a></li>
                        <li><a href="javascript:showDiv('frnd-posts')">Friend's Posts</a></li>
                        <li><a href="javascript:showDiv('comm-posts')">Community Posts</a></li>
                    </ul>

                </div>

                <div class="content-panel">

                    <div id="top-stories" class="top-stories" style="display:block">
                        <!---All posts--->
                        <h3>Top Stories</h3>

                        <div class="post">
                            <?php

                            while($post_details = $all_post->fetch_assoc()) {

                                //echo '<div class="individual-post">';



                                $name = $post_details['first_name'].' '.$post_details['last_name'];

                                echo '<div class="post-owner">';
                                echo $name;
                                echo '</div>';

                                echo '<div class="post-date-time">';
                                echo $post_details['postdate_time'];
                                echo '</div>';

                                echo '<div class="post-text">';
                                echo $post_details['post'];
                                echo '</div>';

                                $pic_id = $post_details['pic_id'];
                                $pic_name=$pic_id.'.jpg';

                                $target = 'pictures/'.$pic_name;
                                if (file_exists($target)) {
                                    echo '<img src="'.$target. '"/>';
                                }

                                //for comment.like .dislike..
                                $post_id = $post_details['post_id'];
                                $poster_id = $post_details['user_id'];

                                $sql = "SELECT thumb_type FROM thumbs WHERE (post_id = $post_id) AND (user_id = $uid) ";
                                $result = $connection->query($sql);
                                $type_row = $result->fetch_assoc();
                                $type = $type_row['thumb_type'];



                                if(mysqli_num_rows($result) == 0)
                                {

                                    echo '<form action="" method="POST">';
                                    echo '<div class="post-thumb-left">';
                                    echo '<button type="submit" class="button" name="like" value="'.$post_id.'"><img src="images/up_neutral.png"></button>';
                                    echo '</div>';
                                    echo '<div class="post-thumb-right">';
                                    echo '<button type="submit" class="button" name="dislike" value="'.$post_id.'"><img src="images/down_neutral.png"></button>';
                                    echo '</div>';
                                    echo '</form>';

                                }
                                else{

                                    if($type == 1){
                                        echo '<form action="" method="POST">';
                                        echo '<div class="post-thumb-left">';
                                        echo '<button type="submit" class="button" name="liked" value="'.$post_id.'"><img src="images/up_true.png"></button>';
                                        echo '</div>';
                                        echo '<div class="post-thumb-right">';
                                        echo '<button type="submit" class="button" name="dislike" value="'.$post_id.'"><img src="images/down_neutral.png"></button>';
                                        echo '</div>';
                                        echo '</form>';

                                    }
                                    else if($type == -1){
                                        echo '<form action="" method="POST">';
                                        echo '<div class="post-thumb-left">';
                                        echo '<button type="submit" class="button" name="like" value="'.$post_id.'"><img src="images/up_neutral.png"></button>';
                                        echo '</div>';
                                        echo '<div class="post-thumb-right">';
                                        echo '<button type="submit" class="button" name="disliked" value="'.$post_id.'"><img src="images/down_true.png" ></button>';
                                        echo '</div>';
                                        echo '</form>';
                                    }

                                }

                                //count total like and comment..
                                echo '<div>';
                                $sql = "SELECT count(user_id) as count_like FROM thumbs where post_id='$post_id' AND thumb_type=1 ";
                                $result = $connection->query($sql);
                                $type_row = $result->fetch_assoc();
                                $total_like = $type_row['count_like'];


                                $sql = "SELECT count(user_id) as count_dislike FROM thumbs where post_id='$post_id' AND thumb_type=-1 ";
                                $result = $connection->query($sql);
                                $type_row = $result->fetch_assoc();
                                $total_dislike = $type_row['count_dislike'];

                                echo 'Like:'.$total_like.'&nbsp;'.'Dislike:'.$total_dislike;
                                echo '</div>';

                                //for like clicked..
                                if(isset($_POST['like'])){

                                    $post_id=$_POST['like'];

                                    $sql = "SELECT thumb_type FROM thumbs WHERE (post_id=$post_id) and (user_id=$uid) ";
                                    $result = $connection->query($sql);
                                    $type_row = $result->fetch_assoc();
                                    $type=$type_row['thumb_type'];

                                    if(mysqli_num_rows($result)>0)
                                    {

                                        $sql = "UPDATE thumbs
                                                SET thumb_type=1
                                                where(post_id=$post_id) and (user_id=$uid) ";
                                        $result = $connection->query($sql);

                                        header("Location: timeline.php");
                                    }
                                    else{

                                        $sql = "INSERT INTO thumbs(user_id,post_id,thumb_type) VALUES ('$uid','$post_id',1)";
                                        $result = $connection->query($sql);

                                        header("Location: timeline.php");
                                    }

                                }

                                //for liked clicked..
                                else if(isset($_POST['liked']))
                                {

                                    $post_id=$_POST['liked'];
                                    $sql = "DELETE FROM thumbs WHERE (post_id=$post_id) and (user_id=$uid)";

                                    $result = $connection->query($sql);
                                    header("Location: timeline.php");

                                }

                                //for clicked disliked..
                                else if(isset($_POST['disliked']))
                                {

                                    $post_id=$_POST['disliked'];
                                    $sql = "DELETE FROM thumbs WHERE post_id=$post_id AND user_id=$uid ";

                                    $result = $connection->query($sql);
                                    header("Location: timeline.php");

                                }

                                //for clicked dislike..
                                else if(isset($_POST['dislike'])){

                                    $post_id=$_POST['dislike'];

                                    $sql = "SELECT thumb_type FROM thumbs WHERE (post_id=$post_id) and (user_id=$uid) ";
                                    $result = $connection->query($sql);
                                    $type_row = $result->fetch_assoc();
                                    $type=$type_row['thumb_type'];

                                    if(mysqli_num_rows($result)>0){
                                        $sql = "UPDATE thumbs SET thumb_type=-1 WHERE (post_id=$post_id) and (user_id=$uid)";
                                        $result = $connection->query($sql);
                                    }
                                    else{
                                        $sql = "INSERT INTO thumbs(user_id,post_id,thumb_type) VALUES ('$uid','$post_id',-1)";
                                        $result = $connection->query($sql);

                                    }
                                    header("Location: timeline.php");
                                }



                                echo '<div class="post-comment">
									<form action="" method="POST">
										<textarea name="comment_box" id="textarea"></textarea>
										<button type="submit" name="comment"  value="'.$post_id.'">Comment</button>
									</form>';
                                echo '</div>';

                                //INSERTING into comments table..
                                if(isset($_POST['comment_box'])){
                                    $comment=$_POST['comment_box'];
                                    $post_id=$_POST['comment'];
                                    if(!empty($comment)){
                                        $sql = "INSERT INTO comments(user_id,post_id,comment) VALUES ('$uid','$post_id','$comment')";
                                        $result = $connection->query($sql);

                                        header("Location: timeline.php");
                                    }
                                }
                                //echo all comment..

                                $sql_commenter = "SELECT first_name,last_name,comment,comment_date_time,user_id
                                                      FROM (profile NATURAL JOIN comments)
                                                      WHERE post_id=$post_id ORDER BY comment_date_time DESC";

                                $result_comment = $connection->query($sql_commenter);
                                $classname = "cmmntclass0";
                                while($comment = $result_comment->fetch_assoc()){
                                    $name = $comment['first_name'].' '.$comment['last_name'];
                                    $commenter_uid = $comment['user_id'];
                                    $time = $comment['comment_date_time'];
                                    $comment = $comment['comment'];

                                    echo '<div class="'.$classname.'">';
                                    echo '<div class="commenter">'.$name.'<br></div>';
                                    echo '<div class="cmmnttime">'.$time.'<br></div>';
                                    echo '<div class="comment">'.$comment.'<br></div>';
                                    echo '</div>';
                                    if($classname == "cmmntclass0"){
                                        $classname = "cmmntclass1";
                                    }
                                    else{
                                        $classname = "cmmntclass0";
                                    }
                                }
                                echo '<br><hr><br>';
                                //echo '<div>';
                            }

                            ?>
                        </div>

                        <!---All posts--->
                    </div>

                    <div id="frnd-posts" class="frnd-posts" style="display:none">

                        <h3>Friend's Posts</h3>

                        <div class="post">
                            <?php

                            while($post_details = $timeline_post->fetch_assoc()) {

                                //echo '<div class="individual-post">';

                                $name = $post_details['first_name'].' '.$post_details['last_name'];

                                echo '<div class="post-owner">';
                                echo $name;
                                echo '</div>';

                                echo '<div class="post-date-time">';
                                echo $post_details['postdate_time'];
                                echo '</div>';

                                echo '<div class="post-text">';
                                echo $post_details['post'];
                                echo '</div>';

                                $pic_id = $post_details['pic_id'];
                                $pic_name=$pic_id.'.jpg';

                                $target = 'pictures/'.$pic_name;
                                if (file_exists($target)) {
                                    echo '<img src="'.$target. '"/>';
                                }

                                //for comment.like .dislike..
                                $post_id = $post_details['post_id'];
                                $poster_id = $post_details['user_id'];

                                $sql = "SELECT thumb_type FROM thumbs WHERE (post_id = $post_id) AND (user_id = $uid) ";
                                $result = $connection->query($sql);
                                $type_row = $result->fetch_assoc();
                                $type = $type_row['thumb_type'];



                                if(mysqli_num_rows($result) == 0)
                                {

                                    echo '<form action="" method="POST">';
                                    echo '<div class="post-thumb-left">';
                                    echo '<button type="submit" class="button" name="like" value="'.$post_id.'"><img src="images/up_neutral.png"></button>';
                                    echo '</div>';
                                    echo '<div class="post-thumb-right">';
                                    echo '<button type="submit" class="button" name="dislike" value="'.$post_id.'"><img src="images/down_neutral.png"></button>';
                                    echo '</div>';
                                    echo '</form>';

                                }
                                else{

                                    if($type == 1){
                                        echo '<form action="" method="POST">';
                                        echo '<div class="post-thumb-left">';
                                        echo '<button type="submit" class="button" name="liked" value="'.$post_id.'"><img src="images/up_true.png"></button>';
                                        echo '</div>';
                                        echo '<div class="post-thumb-right">';
                                        echo '<button type="submit" class="button" name="dislike" value="'.$post_id.'"><img src="images/down_neutral.png"></button>';
                                        echo '</div>';
                                        echo '</form>';

                                    }
                                    else if($type == -1){
                                        echo '<form action="" method="POST">';
                                        echo '<div class="post-thumb-left">';
                                        echo '<button type="submit" class="button" name="like" value="'.$post_id.'"><img src="images/up_neutral.png"></button>';
                                        echo '</div>';
                                        echo '<div class="post-thumb-right">';
                                        echo '<button type="submit" class="button" name="disliked" value="'.$post_id.'"><img src="images/down_true.png" ></button>';
                                        echo '</div>';
                                        echo '</form>';
                                    }

                                }

                                //count total like and comment..
                                echo '<div>';
                                $sql = "SELECT count(user_id) as count_like FROM thumbs where post_id='$post_id' AND thumb_type=1 ";
                                $result = $connection->query($sql);
                                $type_row = $result->fetch_assoc();
                                $total_like = $type_row['count_like'];


                                $sql = "SELECT count(user_id) as count_dislike FROM thumbs where post_id='$post_id' AND thumb_type=-1 ";
                                $result = $connection->query($sql);
                                $type_row = $result->fetch_assoc();
                                $total_dislike = $type_row['count_dislike'];

                                echo 'Like:'.$total_like.'&nbsp;'.'Dislike:'.$total_dislike;
                                echo '</div>';

                                //for like clicked..
                                if(isset($_POST['like'])){

                                    $post_id=$_POST['like'];

                                    $sql = "SELECT thumb_type FROM thumbs WHERE (post_id=$post_id) and (user_id=$uid) ";
                                    $result = $connection->query($sql);
                                    $type_row = $result->fetch_assoc();
                                    $type=$type_row['thumb_type'];

                                    if(mysqli_num_rows($result)>0)
                                    {

                                        $sql = "UPDATE thumbs
                                                SET thumb_type=1
                                                where(post_id=$post_id) and (user_id=$uid) ";
                                        $result = $connection->query($sql);

                                        header("Location: timeline.php");
                                    }
                                    else{

                                        $sql = "INSERT INTO thumbs(user_id,post_id,thumb_type) VALUES ('$uid','$post_id',1)";
                                        $result = $connection->query($sql);

                                        header("Location: timeline.php");
                                    }

                                }

                                //for liked clicked..
                                else if(isset($_POST['liked']))
                                {

                                    $post_id=$_POST['liked'];
                                    $sql = "DELETE FROM thumbs WHERE (post_id=$post_id) and (user_id=$uid)";

                                    $result = $connection->query($sql);
                                    header("Location: timeline.php");

                                }

                                //for clicked disliked..
                                else if(isset($_POST['disliked']))
                                {

                                    $post_id=$_POST['disliked'];
                                    $sql = "DELETE FROM thumbs WHERE post_id=$post_id AND user_id=$uid ";

                                    $result = $connection->query($sql);
                                    header("Location: timeline.php");

                                }

                                //for clicked dislike..
                                else if(isset($_POST['dislike'])){

                                    $post_id=$_POST['dislike'];

                                    $sql = "SELECT thumb_type FROM thumbs WHERE (post_id=$post_id) and (user_id=$uid) ";
                                    $result = $connection->query($sql);
                                    $type_row = $result->fetch_assoc();
                                    $type=$type_row['thumb_type'];

                                    if(mysqli_num_rows($result)>0){
                                        $sql = "UPDATE thumbs SET thumb_type=-1 WHERE (post_id=$post_id) and (user_id=$uid)";
                                        $result = $connection->query($sql);
                                    }
                                    else{
                                        $sql = "INSERT INTO thumbs(user_id,post_id,thumb_type) VALUES ('$uid','$post_id',-1)";
                                        $result = $connection->query($sql);

                                    }
                                    header("Location: timeline.php");
                                }



                                echo '<div class="post-comment">
									<form action="" method="POST">
										<textarea name="comment_box" id="textarea"></textarea>
										<button type="submit" name="comment"  value="'.$post_id.'">Comment</button>
									</form>';
                                echo '</div>';

                                //INSERTING into comments table..
                                if(isset($_POST['comment_box'])){
                                    $comment=$_POST['comment_box'];
                                    $post_id=$_POST['comment'];
                                    if(!empty($comment)){
                                        $sql = "INSERT INTO comments(user_id,post_id,comment) VALUES ('$uid','$post_id','$comment')";
                                        $result = $connection->query($sql);

                                        header("Location: timeline.php");
                                    }
                                }
                                //echo all comment..

                                $sql_commenter = "SELECT first_name,last_name,comment,comment_date_time,user_id
                                                      FROM (profile NATURAL JOIN comments)
                                                      WHERE post_id=$post_id ORDER BY comment_date_time DESC";

                                $result_comment = $connection->query($sql_commenter);
                                $classname = "cmmntclass0";
                                while($comment = $result_comment->fetch_assoc()){
                                    $name = $comment['first_name'].' '.$comment['last_name'];
                                    $commenter_uid = $comment['user_id'];
                                    $time = $comment['comment_date_time'];
                                    $comment = $comment['comment'];

                                    echo '<div class="'.$classname.'">';
                                    echo '<div class="commenter">'.$name.'<br></div>';
                                    echo '<div class="cmmnttime">'.$time.'<br></div>';
                                    echo '<div class="comment">'.$comment.'<br></div>';
                                    echo '</div>';
                                    if($classname == "cmmntclass0"){
                                        $classname = "cmmntclass1";
                                    }
                                    else{
                                        $classname = "cmmntclass0";
                                    }
                                }
                                echo '<br><hr><br>';
                                //echo '<div>';
                            }

                            ?>
                        </div>
                    </div>

                    <div id="comm-posts" class="comm-posts" style="display:none">
                        <!---Only community posts--->
                        <h3>All Community Here</h3>

                        <div class="post">
                            <?php

                            while($post_details = $only_community_post->fetch_assoc()) {

                                //echo '<div class="individual-post">';



                                $name = $post_details['first_name'].' '.$post_details['last_name'];

                                echo '<div class="post-owner">';
                                echo $name;
                                echo '</div>';

                                echo '<div class="post-date-time">';
                                echo $post_details['postdate_time'];
                                echo '</div>';

                                echo '<div class="post-text">';
                                echo $post_details['post'];
                                echo '</div>';

                                $pic_id = $post_details['pic_id'];
                                $pic_name=$pic_id.'.jpg';

                                $target = 'pictures/'.$pic_name;
                                if (file_exists($target)) {
                                    echo '<img src="'.$target. '"/>';
                                }

                                //for comment.like .dislike..
                                $post_id = $post_details['post_id'];
                                $poster_id = $post_details['user_id'];

                                $sql = "SELECT thumb_type FROM thumbs WHERE (post_id = $post_id) AND (user_id = $uid) ";
                                $result = $connection->query($sql);
                                $type_row = $result->fetch_assoc();
                                $type = $type_row['thumb_type'];



                                if(mysqli_num_rows($result) == 0)
                                {

                                    echo '<form action="" method="POST">';
                                    echo '<div class="post-thumb-left">';
                                    echo '<button type="submit" class="button" name="like" value="'.$post_id.'"><img src="images/up_neutral.png"></button>';
                                    echo '</div>';
                                    echo '<div class="post-thumb-right">';
                                    echo '<button type="submit" class="button" name="dislike" value="'.$post_id.'"><img src="images/down_neutral.png"></button>';
                                    echo '</div>';
                                    echo '</form>';

                                }
                                else{

                                    if($type == 1){
                                        echo '<form action="" method="POST">';
                                        echo '<div class="post-thumb-left">';
                                        echo '<button type="submit" class="button" name="liked" value="'.$post_id.'"><img src="images/up_true.png"></button>';
                                        echo '</div>';
                                        echo '<div class="post-thumb-right">';
                                        echo '<button type="submit" class="button" name="dislike" value="'.$post_id.'"><img src="images/down_neutral.png"></button>';
                                        echo '</div>';
                                        echo '</form>';

                                    }
                                    else if($type == -1){
                                        echo '<form action="" method="POST">';
                                        echo '<div class="post-thumb-left">';
                                        echo '<button type="submit" class="button" name="like" value="'.$post_id.'"><img src="images/up_neutral.png"></button>';
                                        echo '</div>';
                                        echo '<div class="post-thumb-right">';
                                        echo '<button type="submit" class="button" name="disliked" value="'.$post_id.'"><img src="images/down_true.png" ></button>';
                                        echo '</div>';
                                        echo '</form>';
                                    }

                                }

                                //count total like and comment..
                                echo '<div>';
                                $sql = "SELECT count(user_id) as count_like FROM thumbs where post_id='$post_id' AND thumb_type=1 ";
                                $result = $connection->query($sql);
                                $type_row = $result->fetch_assoc();
                                $total_like = $type_row['count_like'];


                                $sql = "SELECT count(user_id) as count_dislike FROM thumbs where post_id='$post_id' AND thumb_type=-1 ";
                                $result = $connection->query($sql);
                                $type_row = $result->fetch_assoc();
                                $total_dislike = $type_row['count_dislike'];

                                echo 'Like:'.$total_like.'&nbsp;'.'Dislike:'.$total_dislike;
                                echo '</div>';

                                //for like clicked..
                                if(isset($_POST['like'])){

                                    $post_id=$_POST['like'];

                                    $sql = "SELECT thumb_type FROM thumbs WHERE (post_id=$post_id) and (user_id=$uid) ";
                                    $result = $connection->query($sql);
                                    $type_row = $result->fetch_assoc();
                                    $type=$type_row['thumb_type'];

                                    if(mysqli_num_rows($result)>0)
                                    {

                                        $sql = "UPDATE thumbs
                                                SET thumb_type=1
                                                where(post_id=$post_id) and (user_id=$uid) ";
                                        $result = $connection->query($sql);

                                        header("Location: timeline.php");
                                    }
                                    else{

                                        $sql = "INSERT INTO thumbs(user_id,post_id,thumb_type) VALUES ('$uid','$post_id',1)";
                                        $result = $connection->query($sql);

                                        header("Location: timeline.php");
                                    }

                                }

                                //for liked clicked..
                                else if(isset($_POST['liked']))
                                {

                                    $post_id=$_POST['liked'];
                                    $sql = "DELETE FROM thumbs WHERE (post_id=$post_id) and (user_id=$uid)";

                                    $result = $connection->query($sql);
                                    header("Location: timeline.php");

                                }

                                //for clicked disliked..
                                else if(isset($_POST['disliked']))
                                {

                                    $post_id=$_POST['disliked'];
                                    $sql = "DELETE FROM thumbs WHERE post_id=$post_id AND user_id=$uid ";

                                    $result = $connection->query($sql);
                                    header("Location: timeline.php");

                                }

                                //for clicked dislike..
                                else if(isset($_POST['dislike'])){

                                    $post_id=$_POST['dislike'];

                                    $sql = "SELECT thumb_type FROM thumbs WHERE (post_id=$post_id) and (user_id=$uid) ";
                                    $result = $connection->query($sql);
                                    $type_row = $result->fetch_assoc();
                                    $type=$type_row['thumb_type'];

                                    if(mysqli_num_rows($result)>0){
                                        $sql = "UPDATE thumbs SET thumb_type=-1 WHERE (post_id=$post_id) and (user_id=$uid)";
                                        $result = $connection->query($sql);
                                    }
                                    else{
                                        $sql = "INSERT INTO thumbs(user_id,post_id,thumb_type) VALUES ('$uid','$post_id',-1)";
                                        $result = $connection->query($sql);

                                    }
                                    header("Location: timeline.php");
                                }



                                echo '<div class="post-comment">
									<form action="" method="POST">
										<textarea name="comment_box" id="textarea"></textarea>
										<button type="submit" name="comment"  value="'.$post_id.'">Comment</button>
									</form>';
                                echo '</div>';

                                //INSERTING into comments table..
                                if(isset($_POST['comment_box'])){
                                    $comment=$_POST['comment_box'];
                                    $post_id=$_POST['comment'];
                                    if(!empty($comment)){
                                        $sql = "INSERT INTO comments(user_id,post_id,comment) VALUES ('$uid','$post_id','$comment')";
                                        $result = $connection->query($sql);

                                        header("Location: timeline.php");
                                    }
                                }
                                //echo all comment..

                                $sql_commenter = "SELECT first_name,last_name,comment,comment_date_time,user_id
                                                      FROM (profile NATURAL JOIN comments)
                                                      WHERE post_id=$post_id ORDER BY comment_date_time DESC";

                                $result_comment = $connection->query($sql_commenter);
                                $classname = "cmmntclass0";
                                while($comment = $result_comment->fetch_assoc()){
                                    $name = $comment['first_name'].' '.$comment['last_name'];
                                    $commenter_uid = $comment['user_id'];
                                    $time = $comment['comment_date_time'];
                                    $comment = $comment['comment'];

                                    echo '<div class="'.$classname.'">';
                                    echo '<div class="commenter">'.$name.'<br></div>';
                                    echo '<div class="cmmnttime">'.$time.'<br></div>';
                                    echo '<div class="comment">'.$comment.'<br></div>';
                                    echo '</div>';
                                    if($classname == "cmmntclass0"){
                                        $classname = "cmmntclass1";
                                    }
                                    else{
                                        $classname = "cmmntclass0";
                                    }
                                }
                                echo '<br><hr><br>';
                                //echo '<div>';
                            }

                            ?>
                        </div>

                        <!---Only community posts--->
                    </div>
                </div>
            </div>

            <div class="col col-2">
                <div  class="right-nav"><?php include 'backend/right-nav.php'; ?></div>
            </div>

        </div>
    </section>
    <footer class="footer">Under Copyright<sup style="font-size: 5px">TM</sup></footer>
</div>
</body>
</html>﻿

