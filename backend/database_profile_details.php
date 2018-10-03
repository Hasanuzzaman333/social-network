<?php
/**
 * Created by PhpStorm.
 * User: Ashiqur Rahman
 * Date: 19-Dec-16
 * Time: 11:39 PM
 */

$query = "SELECT * FROM profile WHERE user_id = '$query_user_id'";
$result = $connection -> query($query);
$allFromProfileRow = $result -> fetch_assoc();


$query = "SELECT * FROM education WHERE user_id = '$query_user_id' ORDER BY year_of_passing";
$educationresult = $connection -> query($query);

?>