<?php
/**
 * Created by PhpStorm.
 * User: Ashiqur Rahman
 * Date: 13-Dec-16
 * Time: 10:15 PM
 */
ob_start();
session_start();
header('Cache-Control: max-age=900');
if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
}

$uid = $_SESSION['user_id'];
$query_user_id = $uid;
include 'backend/database_connection.php';
include 'backend/database_profile_details.php';

?>

</script>

<script type="text/javascript">

    function showDiv(divName) {
        if(document.getElementById(divName).style.display != "block"){
            document.getElementById(divName).style.display = "block";

            if(divName != 'edit-profile'){
                document.getElementById('edit-profile').style.display = "none";
            }
            if(divName != 'security'){
                document.getElementById('security').style.display = "none";
            }
        }
    }

    function formValidationTwo(formname){

        if (formname == 'update-information'){

            if(document.forms[formname]["fname"].value == ""){
                document.getElementById("fname").focus();
                alert("First name must be filled out");
                return false;
            }
            if(document.forms[formname]["lname"].value == ""){
                document.getElementById("lname").focus();
                alert("Lirst name must be filled out");
                return false;
            }
            if(document.forms[formname]["bdate"].value == ""){
                document.getElementById("bdate").focus();
                alert("Birth date must be filled out");
                return false;
            }

            <?php
            //Receiving all email ids and usernames
            $result = $connection -> query("SELECT email FROM profile WHERE user_id != $uid");
            $emails = [];

            while($row = $result -> fetch_assoc()){
                $emails[] = $row['email'];
            }
            ?>
            var emarray = <?php echo json_encode($emails) ?>;
            if (emarray.includes(document.forms[formname]["email"].value)) {
                alert("This Email is already used by another user! Give another one.");
                document.getElementById("email").focus();
                return false;
            }
        }
    }
</script>


<!DOCTYPE HTML>
<html>
<head>
    <title>Settings</title>
    <link rel="stylesheet" href="sceleton-workspace.css" type="text/css">
</head>
<body>
<div class="container">

    <div class="header-container">

        <header class="header">
            <div class="branding">
                <div class="brand-logo"><img src="images/logoFinal.png"></div>
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
                        <li><a href="javascript:showDiv('edit-profile')">Edit Profile</a></li>
                        <li><a href="javascript:showDiv('security')">Security</a></li>
                    </ul>

                </div>

                <div class="content-panel">
                    <?php include 'backend/update_profile.php'?>
                    <div class="edit-profile" id="edit-profile" style="display:block">

                        <h3>Change Profile Picture</h3>


                        <form action='settings.php' method='POST' enctype='multipart/form-data'>
                            <input type='file' name='pro_pic' value="Choose a photo" accept="image/*"><br>
                            <input type='submit' name='upload_pic' value='Update Profile Picture'>
                        </form>


                        <?php
                        if(isset($_POST['upload_pic'])){

                            $pic_name = $uid.'_pro_pic.jpg';

                            $target = 'pictures/'.$pic_name;
                            $check = move_uploaded_file( $_FILES['pro_pic']['tmp_name'], $target);

                            $query = "UPDATE profile SET pro_pic_id = '$uid' WHERE user_id = $uid";
                            $connection->query($query);

                            if($query){
                                header("Location: settings.php");
                            }
                            else{
                                header("Location: index.php");
                            }
                        }
                        ?>


                        <form id="update-info-form" action="settings.php" method="POST" onsubmit="return(formValidationTwo('update-info-form')">

                            <h3>Basic Information</h3>

                            <label >First Name:</label><input type="text" id="fname" name="fname" value= <?php echo '"'.$allFromProfileRow['first_name'].'"'; ?> >
                            <label >Last Name:</label><input type="text" id="lname" name="lname" value= <?php echo '"'.$allFromProfileRow['last_name'].'"'; ?>>
                            <label >Nick Name:</label><input type="text" id="nname" name="nname" value= <?php echo '"'.$allFromProfileRow['nick_name'].'"'; ?>>
                            <label >Date of Birth:</label><input type="date" placeholder="yyyy-mm-dd eg:2010-12-31" id="bdate" name="bdate" value= <?php echo '"'.$allFromProfileRow['b_date'].'"'; ?>>
                            <label >Gender:</label>

                            <?php
                            if($allFromProfileRow['gender'] == 'Male'){
                                echo '<input type="radio" name="gender" value="Male" checked><label>Male<br></label>';
                                echo '<input type="radio" name="gender" value="Female"><label>Female</label>';
                            }
                            else{
                                echo '<input type="radio" name="gender" value="Male"><label>Male<br></label>';
                                echo '<input type="radio" name="gender" value="Female" checked><label>Female</label>';
                            }
                            ?>

                            <h3>Contact</h3>
                            <label >Email:<br></label><input type="email" id="email" name="email" value=<?php echo '"'.$allFromProfileRow['email'].'"'; ?>>
                            <label >Phone:<br></label><input type="tel" id="phone" name="phone" value=<?php echo '"'.$allFromProfileRow['phone_no'].'"'; ?>>
                            <label >Street No:<br></label><input type="text" id="street-no" name="street-no" value=<?php echo '"'.$allFromProfileRow['street_no'].'"'; ?>>
                            <label >House No:<br></label><input type="text" id="house-no" name="house-no" value=<?php echo '"'.$allFromProfileRow['house_no'].'"'; ?>>
                            <label >District:<br></label><input type="text" id="district" name="district" value=<?php echo '"'.$allFromProfileRow['district'].'"'; ?>>
                            <label >Country:<br></label><input type="text" id="country" name="country" value=<?php echo '"'.$allFromProfileRow['country'].'"'; ?>>


                            <?php
                            $i = 1;
                            while($i<4){
                                echo '<h3>Education '.$i.'</h3>';
                                $touple = $educationresult -> fetch_assoc();
                                echo '<label >Class:</label><input type="text" id="class-'.$i.'" name="class-'.$i.'" value='.'"'.$touple['edu_status'].'">';
                                echo '<label >Institute:</label><input type="text" id="institute-'.$i.'" name="institute-'.$i.'" value='.'"'.$touple['institute'].'">';
                                echo '<label >Passing Year:</label><input type="text" id="pass-year-'.$i.'" name="pass-year-'.$i.'" value='.'"'.$touple['year_of_passing'].'">';
                                $i++;
                            }
                            ?>


                            <h3>Details</h3>
                            <label >Occupation:<br></label><input type="text" id="occupation" name="occupation" value=<?php echo $allFromProfileRow['occupation']; ?>>
                            <label >About Me:</label>
                            <textarea rows="6" cols="50" name="about" id="textarea" form="update-info-form" placeholder="Describe yourself here..."><?php echo $allFromProfileRow['about']; ?></textarea>
                            <label >Favourite Quote:</label>
                            <textarea rows="4" cols="50" name="quote" id="textarea" form="update-info-form" placeholder="Favourite quote..."><?php echo $allFromProfileRow['fav_quote']; ?></textarea>

                            <input type="submit" value="Update Information">

                        </form>

                    </div>

                    <!--- /*****************/ --->

                    <script>
                        function formValidationTwo(divName) {

                            if(document.forms["chngpassform"]["oldpwd"].value == ""){
                                document.getElementById("oldpwd").focus();
                                alert("Enter your password");
                                return false;
                            }
                            if(document.forms["chngpassform"]["newpwd1"].value == ""){
                                document.getElementById("newpwd1").focus();
                                alert("Give a new password");
                                return false;
                            }
                            if(document.forms["chngpassform"]["newpwd2"].value == ""){
                                document.getElementById("newpwd2").focus();
                                alert("Confirm new password");
                                return false;
                            }

                            if(document.forms["chngpassform"]["newpwd1"].value != document.forms["chngpassform"]["newpwd2"].value){
                                document.getElementById("newpwd2").focus();
                                alert("Password Mismatch");
                                return false;
                            }

                        }
                    </script>


                    <!--- /*****************/ --->

                    <div id="security" style="display:none">

                        <form name="chngpassform" action="settings.php" method="POST" onsubmit="return(formValidationTwo(''))">


                            <div class="chngpass" id="chngpass" name="chngpass">

                                <label >Password</label> <input type="password" name="oldpwd" id="oldpwd">
                                <label >New Password: </label> <input type="password" id="newpwd1" name="newpwd1">
                                <label >Repeat: </label> <input type="password" id="newpwd2" name="newpwd2">
                                <input type="submit" value="Update Password" />
                            </div>

                            <?php
                            if (isset($_POST['oldpwd'])){
                                $new_pass = password_hash($_POST['newpwd1'], PASSWORD_DEFAULT);

                                $old_pass = $_POST['oldpwd'];
                                $encrypted_pass = $allFromProfileRow['passcode'];

                                if(password_verify($old_pass, $encrypted_pass)){
                                    $chng_pass_query = "UPDATE profile SET passcode='$new_pass' WHERE user_id = '$uid'";
                                    if($connection -> query($chng_pass_query)){
                                        echo '<h1>Changed</h1>';
                                    }
                                }
                                else{
                                    echo '<h1>Couldn\'t Change</h1>';
                                }
                            }
                            ?>

                        </form>




                        <h3>Secret Questions</h3>
                        <div class="q-and-a">

                            <form name="security-queation-set" action="settings.php" method="POST">
                                <?php
                                $query = "SELECT * FROM secret_questions";
                                $all_questions = $connection -> query($query);
                                $question = $all_questions -> fetch_assoc();

                                echo '<input type="radio" name="question" value="'.$question['question_id'].'" checked><label>'.$question['question'].'</label><br>';

                                while ($question = $all_questions -> fetch_assoc()){
                                    echo '<input type="radio" name="question" value="'.$question['question_id'].'"><label>'.$question['question'].'</label><br>';
                                }
                                ?>
                                <h3>Your Answer</h3>
                                <input type="text" id="answer" name="answer">
                                <text><br>*Answering previously answered question will overwrite previous answer.</text>
                                <input type="submit" value="Security Q&A">
                            </form>


                            <?php
                            if (isset($_POST['answer'])){
                                echo '<h1>Question: '.$_POST['question'].'</h1>';
                                echo '<h1>Ans: '.$_POST['answer'].'</h1>';
                                if ($_POST['answer'] != ''){
                                    $q_id = $_POST['question'];
                                    $ans = $_POST['answer'];

                                    $query = "DELETE FROM secret_answers WHERE user_id = '$uid' AND question_id = '$q_id'";
                                    $connection -> query($query);

                                    $connection -> query("INSERT INTO secret_answers(question_id, user_id, answer) VALUES ('$q_id','$uid','$ans')");
                                    echo '<h5>Question and answer set successful</h5>';
                                }
                                else{
                                    echo '<h5>Provide an answer</h5>';
                                }
                            }
                            else{
                                echo '<h5>Provide an answer</h5>';
                            }
                            ?>


                        </div>

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
