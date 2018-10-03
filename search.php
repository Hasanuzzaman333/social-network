<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
}

$uid = $_SESSION['user_id'];
$query_user_id = $uid;
include 'backend/database_connection.php';
include 'backend/database_profile_details.php';
include 'backend/database_friends.php';

header('Cache-Control: max-age=900');
?>

<?php
    //Search Handle
    if (isset($_POST['search'])){

        $search_key = $_POST['search'];

        //Name Search
        $name_key = preg_replace("#[^0-9a-z]#i","",$search_key);
        $search_query = "SELECT first_name, last_name, nick_name, email, user_id, pro_pic_id FROM profile 
                        WHERE first_name LIKE '%$name_key%' 
                        OR last_name LIKE '%$name_key%' 
                        OR nick_name LIKE '%$name_key%'";
        $search_result_name = $connection -> query($search_query);

        $search_query = "SELECT * FROM community WHERE name LIKE '%$name_key%'";
        $search_result_comm = $connection -> query($search_query);

        $email_key = explode('@', $search_key);
        $email_key = $email_key[0];
        $search_query = "SELECT first_name, last_name, nick_name, email, user_id, pro_pic_id FROM profile 
                        WHERE email LIKE '%$email_key%'";
        $search_result_email = $connection -> query($search_query);

    }
    else{
        header("Location: profile_view.php");
    }
?>

<script type="text/javascript">

    function showDiv(divName) {
        if(document.getElementById(divName).style.display != "block"){
            document.getElementById(divName).style.display = "block";

            if(divName != 'people'){
                document.getElementById('people').style.display = "none";
            }
            if(divName != 'email'){
                document.getElementById('email').style.display = "none";
            }
            if(divName != 'community'){
                document.getElementById('community').style.display = "none";
            }
        }
    }

</script>

<!DOCTYPE HTML>
<html>
<head>
    <title>Search</title>
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

                <div class="left-nav">
                    <ul>
                        <li><a href="javascript:showDiv('people')">People</a></li>
                        <li><a href="javascript:showDiv('email')">Email</a></li>
                        <li><a href="javascript:showDiv('community')">Community</a></li>
                    </ul>
                </div>
                <div class="content-panel" >
                    <div id="people" style="display:block">

                        <?php
                        echo "<h3>People Name Result: ".$name_key."</h3>";
                        while($result_profile_rows = $search_result_name -> fetch_assoc()){
                            echo '<h1>Result:'.$result_profile_rows['first_name'].'</h1>';

                            $profile_link = "profile_view_other.php?viewable_uid=".$result_profile_rows['user_id'];

                            echo '<a href= '.$profile_link.'>';
                                echo '<div class="srch_rslt_div">';
                                    if ($result_profile_rows['pro_pic_id'] == null)echo '<img src="images/default_pro_pic.png">';
                                    else{
                                        echo '<img src="pictures/'.$result_profile_rows['pro_pic_id'].'_pro_pic.jpg">';
                                    }
                                    echo '<ul>';
                                        echo '<li>'.$result_profile_rows["first_name"].' '.$result_profile_rows["last_name"].'</a></li>';
                                        if ($result_profile_rows["nick_name"] != null)echo '<li><a href="#">'.$result_profile_rows["nick_name"].'</a></li>';
                                        echo '<li>'.$result_profile_rows["email"].'</li>';
                                    echo '</ul>';
                            echo '</div>';
                        }
                        ?>
                    </div>


                    <div id="email" style="display:none">

                        <?php
                        echo "<h3>Email Result: ".$name_key."</h3>";
                        while($result_profile_rows = $search_result_email -> fetch_assoc()){
                            echo '<h1>Result:'.$result_profile_rows['first_name'].'</h1>';

                            $profile_link = "profile_view_other.php?viewable_uid=".$result_profile_rows['user_id'];

                            echo '<a href= '.$profile_link.'>';
                            echo '<div class="srch_rslt_div">';
                            if ($result_profile_rows['pro_pic_id'] == null)echo '<img src="images/default_pro_pic.png">';
                            else{
                                echo '<img src="pictures/'.$result_profile_rows['pro_pic_id'].'_pro_pic.jpg">';
                            }
                            echo '<ul>';
                            echo '<li>'.$result_profile_rows["first_name"].' '.$result_profile_rows["last_name"].'</a></li>';
                            if ($result_profile_rows["nick_name"] != null)echo '<li><a href="#">'.$result_profile_rows["nick_name"].'</a></li>';
                            echo '<li>'.$result_profile_rows["email"].'</li>';
                            echo '</ul>';
                            echo '</div>';
                        }
                        ?>
                    </div>

                    <div id="community" style="display:none">

                        <?php
                        echo "<h3>Community Name Result: ".$name_key."</h3>";
                        while($result_comm = $search_result_comm -> fetch_assoc()){
                            echo '<h1>Result:'.$result_comm['name'].'</h1>';

                            $name = $result_comm['name'];
                            $type = $result_comm['type'];
                            $logo_id = $result_comm['logo_pic_id'];
                            $comm_link = "community_view.php?viewable_comm_id=".$result_comm['comm_id'];

                            $query = "SELECT COUNT(user_id) as members FROM membership 
                                      WHERE (member_type = 1 OR member_type = 2) AND comm_id = ".$result_comm['comm_id'];
                            $members = $connection -> query($query) -> fetch_assoc();


                            echo '<a href= '.$comm_link.'>';
                            echo '<div class="comm-div">';
                            if ($logo_id == null)echo '<img src="images/comm_logo_default.png">';
                            else{
                                echo '<img src="pictures/'.$logo_id.'_logo_pic.jpg">';
                            }
                            echo '<ul>';
                            echo '<li>Name: '.$name.'</a></li>';
                            echo '<li>Type: '.$type.'</a></li>';
                            echo '<li>Members: '.$members['members'].'</a></li>';
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