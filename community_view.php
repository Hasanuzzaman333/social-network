<?php
/**
 * Created by PhpStorm.
 * User: Ashiqur Rahman
 * Date: 31-Dec-16
 * Time: 2:07 AM
 */
ob_start();
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
}

$uid = $_SESSION['user_id'];
$query_user_id = $uid;
//include 'backend/database_profile_details.php';
include 'backend/database_connection.php';

$viewable_comm_id = '';

if (isset($_POST['commname'])) {
    $comm_name = $_POST['commname'];
    $comm_type = $_POST['comm-type'];

    $connection->query("INSERT INTO community(name, type)  VALUES('$comm_name', '$comm_type')");
    $viewable_comm_id = mysqli_insert_id($connection);
    $connection->query("INSERT INTO membership(user_id, comm_id, member_type) VALUES ('$uid', '$viewable_comm_id', 1)");
}
else if(isset($_GET['viewable_comm_id'])){
    $viewable_comm_id = $_GET['viewable_comm_id'];
}
else{
    header("Location: community.php");
}
include 'backend/database_community.php';


if($member_type == 4){
    header("Location: community.php");
}
else if($member_type == null || $member_type == ''){
    $member_type = 3;
}
/*member type
    1 = admin
    2 = members
    3 = non member
    4 = banned member
    5 = request
*/
?>



<script type="text/javascript">

    function showDiv(divName) {
        if(document.getElementById(divName).style.display != "block"){
            document.getElementById(divName).style.display = "block";

            if(divName != 'info'){
                document.getElementById('info').style.display = "none";
            }
            if(divName != 'my-posts'){
                document.getElementById('my-posts').style.display = "none";
            }
            if(divName != 'requests'){
                document.getElementById('requests').style.display = "none";
            }
            if(divName != 'banned'){
                document.getElementById('banned').style.display = "none";
            }
            if(divName != 'logo'){
                document.getElementById('logo').style.display = "none";
            }
        }
    }

</script>



<!DOCTYPE HTML>
<html>
<head>
    <title><?php echo $comm_details['name']?></title>
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
                        <?php
                        if($member_type == 1 || $member_type == 2){
                            echo '<li><a href="javascript:showDiv(\'my-posts\')">Posts</a></li>';
                        }

                        echo '<li><a href="javascript:showDiv(\'info\')">Info</a></li>';

                        if($member_type == 1){
                            echo '<li><a href="javascript:showDiv(\'requests\')">Requests</a></li>';
                            echo '<li><a href="javascript:showDiv(\'banned\')">Banned Members</a></li>';
                            echo '<li><a href="javascript:showDiv(\'logo\')">Logo</a></li>';
                        }
                        ?>
                    </ul>

                </div>

                <div class="content-panel">


                    <div id="my-posts" class="my-posts" style="display:block">
                        <div class="new-post">

                            <?php
                            if($member_type == 1 || $member_type == 2){?>
                                <h3>New Post</h3>

                                <form action='<?php echo "community_view.php?viewable_comm_id=".$viewable_comm_id ?>' method='POST' enctype='multipart/form-data'>
                                    <textarea name="post_box" id="textarea" placeholder="Type your words..." ></textarea><br>
                                    <input type='file' name='post_pic' accept="image/*"><br>
                                    <input type='submit' name='upload_pic' value='Post'>
                                </form>
                                <?php


                                if(isset($_POST['upload_pic']) && isset($_POST['post_box'])){

                                    $post_value = $_POST['post_box'];

                                    if(!empty($post_value) || (is_uploaded_file($_FILES['post_pic']['tmp_name'])) ) {

                                        $sql = "INSERT INTO posts(post,is_community_post) VALUES ('$post_value','1')";
                                        $result = $connection->query($sql);
                                        $post_id = mysqli_insert_id($connection);

                                        $sql = "INSERT INTO post_pictures(post_id) VALUES ('$post_id')";
                                        $result = $connection->query($sql);
                                        $pic_id = mysqli_insert_id($connection);

                                        $sql = "INSERT INTO posts_in_community(comm_id,post_id) VALUES ('$viewable_comm_id','$post_id')";
                                        $result = $connection->query($sql);


                                        //pic_conversion..

                                        //upload_image
                                        $newname = $pic_id . '.jpg';

                                        $target = 'pictures/' . $newname;
                                        $check = move_uploaded_file($_FILES['post_pic']['tmp_name'], $target);

                                        //inserting posting table

                                        $sql = "INSERT INTO posting(user_id,post_id) VALUES ('$uid','$post_id')";
                                        $connection->query($sql);

                                        if ($sql) {
                                            //$link = "community_view.php?viewable_comm_id=".$viewable_comm_id;
                                            header("Location: community_view.php?viewable_comm_id=$viewable_comm_id ");
                                        }
                                    }
                                }


                                echo '</div>';






                                echo '<h3>Old Posts</h3>';

                                //<!--Just View POst code Here-->

                                echo '<div class="post">';

                                while($post_details = $community_post->fetch_assoc()) {

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

                                    //ok.......

                                    $sql = "SELECT thumb_type FROM thumbs WHERE (post_id = $post_id) AND (user_id = $uid) ";
                                    $result = $connection->query($sql);
                                    $type_row = $result->fetch_assoc();
                                    $type = $type_row['thumb_type'];



                                    if(mysqli_num_rows($result) == 0)
                                    {

                                        echo '<form action="community_view.php?viewable_comm_id='.$viewable_comm_id.'" method="POST">';
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
                                            echo '<form action="community_view.php?viewable_comm_id='.$viewable_comm_id.'" method="POST">';
                                            echo '<div class="post-thumb-left">';
                                            echo '<button type="submit" class="button" name="liked" value="'.$post_id.'"><img src="images/up_true.png"></button>';
                                            echo '</div>';
                                            echo '<div class="post-thumb-right">';
                                            echo '<button type="submit" class="button" name="dislike" value="'.$post_id.'"><img src="images/down_neutral.png"></button>';
                                            echo '</div>';
                                            echo '</form>';

                                        }
                                        else if($type == -1){
                                            echo '<form action="community_view.php?viewable_comm_id='.$viewable_comm_id.'" method="POST">';
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
                                                    WHERE(post_id=$post_id) and (user_id=$uid) ";
                                            $result = $connection->query($sql);
                                            header("Location: community_view.php?viewable_comm_id=$viewable_comm_id");
                                        }
                                        else{

                                            $sql = "INSERT INTO thumbs(user_id,post_id,thumb_type) VALUES ('$uid','$post_id',1)";
                                            $result = $connection->query($sql);

                                            header("Location: community_view.php?viewable_comm_id=$viewable_comm_id");

                                        }
                                    }


                                    //for liked clicked..
                                    else if(isset($_POST['liked']))
                                    {
                                        $post_id=$_POST['liked'];
                                        $sql = "DELETE FROM thumbs WHERE (post_id=$post_id) and (user_id=$uid)";

                                        $result = $connection->query($sql);
                                        header("Location: community_view.php?viewable_comm_id=$viewable_comm_id");
                                    }

                                    //for clicked disliked..
                                    else if(isset($_POST['disliked']))
                                    {
                                        $post_id=$_POST['disliked'];
                                        $sql = "DELETE FROM thumbs WHERE post_id=$post_id AND user_id=$uid ";
                                        $result = $connection->query($sql);
                                        header("Location: community_view.php?viewable_comm_id=$viewable_comm_id");

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
                                        header("Location: community_view.php?viewable_comm_id=$viewable_comm_id");
                                    }


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

                                            header("Location: community_view.php?viewable_comm_id=$viewable_comm_id");

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
                                    //end while...
                                }
                            }
                            ?>

                        </div>

                    </div>

                    <?php
                    if($member_type == 3 || $member_type == 5){
                        echo '<div id="info" style="display:block">';
                    }
                    else{
                        echo '<div id="info" style="display:none">';
                    }
                    ?>
                        <h3>Information</h3>
                        <?php
                            echo '<div class="my-pro-pic">';
                            if($comm_details['logo_pic_id'] == null){
                                echo '<img src="images/comm_logo_default.png">';
                            }
                            else{
                                echo '<img src="pictures/'.$comm_details['logo_pic_id'].'_logo_pic.jpg">';
                            }
                            echo '</div>';

                            echo 'Name: <b>'.$comm_details['name'].'</b><br>';
                            echo 'Type: <b>'.$comm_details['type'].'</b><br>';
                            echo 'Member: <b>'.$no_of_members['members'].'</b><br>';
                            echo 'Created: <b>'.$comm_details['age_month'].' months ago</b><br>';

                            if($member_type == 3) {
                                echo '<form name="" action="" method="POST">';
                                echo '<input type="submit" name="join" value="Join">';
                                echo '</form>';
                            }
                            else if($member_type == 2 || $member_type == 5) {
                                echo '<form name="" action="" method="POST">';
                                echo '<input type="submit" name="leave" value="Leave">';
                                echo '</form>';
                            }

                            if (isset($_POST['join'])){
                                $connection -> query("INSERT INTO membership(user_id, comm_id, member_type)
                                                      VALUES('$uid', '$viewable_comm_id', '5')");
                                header("Location: community_view.php?viewable_comm_id=".$viewable_comm_id);
                            }
                            else if(isset($_POST['leave'])){
                                $connection -> query("DELETE FROM membership WHERE (user_id = '$uid') AND (comm_id = '$viewable_comm_id')");
                                header("Location: community_view.php?viewable_comm_id=".$viewable_comm_id);
                            }
                        ?>
                        <h3>Admin</h3>

                        <?php
                        while($profile = $admin_profile_rows -> fetch_assoc()){


                            $profile_link = "profile_view_other.php?viewable_uid=".$profile['user_id'];

                            echo '<a href= '.$profile_link.'>';
                            echo '<div class="srch_rslt_div">';
                            if ($profile['pro_pic_id'] == null)echo '<img src="images/default_pro_pic.png">';
                            else{
                            echo '<img src="pictures/'.$profile['pro_pic_id'].'_pro_pic.jpg">';
                            }

                                echo '<ul>';
                                    echo '<li>'.$profile["first_name"].' '.$profile["last_name"].'</a></li>';
                                    if ($profile["nick_name"] != null)echo '<li><a href="#">'.$profile["nick_name"].'</a></li>';
                                    echo '<li>'.$profile["email"].'</li>';
                                echo '</ul>';

                            echo '</div>';
                        }
                        ?>


                        <h3>Member</h3>
                        <?php
                        while($profile = $member_profile_rows -> fetch_assoc()){


                            $profile_link = "profile_view_other.php?viewable_uid=".$profile['user_id'];

                            echo '<a href= '.$profile_link.'>';
                            echo '<div class="srch_rslt_div">';
                            if ($profile['pro_pic_id'] == null)echo '<img src="images/default_pro_pic.png">';
                            else{
                                echo '<img src="pictures/'.$profile['pro_pic_id'].'_pro_pic.jpg">';
                            }

                            echo '<ul>';
                            echo '<li>'.$profile["first_name"].' '.$profile["last_name"].'</a></li>';
                            if ($profile["nick_name"] != null)echo '<li><a href="#">'.$profile["nick_name"].'</a></li>';
                            echo '<li>'.$profile["email"].'</li>';
                            echo '</ul>';

                            echo '</div>';

                            if($member_type == 1){
                                echo '<form name="" action="community_view.php?viewable_comm_id='.$viewable_comm_id.' & ban_id='.$profile["user_id"].'" method="POST">';
                                echo '<input type="submit" name="ban" value="Ban">';
                                echo '</form>';
                            }
                        }

                        if(isset($_GET['ban_id'])){

                            $connection -> query("UPDATE membership SET member_type = 4 
                                                  WHERE comm_id = $viewable_comm_id AND user_id = ".$_GET['ban_id']);
                            //header("Location: community_view.php?viewable_comm_id='.$viewable_comm_id");
                        }
                        ?>
                    </div>

                    <div id="requests" style="display:none">
                        <h3>Requests</h3>
                        <?php
                        while($profile = $request_profile_rows -> fetch_assoc()){


                            $profile_link = "profile_view_other.php?viewable_uid=".$profile['user_id'];

                            echo '<a href= '.$profile_link.'>';
                            echo '<div class="srch_rslt_div">';
                            if ($profile['pro_pic_id'] == null)echo '<img src="images/default_pro_pic.png">';
                            else{
                                echo '<img src="pictures/'.$profile['pro_pic_id'].'_pro_pic.jpg">';
                            }

                            echo '<ul>';
                            echo '<li>'.$profile["first_name"].' '.$profile["last_name"].'</a></li>';
                            if ($profile["nick_name"] != null)echo '<li><a href="#">'.$profile["nick_name"].'</a></li>';
                            echo '<li>'.$profile["email"].'</li>';
                            echo '</ul>';

                            echo '</div>';
                            echo '<form name="" action="community_view.php?viewable_comm_id='.$viewable_comm_id.' & acpt_id='.$profile["user_id"].'" method="POST">';
                            echo '<input type="submit" name="accept" value="Accept">';
                            echo '</form>';

                            echo '<form name="" action="community_view.php?viewable_comm_id='.$viewable_comm_id.' & rjct_id='.$profile["user_id"].'" method="POST">';
                            echo '<input type="submit" name="reject" value="Reject">';
                            echo '</form>';

                        }

                        if(isset($_GET['acpt_id'])){
                            $connection -> query("UPDATE membership SET member_type = 2 
                                                  WHERE comm_id = $viewable_comm_id AND user_id = ".$_GET['acpt_id']);
                            //header("Location: community_view.php?viewable_comm_id='.$viewable_comm_id");
                        }
                        else if(isset($_GET['rjct_id'])){
                            $connection -> query("DELETE FROM membership  
                                                  WHERE member_type = 5 AND comm_id = $viewable_comm_id AND user_id = ".$_GET['rjct_id']);
                            //header("Location: community_view.php?viewable_comm_id='.$viewable_comm_id");
                        }
                        ?>
                    </div>

                    <div id="banned" style="display:none">
                        <h3>Banned Members</h3>
                        <?php
                        while($profile = $banned_profile_rows -> fetch_assoc()){


                            $profile_link = "profile_view_other.php?viewable_uid=".$profile['user_id'];

                            echo '<a href= '.$profile_link.'>';
                            echo '<div class="srch_rslt_div">';
                            if ($profile['pro_pic_id'] == null)echo '<img src="images/default_pro_pic.png">';
                            else{
                                echo '<img src="pictures/'.$profile['pro_pic_id'].'_pro_pic.jpg">';
                            }

                            echo '<ul>';
                            echo '<li>'.$profile["first_name"].' '.$profile["last_name"].'</a></li>';
                            if ($profile["nick_name"] != null)echo '<li><a href="#">'.$profile["nick_name"].'</a></li>';
                            echo '<li>'.$profile["email"].'</li>';
                            echo '</ul>';

                            echo '</div>';


                            if($member_type == 1){
                                echo '<form name="" action="community_view.php?viewable_comm_id='.$viewable_comm_id.' & unban_id='.$profile["user_id"].'" method="POST">';
                                echo '<input type="submit" name="unban" value="Unan">';
                                echo '</form>';
                            }
                        }
                        if(isset($_GET['unban_id'])){

                            $connection -> query("UPDATE membership SET member_type = 2 
                                                  WHERE comm_id = $viewable_comm_id AND user_id = ".$_GET['unban_id']);
                            //header("Location: community_view.php?viewable_comm_id='.$viewable_comm_id");
                        }
                        ?>
                    </div>

                    <div id="logo" style="display:none">
                        <h3>Change Logo</h3>

                        <?php
                            $link = 'community_view.php?viewable_comm_id='.$viewable_comm_id;
                        ?>

                        <form action="<?php echo $link; ?>" enctype="multipart/form-data" method="POST">
                            <input type='file' name='logo_pic' value="Choose Logo" accept="image/*"><br>
                            <input type='submit' name='upload_logo' value='Update Logo'>
                        </form>


                        <?php
                        if(isset($_POST['upload_logo'])){

                            $pic_name = $viewable_comm_id.'_logo_pic.jpg';

                            $target = 'pictures/'.$pic_name;
                            $check = move_uploaded_file( $_FILES['logo_pic']['tmp_name'], $target);

                            $query = "UPDATE community SET logo_pic_id = '$viewable_comm_id' WHERE comm_id = '$viewable_comm_id'";
                            $connection->query($query);
                        }
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





