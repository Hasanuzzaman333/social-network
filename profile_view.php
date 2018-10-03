<?php
/**
 * Created by PhpStorm.
 * User: Ashiqur Rahman
 * Date: 13-Dec-16
 * Time: 10:15 PM
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

            if(divName != 'about-me'){
                document.getElementById('about-me').style.display = "none";
            }
            if(divName != 'my-posts'){
                document.getElementById('my-posts').style.display = "none";
            }
        }
    }

</script>




<!DOCTYPE HTML>
<html>
<head>
    <title><?php echo $allFromProfileRow['first_name']." ".$allFromProfileRow['last_name']; ?></title>
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
                        <li><a href="javascript:showDiv('my-posts')">Posts</a></li>
                        <li><a href="javascript:showDiv('about-me')">About Me</a></li>
                    </ul>

                </div>

                <div class="content-panel">

                    <div id="my-posts" class="my-posts" style="display:block">

                        <div class="new-post">
                            <h3>New Post</h3>

                            <form action='profile_view.php' method='POST' enctype='multipart/form-data'>
                                <textarea name="post_box" id="textarea" placeholder="Type your words..." ></textarea><br>
                                <input type='file' name='post_pic' accept="image/*"><br>
                                <input type='submit' name='upload_pic' value='Post'>
                            </form>

                            <?php

                            if(isset($_POST['upload_pic']) && isset($_POST['post_box'])){

                                $post_value = $_POST['post_box'];

                                if(!empty($post_value) || (is_uploaded_file($_FILES['post_pic']['tmp_name'])) ) {

                                    $sql = "INSERT INTO posts(post,is_community_post) VALUES ('$post_value','-1')";
                                    $result = $connection->query($sql);
                                    $post_id = mysqli_insert_id($connection);

                                    $sql = "INSERT INTO post_pictures(post_id) VALUES ('$post_id')";
                                    $result = $connection->query($sql);
                                    $pic_id = mysqli_insert_id($connection);

                                    //pic_conversion..

                                    //upload_image
                                    $newname = $pic_id . '.jpg';

                                    $target = 'pictures/' . $newname;
                                    $check = move_uploaded_file($_FILES['post_pic']['tmp_name'], $target);

                                    //inserting posting table

                                    $sql = "INSERT INTO posting(user_id,post_id) VALUES ('$uid','$post_id')";
                                    $connection->query($sql);

                                    if ($sql) {
                                        header("Location: profile_view.php");
                                    }
                                }
                            }
                            ?>

                        </div>


                        <h3>My Older Posts</h3>

                        <div class="post">
                            <?php
                            $name = $allFromProfileRow['first_name'].' '.$allFromProfileRow['last_name'];
                            while(($post_details = $post_posting->fetch_assoc()))
                            {
                                echo '<div class="individual-post">';

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

                                //break

                                $sql = "SELECT thumb_type FROM thumbs WHERE (post_id = $post_id) AND (user_id = $uid) ";
                                $result = $connection->query($sql);
                                $type_row = $result->fetch_assoc();
                                $type = $type_row['thumb_type'];



                                if(mysqli_num_rows($result) == 0)
                                {

                                    echo '<form action="profile_view.php" method="POST">';
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
                                        echo '<form action="profile_view.php" method="POST">';
                                            echo '<div class="post-thumb-left">';
                                                echo '<button type="submit" class="button" name="liked" value="'.$post_id.'"><img src="images/up_true.png"></button>';
                                            echo '</div>';
                                            echo '<div class="post-thumb-right">';
                                                echo '<button type="submit" class="button" name="dislike" value="'.$post_id.'"><img src="images/down_neutral.png"></button>';
                                            echo '</div>';
                                        echo '</form>';

                                    }
                                    else if($type == -1){
                                        echo '<form action="profile_view.php" method="POST">';
                                            echo '<div class="post-thumb-left">';
                                                echo '<button type="submit" class="button" name="like" value="'.$post_id.'"><img src="images/up_neutral.png"></button>';
                                            echo '</div>';
                                            echo '<div class="post-thumb-right">';
                                                echo '<button type="submit" class="button" name="disliked" value="'.$post_id.'"><img src="images/down_true.png" ></button>';
                                            echo '</div>';
                                        echo '</form>';
                                    }
                                }

                                echo '<div>';
                                    $sql = "SELECT count(user_id) as count_like FROM thumbs where post_id='$post_id' AND thumb_type=1 ";
                                    $result = $connection->query($sql);
                                    $type_row = $result->fetch_assoc();
                                    $total_like = $type_row['count_like'];

                                    $sql = "SELECT count(user_id) as count_dislike FROM thumbs where post_id='$post_id' AND thumb_type=-1 ";
                                    $result = $connection->query($sql);
                                    $type_row = $result->fetch_assoc();
                                    $total_dislike = $type_row['count_dislike'];
                                    echo 'Like:'.$total_like.'&nbsp;&nbsp;&nbsp;'.'Dislike:'.$total_dislike;
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
                                                WHERE(post_id=$post_id) and (user_id=$uid) ";
                                        $result = $connection->query($sql);
                                        header("Location: profile_view.php");
                                    }
                                    else{

                                        $sql = "INSERT INTO thumbs(user_id,post_id,thumb_type) VALUES ('$uid','$post_id',1)";
                                        $result = $connection->query($sql);

                                        header("Location: profile_view.php");
                                    }
                                }

                                //for liked clicked..
                                else if(isset($_POST['liked']))
                                {
                                    $post_id=$_POST['liked'];
                                    $sql = "DELETE FROM thumbs WHERE (post_id=$post_id) and (user_id=$uid)";

                                    $result = $connection->query($sql);
                                    header("Location: profile_view.php");
                                }

                                //for clicked disliked..
                                else if(isset($_POST['disliked']))
                                {
                                    $post_id=$_POST['disliked'];
                                    $sql = "DELETE FROM thumbs WHERE post_id=$post_id AND user_id=$uid ";
                                    $result = $connection->query($sql);
                                    header("Location: profile_view.php");

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
                                    header("Location: profile_view.php");
                                }
                                //
                                echo '<br/>';
                                echo '<div class="post-comment">
                                    <form action="" method="POST">
                                        <textarea name="comment_box" id="textarea"></textarea>
                                        <button class="button" type="submit" name="comment"  value="'.$post_id.'">Comment</button>
                                    </form>';
                                echo '</div>';


                                //INSERTING into comments table..
                                if(isset($_POST['comment_box'])){
                                    $comment=$_POST['comment_box'];
                                    $post_id=$_POST['comment'];
                                    if(!empty($comment)){
                                        $sql = "INSERT INTO comments(user_id,post_id,comment) VALUES ('$uid','$post_id','$comment')";
                                        $result = $connection->query($sql);

                                        //header("Location: profile_view.php");
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
                                echo '</div>';
                            }
                            ?>
                        </div> <!--- End  of div class = "post"--->



                    </div>

                    <div id="about-me" style="display:none">

                        <h3>Basic Information</h3>

                        <?php
                        echo "Name: <b>$allFromProfileRow[first_name] $allFromProfileRow[last_name]</b><br>";
                        if($allFromProfileRow['nick_name'] != null){
                            echo "Nick Name: <b>$allFromProfileRow[nick_name]</b><br>";
                        }
                        echo "Date of Birth : <b>$allFromProfileRow[b_date]</b><br>";
                        echo "Gender: <b>$allFromProfileRow[gender]</b><br>";
                        ?>

                        <h3>Contact Information</h3>

                        <?php
                        echo "Email: <b>$allFromProfileRow[email]</b><br>";

                        if($allFromProfileRow['phone_no'] == null) echo "Phone: No phone no. has been provided<br>";
                        else echo "Phone: </b>$allFromProfileRow[phone_no]</b><br>";

                        if($allFromProfileRow['street_no'] == null) echo "Street No: No street so. has been provided<br>";
                        else echo "Street No: <b>$allFromProfileRow[street_no]</b><br>";

                        if($allFromProfileRow['house_no'] == null) echo "House No: No house no. has been provided<br>";
                        else echo "House No: <b>$allFromProfileRow[house_no]</b><br>";

                        if($allFromProfileRow['district'] == null) echo "District : No district has been provided<br>";
                        else echo "District : <b>$allFromProfileRow[district]</b><br>";

                        if($allFromProfileRow['country'] == null) echo "Country: No country information found<br>";
                        else echo "Country: <b>$allFromProfileRow[country]</b><br>";

                        ?>

                        <h3>Education</h3>

                        <?php
                        if($educationresult == null){
                            echo "No Educational Information Has Been Provided <br>";
                        }
                        else{
                            while($educationRow = $educationresult -> fetch_assoc()){
                                echo "Class: <b>$educationRow[edu_status]</b><br>";
                                echo "Passing Year: <b>$educationRow[year_of_passing]</b><br>";
                                echo "Institute: <b>$educationRow[institute]</b><br><br>";
                            }
                        }
                        ?>

                        <h3>Details</h3>

                        <?php
                        if($allFromProfileRow['occupation'] != null) echo "Occupation : <b>$allFromProfileRow[occupation]</b> <br>";
                        if($allFromProfileRow['about'] != null) echo "About : <b>$allFromProfileRow[about]</b> <br>";
                        if($allFromProfileRow['fav_quote'] != null) echo "Favourite Quote : <b>$allFromProfileRow[fav_quote]</b> <br>";
                        ?>

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
