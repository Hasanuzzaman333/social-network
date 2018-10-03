<?php
/**
 * Created by PhpStorm.
 * User: Ashiqur Rahman
 * Date: 23-Dec-16
 * Time: 3:22 AM
 */
ob_start();
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
}

$uid = $_SESSION['user_id'];

$messaging_uid = '';
if(isset($_GET['messaging_uid'])){
    if($_GET['messaging_uid'] == $uid){
        header("Location: profile_view.php");
    }
    $messaging_uid = $_GET['messaging_uid'];
}
$user = $uid;
$query_user_id = $messaging_uid;

$count = 10;

if(isset($_GET['count'])){
    $count = $_GET['count'];
}
include 'backend/database_connection.php';
include 'backend/database_messaging.php';
header('Cache-Control: max-age=900');

?>


<!DOCTYPE HTML>
<html>
<head>
    <title>Messages</title>
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
                        while($rcvr = $all_receiver -> fetch_assoc()){
                            $rcvr_name = $rcvr['first_name'].' '.$rcvr['last_name'];
                            $rcvr_uid = $rcvr['user_id'];
                            echo '<li><a href="messaging.php?messaging_uid='.$rcvr_uid.'">'.$rcvr_name.'</a></li>';
                        }
                        ?>

                    </ul>

                </div>


                <div class="content-panel">


                    <?php
                    if($messaging_uid != ''){
                        $path = "messaging.php?messaging_uid=".$messaging_uid."";

                        echo '<div class="msg_type">';
                            echo '<form name="msgform" action= '.$path.' method="post" enctype="multipart/form-data">';
                                echo '<textarea name="msg" id="textarea" placeholder="Type message..." ></textarea><br>';
                                echo '<input type="submit" name="send" value="Send">';
                            echo '</form>';
                        echo '</div>';


                        if(isset($_POST['send']) && isset($_POST['msg']) && $_POST['msg'] != ''){
                            $message = $_POST['msg'];

                            $query = "INSERT INTO chat(sender_id,receiver_id,msg) VALUES('$uid','$messaging_uid','$message')";
                            $connection->query($query);
                            if ($query){
                                header("Location: ".$path."");
                            }
                        }

                        if($messaging_uid != ''){

                            echo '<h3>Conversation between</h3><h3>'.$user_name.' and '.$receiver_name.'</h3>';

                            while($chats = $chat_result -> fetch_assoc()){

                                if($chats['sender_id'] == $uid){
                                    $name = $user_name;
                                    $classname = 'msg0';
                                }
                                else{
                                    $name = $receiver_name;
                                    $classname = 'msg1';
                                }
                                $time = $chats['sending_date_time'];

                                echo '<div class="'.$classname.'">';
                                    echo '<div class="msgsender">'.$name.'<br></div>';
                                    echo '<div class="msgtime">'.$time.'<br></div>';
                                    echo '<div class="msg">'.$chats['msg'].'<br></div>';
                                echo '</div>';

                            }
                            $count = $count+10;
                            //echo '<h1>uid'+$messaging_uid+' uid</h1>';

                            echo '<h5><a href="messaging.php?messaging_uid='.$messaging_uid.' & count='.$count.'">Load more</a></h5>';

                        }
                    }
                    else{
                        echo '<h1>Choose a recipient first</h1>';
                    }
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

