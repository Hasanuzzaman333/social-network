<?php
/**
 * Created by PhpStorm.
 * User: Ashiqur Rahman
 * Date: 13-Dec-16
 * Time: 10:18 PM
 */
ob_start();
include 'backend/database_connection.php';
header('Cache-Control: max-age=900');
session_start();
if(isset($_SESSION['user_id'])){
    //Already Logged In
    header("Location: profile_view.php");
}

?>


<?php
//Receiving all email ids and usernames
$result = $connection -> query("SELECT email FROM profile");
$emails = [];

while($row = $result -> fetch_assoc()){
    $emails[] = $row['email'];
}

?>



<html>
<head>
    <title>Password Recover</title>
    <link rel="stylesheet" href="sceleton-workspace.css" type="text/css">
</head>
<body>
<div class="container">

    <div class="header-container">
        <header class="header" style="height: 100px; padding-top: 0">
            <div class="logo"><a href="index.php"><img src="images/logoFinal.png"></a></div>
            <h1 style="padding-top: 15px">Cyber Society</h1>
        </header>
    </div>

    <section class="content_area">
        <div class="banner">

            <div class="col col-1">

                <div class="left-nav">

                </div>
                <div class="content-panel" >

                    <div id="mail" style=" display:block">
                        <script>
                            function formValidation() {

                                if (document.forms["emailenter"]["email"].value == "") {
                                    document.getElementById("email").focus();
                                    alert("Email must be filled out");
                                    return false;
                                }
                            }

                            function formValidationTwo() {

                                if (document.forms["answerenter"]["answer"].value == "") {
                                    document.getElementById("answer").focus();
                                    alert("Type your answer");
                                    return false;
                                }
                            }

                            function formValidationThree() {

                                if (document.forms["passenter"]["pass1"].value == "") {
                                    document.getElementById("pass1").focus();
                                    alert("Type new password");
                                    return false;
                                }
                                else if (document.forms["passenter"]["pass2"].value == "") {
                                    document.getElementById("pass2").focus();
                                    alert("Re-type new password");
                                    return false;
                                }
                                else if (document.forms["passenter"]["pass1"].value != document.forms["passenter"]["pass2"].value) {
                                    document.getElementById("pass2").focus();
                                    alert("Password mismatch");
                                    return false;
                                }
                            }
                        </script>

                        <form name="emailenter" action="password_recovery.php" method="POST" onsubmit="return(formValidation())">
                            <label >Email: </label> <input type="email" name="email" id="email">
                            <input type="submit" value="Submit" />
                        </form>

                        <?php

                            if(isset($_POST['email'])){
                                $email = $_POST['email'];
                                $query = "SELECT user_id FROM profile WHERE email = '$email'";
                                $uid = $connection -> query($query) -> fetch_assoc();
                                $uid = $uid['user_id'];

                                if($uid == null){
                                    echo 'Sorry, the email you provided doesnot exist in our database.';
                                }
                                else{
                                    $query = "SELECT * FROM secret_questions WHERE question_id IN 
                                              (SELECT question_id FROM secret_answers WHERE user_id = '$uid')";

                                    $questions = $connection -> query($query);
                                    if(mysqli_num_rows($questions) == 0){
                                        echo 'Sorry, you did not answered any security question.';
                                    }
                                    else{

                                        echo '<h3>Choose a Question</h3>';
                                        $question = $questions -> fetch_assoc();
                                        echo '<form name="answerenter" action="password_recovery.php?uid='.$uid.'" method="POST" onsubmit="return(formValidationTwo())">';

                                        echo '<input type="radio" name="question" value="'.$question['question_id'].'" checked>'.$question['question'].'<br>';
                                        while($question = $questions -> fetch_assoc()){
                                            echo '<input type="radio" name="question" value="'.$question['question_id'].'">'.$question['question'].'<br>';
                                        }

                                        echo '<h3>Your Answer</h3>';
                                        echo '<input type="text" name="answer" id="answer" placeholder="Enter your answer here"><br>';
                                        echo '<input type="submit" value="Submit" />';
                                        echo '</form>';
                                    }
                                }
                            }

                            else if(isset($_GET['uid'])){
                                $uid = $_GET['uid'];
                                if(isset($_POST['answer'])) {
                                    $answer = $_POST['answer'];
                                    $question_id = $_POST['question'];

                                    $result = $connection -> query("SELECT * FROM secret_answers WHERE question_id = '$question_id' AND user_id = '$uid' AND answer = '$answer'");

                                    if (mysqli_num_rows($result) == 0){
                                        echo 'Sorry, your answer did not match with the saved answer.';
                                    }
                                    else{
                                        echo '<form name="passenter" action="password_recovery.php?uid='.$uid.'" method="POST" onsubmit="return(formValidationThree())">';
                                            echo '<label >New Password</label><br><input type="password" name="pass1" id="pass1">';
                                            echo '<br><label >Repeat New Password</label><br><input type="password" name="pass2" id="pass2"><br>';
                                            echo '<input type="submit" value="Reset" />';
                                        echo '</form>';
                                    }
                                }
                                else if(isset($_POST['pass1'])){
                                    $pwd = $_POST['pass1'];
                                    $pwd_encrypted = password_hash($pwd, PASSWORD_DEFAULT);
                                    $connection -> query("UPDATE profile SET passcode = '$pwd_encrypted' WHERE user_id = '$uid'");
                                    header("Location: index.php");
                                }
                            }


                        ?>
                    </div>

                </div>

            </div>
            <div class="col col-2">
            </div>

        </div>

    </section>
    <footer class="footer">Under Copyright<sup style="font-size: 5px">TM</sup></footer>
</div>
</body>
</html>﻿

