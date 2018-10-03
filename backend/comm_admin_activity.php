<?php
/**
 * Created by PhpStorm.
 * User: Ashiqur Rahman
 * Date: 01-Jan-17
 * Time: 4:03 AM
 */



session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
}

$uid = $_SESSION['user_id'];
$query_user_id = $uid;
//include 'backend/database_profile_details.php';
include 'backend/database_connection.php';

$viewable_comm_id = '';

if (isset($_GET['commname'])) {
    $comm_name = $_GET['commname'];
    $comm_type = $_GET['comm-type'];

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


if(isset($_GET['acpt_id'])){
    $connection -> query("UPDATE membership SET member_type = 2 
                                                  WHERE comm_id = $viewable_comm_id AND user_id = ".$_GET['acpt_id']);
    header("Location: community_view.php?viewable_comm_id='.$viewable_comm_id");
}

?>