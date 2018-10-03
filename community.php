<?php
ob_start();
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
}


$uid = $_SESSION['user_id'];
$query_user_id = $uid;
include 'backend/database_connection.php';
include 'backend/database_profile_details.php';

header('Cache-Control: max-age=900');
?>



<script type="text/javascript">

    function showDiv(divName) {
        if(document.getElementById(divName).style.display != "block"){
            document.getElementById(divName).style.display = "block";

            if(divName != 'my-communities'){
                document.getElementById('my-communities').style.display = "none";
            }
            if(divName != 'comm-create'){
                document.getElementById('comm-create').style.display = "none";
            }
            if(divName != 'joined-communities'){
                document.getElementById('joined-communities').style.display = "none";
            }
        }
    }

</script>

<!DOCTYPE HTML>
<html>
<head>
    <title>Community</title>
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
                        <li><a href="javascript:showDiv('my-communities')">My Communities</a></li>
                        <li><a href="javascript:showDiv('joined-communities')">Joined Communities</a></li>
                        <li><a href="javascript:showDiv('comm-create')">Create Community</a></li>
                    </ul>
                </div>
                <div class="content-panel" >

                    <div id="my-communities" style="display:block">
                        <h3>My Communities</h3>

                        <?php
                            $query = "SELECT * FROM community WHERE comm_id IN 
                                                (SELECT comm_id FROM membership WHERE user_id = '$uid' AND member_type = 1)";
                            $my_communities_rows = $connection -> query($query);

                            while($my_community = $my_communities_rows -> fetch_assoc()){

                                $name = $my_community['name'];
                                $type = $my_community['type'];
                                $logo_id = $my_community['logo_pic_id'];
                                $comm_link = "community_view.php?viewable_comm_id=".$my_community['comm_id'];

                                $query = "SELECT COUNT(user_id) as members FROM membership WHERE (member_type = 1 OR member_type = 2) AND comm_id = ".$my_community['comm_id'];
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

                    <div id="joined-communities" style="display:none">
                        <h3>Joined Communities</h3>
                        <?php
                        $query = "SELECT * FROM community WHERE comm_id IN 
                                                (SELECT comm_id FROM membership WHERE user_id = '$uid' AND member_type = 2)";
                        $joined_communities_rows = $connection -> query($query);

                        while($my_community = $joined_communities_rows -> fetch_assoc()){

                            $name = $my_community['name'];
                            $type = $my_community['type'];
                            $logo_id = $my_community['logo_pic_id'];
                            $comm_link = "community_view.php?viewable_comm_id=".$my_community['comm_id'];

                            $query = "SELECT COUNT(user_id) as members FROM membership WHERE (member_type = 1 OR member_type = 2) AND comm_id = ".$my_community['comm_id'];
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

                    <script>
                        function formValidation2() {
                            if(document.forms["commcreate"]["commname"].value == ""){
                                document.getElementById("commname").focus();
                                alert("Give a Community Name");
                                return false;
                            }
                        }
                    </script>
                    <div class="comm-create" id="comm-create" name="comm-create" style=" display:none">

                        <h3>Create a New Community</h3>

                        <form name="commcreate" action="community_view.php" method="POST" onsubmit="return(formValidation2())">
                            <input type="text" name="commname" id="commname" placeholder="Community Name">
                            <br><br><label>Choose Community Type: </label><br>

                            <br><input type="radio" name="comm-type" value="Education" checked><label>Education</label><br>
                            <input type="radio" name="comm-type" value="Friends" ><label>Friends</label><br>
                            <input type="radio" name="comm-type" value="Family" ><label>Family</label><br>
                            <input type="radio" name="comm-type" value="Entertainment"><label>Entertainment</label><br>
                            <input type="radio" name="comm-type" value="Business" ><label>Business</label><br>
                            <input type="radio" name="commtype" value="Company"><label>Company</label><br>
                            <input type="radio" name="comm-type" value="Institution"><label>Institution</label><br>
                            <input type="radio" name="comm-type" value="Photography"><label>Photography</label><br>
                            <input type="radio" name="comm-type" value="Other"><label>Other</label><br>

                            <br><input type="submit" value="Create" />
                        </form>

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




<!---




--->