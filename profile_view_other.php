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

$viewable_uid = '';
if(isset($_GET['viewable_uid'])){
    if( $_GET['viewable_uid'] == '' || $_GET['viewable_uid'] == $uid){
        header("Location: profile_view.php");
    }
    $viewable_uid = $_GET['viewable_uid'];
}
$query_user_id = $viewable_uid;
include 'backend/database_connection.php';
include 'backend/database_profile_details.php';
include 'backend/database_posts.php';
include 'backend/database_friends.php';
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
</script>


<!DOCTYPE HTML>
<html>
<head>
<title><?php echo $allFromProfileRow['first_name']." ".$allFromProfileRow['last_name'];?></title>
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
    $user_pro_pic_id = "SELECT pro_pic_id FROM profile WHERE user_id =".$uid;
    $user_pro_pic_id = $connection -> query($user_pro_pic_id);
    $user_pro_pic_id = $user_pro_pic_id -> fetch_assoc();

    if($user_pro_pic_id['pro_pic_id'] == null){
        echo '<img src="images/default_pro_pic.png">';
    }
    else{
        echo '<img src="pictures/'.$user_pro_pic_id['pro_pic_id'].'_pro_pic.jpg">';
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
    <li><a href="javascript:showDiv('about-me')">About</a></li>
    </ul>

    </div>

    <div class="content-panel">
    <?php

    $is_friend =  "SELECT EXISTS (SELECT 1 FROM friendship 
                                WHERE (friendship.user1_id =".$uid." AND friendship.user2_id =".$viewable_uid.") OR 
                                (friendship.user2_id =".$uid." AND friendship.user1_id =".$viewable_uid.")) AS bool";
    $is_friend = $connection -> query($is_friend);
    $is_friend = $is_friend -> fetch_assoc();


    $did_i_invite = "SELECT EXISTS (SELECT 1 FROM friendship_requests 
                                      WHERE (request_by =".$uid." AND request_to =".$viewable_uid.")) AS bool";
    $did_i_invite = $connection -> query($did_i_invite);
    $did_i_invite = $did_i_invite -> fetch_assoc();

    $did_he_invite = "SELECT EXISTS (SELECT 1 FROM friendship_requests 
                                      WHERE (request_by =".$viewable_uid." AND request_to =".$uid.")) AS bool";
    $did_he_invite = $connection -> query($did_he_invite);
    $did_he_invite = $did_he_invite -> fetch_assoc();

    if($is_friend['bool']){
        echo 'You have friendship with this person<br>';
        echo '<form action="profile_view_other.php?viewable_uid='.$viewable_uid.'" method="POST">';
        echo '<input type="submit" name="end_frndship" value="End Friendship">';
        echo '</form>';
    }
    else if($did_i_invite['bool']){
        echo 'Friendship invitation sent to this person<br>';
        echo '<form action="profile_view_other.php?viewable_uid='.$viewable_uid.'" method="POST">';
        echo '<input type="submit" name="cncl_invite" value="Cancel Invitation">';
        echo '</form>';
    }
    else if($did_he_invite['bool']){
        echo 'Friendship invitation received from this person<br>';

        echo '<form action="profile_view_other.php?viewable_uid='.$viewable_uid.'" method="POST">';
        echo '<input type="submit" name="acpt_invite" value="Accept Invitation">';
        echo '<input type="submit" name="rjct_invite" value="Reject Invitation">';
        echo '</form>';
    }
    else{
        echo 'You are not connected with this person<br>';
        echo '<form action="profile_view_other.php?viewable_uid='.$viewable_uid.'" method="POST">';
        echo '<input type="submit" name="invite_frndship" value="Invite Friendship">';
        echo '</form>';

    }

    //Messaging Form
    echo '<form action="messaging.php?messaging_uid='.$viewable_uid.'" method="POST">';
    echo '<input type="submit" name="send_msg" value="Send Message">';
    echo '</form>';


    if(isset($_POST['invite_frndship'])){
        $query = "INSERT INTO friendship_requests(request_by, request_to) VALUES('$uid','$viewable_uid')";
        $connection -> query($query);
        if($query){
            header("Location: profile_view_other.php?viewable_uid=$viewable_uid");
        }
    }
    else if(isset($_POST['end_frndship'])){

        $query = "DELETE FROM friendship WHERE (user1_id = '$uid' AND user2_id = '$viewable_uid')
                                                                OR (user2_id = '$uid' AND user1_id = '$viewable_uid')";
        $connection -> query($query);
        if($query){
            header("Location: profile_view_other.php?viewable_uid=$viewable_uid");
        }
    }
    else if(isset($_POST['cncl_invite'])){

        $query = "DELETE FROM friendship_requests WHERE request_by = '$uid' AND request_to = '$viewable_uid'";
        $connection -> query($query);
        if($query){
            header("Location: profile_view_other.php?viewable_uid=$viewable_uid");
        }
    }
    else if(isset($_POST['rjct_invite'])){

        $query = "DELETE FROM friendship_requests WHERE request_to = '$uid' AND request_by = '$viewable_uid'";
        $connection -> query($query);
        if($query){
            header("Location: profile_view_other.php?viewable_uid=$viewable_uid");
        }
    }
    else if(isset($_POST['acpt_invite'])){

        $query = "DELETE FROM friendship_requests WHERE request_to = '$uid' AND request_by = '$viewable_uid'";
        $connection -> query($query);
        $query = "INSERT INTO friendship(user1_id,user2_id) VALUES('$viewable_uid','$uid')";
        $connection -> query($query);

        if($query){
            header("Location: profile_view_other.php?viewable_uid=$viewable_uid");
        }
    }



    echo '<div id="about-me" style="display:none">';

    echo '<div class="my-pro-pic">';

    if($allFromProfileRow['pro_pic_id'] == null){
        echo '<img src="images/default_pro_pic.png">';
    }
    else{
        echo '<img src="pictures/'.$allFromProfileRow['pro_pic_id'].'_pro_pic.jpg">';
    }

    echo '</div>';



    echo '<h3>Basic Information</h3>';
    echo "Name: <b>$allFromProfileRow[first_name] $allFromProfileRow[last_name]</b><br>";
    if($allFromProfileRow['nick_name'] != null){
        echo "Nick Name: <b>$allFromProfileRow[nick_name]</b><br>";
    }
    echo "Date of Birth : <b>$allFromProfileRow[b_date]</b><br>";
    echo "Gender: <b>$allFromProfileRow[gender]</b><br>";

    echo '<h3>Contact Information</h3>';

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

    echo '<h3>Education</h3>';

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

    echo '<h3>Details</h3>';

    if($allFromProfileRow['occupation'] != null) echo "Occupation : <b>$allFromProfileRow[occupation]</b> <br>";
    if($allFromProfileRow['about'] != null) echo "About : <b>$allFromProfileRow[about]</b> <br>";
    if($allFromProfileRow['fav_quote'] != null) echo "Favourite Quote : <b>$allFromProfileRow[fav_quote]</b> <br>";

    echo '</div>';
    ?>

    <!-- posts -->
    <?php
    echo '<div id="my-posts" class="my-posts" style="display:block">';
    $name = $allFromProfileRow['first_name'].' '.$allFromProfileRow['last_name'];
    echo '<h3>Posts By: '.$name.'</h3>';

    while(($post_details = $post_posting->fetch_assoc()))
    {
        echo '<div class="post">';
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

        $pic_name=$pic_id.'.'.'jpg';
        $target = 'pictures/'.$pic_name;
        if (file_exists($target)){
            echo '<img src="'.$target. '"/>';
        }
        else{
            echo 'No picture available';
        }
        echo '</div>';


        //like comment....


        // header("Location: profile_view_other.php?viewable_uid=$viewable_uid");

        $post_id = $post_details['post_id'];



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
            if($type == -1){
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
                                                where(post_id=$post_id) and (user_id=$uid) ";
                $result = $connection->query($sql);

                header("Location: profile_view_other.php?viewable_uid=$viewable_uid");
            }
            else{

                $sql = "INSERT INTO thumbs(user_id,post_id,thumb_type) VALUES ('$uid','$post_id',1)";
                $result = $connection->query($sql);

                header("Location: profile_view_other.php?viewable_uid=$viewable_uid");
            }

        }

        //for liked clicked..
        else if(isset($_POST['liked']))
        {

            $post_id=$_POST['liked'];
            $sql = "DELETE FROM thumbs WHERE (post_id=$post_id) and (user_id=$uid)";

            $result = $connection->query($sql);
            header("Location: profile_view_other.php?viewable_uid=$viewable_uid");

        }

        //for clicked disliked..
        else if(isset($_POST['disliked']))
        {

            $post_id=$_POST['disliked'];
            $sql = "DELETE FROM thumbs WHERE post_id=$post_id AND user_id=$uid ";

            $result = $connection->query($sql);
            header("Location: profile_view_other.php?viewable_uid=$viewable_uid");

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
            header("Location: profile_view_other.php?viewable_uid=$viewable_uid");
        }
        //

        echo '<br/>';
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

                //header("Location: profile_view_other.php?viewable_uid=$viewable_uid");

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


        //end of like comment....

    }
    echo '</div>';
    ?>

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
