<?php
/**
 * Created by PhpStorm.
 * User: Ashiqur Rahman
 * Date: 17-Dec-16
 * Time: 4:51 AM
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
include 'backend/database_friends.php';
?>


<script type="text/javascript">

    function showDiv(divName) {
        if(document.getElementById(divName).style.display != "block"){
            document.getElementById(divName).style.display = "block";

            if(divName != 'friendship-invites'){
                document.getElementById('friendship-invites').style.display = "none";
            }
            if(divName != 'connected-people'){
                document.getElementById('connected-people').style.display = "none";
            }
            if(divName != 'suggested-people'){
                document.getElementById('suggested-people').style.display = "none";
            }
        }
    }

</script>

<!DOCTYPE HTML>
<html>
<head>
    <title>People</title>
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
            <?php include "backend/top_nav.php"; ?>
        </nav>

    </div>

    <section class="content_area">
        <div class="banner">

            <div class="col col-1">

                <div class="left-nav" >
                    <ul>
                        <li><a href="javascript:showDiv('friendship-invites')">Friendship Invites</a></li>
                        <li><a href="javascript:showDiv('connected-people')">Connected People</a></li>
                        <li><a href="javascript:showDiv('suggested-people')">Suggestions</a></li>
                    </ul>
                </div>
                <div class="content-panel" >
                    <div id="friendship-invites" style="display:block">
                        <h3>Received Invitation</h3>

                        <?php
                        while($requ_by_uid = $received_requ -> fetch_assoc()){
                            $query = "SELECT first_name, last_name, nick_name, email,pro_pic_id FROM profile WHERE user_id = ".$requ_by_uid['request_by'];
                            $result_profile = $connection -> query($query) -> fetch_assoc();

                            $profile_link = "profile_view_other.php?viewable_uid=".$requ_by_uid['request_by'];

                            echo '<a href= '.$profile_link.'>';
                            echo '<div class="people_tile">';
                            if ($result_profile['pro_pic_id'] == null)echo '<img src="images/default_pro_pic.png">';
                            else{
                                echo '<img src="pictures/'.$result_profile['pro_pic_id'].'_pro_pic.jpg">';
                            }
                            echo '<ul>';
                            echo '<li>'.$result_profile["first_name"].' '.$result_profile["last_name"].'</a></li>';
                            if ($result_profile["nick_name"] != null)echo '<li><a href="#">'.$result_profile["nick_name"].'</a></li>';
                            echo '<li>'.$result_profile["email"].'</li>';
                            echo '</ul>';
                            echo '</div>';
                        }
                        ?>

                        <h3>Sent Invitation</h3>

                        <?php
                        while($requ_to_uid = $sent_requ -> fetch_assoc()){

                            $query = "SELECT first_name, last_name, nick_name, email,pro_pic_id FROM profile WHERE user_id = ".$requ_to_uid['request_to'];
                            $result_profile = $connection -> query($query) -> fetch_assoc();

                            $profile_link = "profile_view_other.php?viewable_uid=".$requ_to_uid['request_to'];

                            echo '<a href= '.$profile_link.'>';
                            echo '<div class="people_tile">';
                            if ($result_profile['pro_pic_id'] == null)echo '<img src="images/default_pro_pic.png">';
                            else{
                                echo '<img src="pictures/'.$result_profile['pro_pic_id'].'_pro_pic.jpg">';
                            }
                            echo '<ul>';
                            echo '<li>'.$result_profile["first_name"].' '.$result_profile["last_name"].'</a></li>';
                            if ($result_profile["nick_name"] != null)echo '<li><a href="#">'.$result_profile["nick_name"].'</a></li>';
                            echo '<li>'.$result_profile["email"].'</li>';
                            echo '</ul>';
                            echo '</div>';
                        }
                        ?>

                    </div>

                    <div id="connected-people" style="display:none">
                        <h3>Connected People</h3>
                        <?php
                        while($friend_uid = $friends -> fetch_assoc()){

                            $query = "SELECT first_name, last_name, nick_name, email,pro_pic_id FROM profile WHERE user_id = ".$friend_uid['friend'];
                            $result_profile = $connection -> query($query) -> fetch_assoc();

                            $profile_link = "profile_view_other.php?viewable_uid=".$friend_uid['friend'];

                            echo '<a href= '.$profile_link.'>';
                            echo '<div class="people_tile">';
                                if ($result_profile['pro_pic_id'] == null)echo '<img src="images/default_pro_pic.png">';
                                else{
                                    echo '<img src="pictures/'.$result_profile['pro_pic_id'].'_pro_pic.jpg">';
                                }
                                echo '<ul>';
                                    echo '<li>'.$result_profile["first_name"].' '.$result_profile["last_name"].'</a></li>';
                                    if ($result_profile["nick_name"] != null)echo '<li><a href="#">'.$result_profile["nick_name"].'</a></li>';
                                    echo '<li>'.$result_profile["email"].'</li>';
                                echo '</ul>';
                            echo '</div>';
                        }
                        ?>
                    </div>

                    <div id="suggested-people" style="display:none">
                        <h3>Suggested People</h3>
                        <?php
                        while($friend_s_friends_uid = $friend_s_friends -> fetch_assoc()){

                            $query = "SELECT first_name, last_name, nick_name, email,pro_pic_id FROM profile WHERE user_id = ".$friend_s_friends_uid['frnd_s_frnd'];
                            $result_profile = $connection -> query($query) -> fetch_assoc();

                            $profile_link = "profile_view_other.php?viewable_uid=".$friend_s_friends_uid['frnd_s_frnd'];

                            echo '<a href= '.$profile_link.'>';
                            echo '<div class="people_tile">';
                            if ($result_profile['pro_pic_id'] == null)echo '<img src="images/default_pro_pic.png">';
                            else{
                                echo '<img src="pictures/'.$result_profile['pro_pic_id'].'_pro_pic.jpg">';
                            }
                            echo '<ul>';
                            echo '<li>'.$result_profile["first_name"].' '.$result_profile["last_name"].'</a></li>';
                            if ($result_profile["nick_name"] != null)echo '<li><a href="#">'.$result_profile["nick_name"].'</a></li>';
                            echo '<li>'.$result_profile["email"].'</li>';
                            echo '</ul>';
                            echo '</div>';
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
