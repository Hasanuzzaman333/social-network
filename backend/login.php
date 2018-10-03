<?php
/**
 * Created by PhpStorm.
 * User: Ashiqur Rahman
 * Date: 13-Dec-16
 * Time: 11:18 PM
 */
    include 'database_connection.php';

    session_start();

    if(isset($_SESSION['user_id'])){
        //Already Logged In
        header("Location: ../profile_view.php");
    }

    $email = $_POST['email'];
    $pwd = $_POST['pwd'];

    $login_query = "SELECT passcode,user_id FROM profile WHERE email = '$email'";
    $result = $connection -> query($login_query);
    $row = $result -> fetch_assoc();

    $encrypted_pass = $row['passcode'];

    $bool = password_verify($pwd, $encrypted_pass);

    if($bool){
        //log in successful
        $_SESSION['user_id'] = $row['user_id'];
        echo "<h1>seessss: ".$_SESSION['user_id']."</h1>";
        header("Location: ../profile_view.php");
    }
    else{
        //echo "log in error";
        header("Location: ../index.php");
    }
?>


