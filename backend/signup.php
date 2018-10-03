<?php
/**
 * Created by PhpStorm.
 * User: Ashiqur Rahman
 * Date: 13-Dec-16
 * Time: 1:43 PM
 */
    include 'database_connection.php';

    session_start();
    if(isset($_SESSION['user_id'])){
        //Already Logged In
        echo "Logout First";
        header("Location: ../profile_view.php");
    }
    else{
        header("Location: ../index.php");
    }

?>

<?php

    //Initialising raw values
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $pwd = $_POST['pwd1'];
    $gender = $_POST['gender'];
    $bdate = $_POST['bdate'];

    echo $bdate;
    //Encrypting Password
    $pwd_encrypted = password_hash($pwd, PASSWORD_DEFAULT);


    $newEntryQuery = "INSERT INTO profile(first_name,last_name,email,passcode,gender,b_date) VALUES('$fname','$lname','$email','$pwd_encrypted','$gender','$bdate')";

    if($connection -> query($newEntryQuery)){
        //echo "Sign up Successful.";
        $IDQuery = "SELECT user_id FROM profile WHERE email = '$email'";
        $result = $connection -> query($IDQuery);
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['user_id'];
        header("Location: ../profile_view.php");
    }
    else{
        echo "Sign up Failed";
        header("Location: ../index.php");
    }

?>
