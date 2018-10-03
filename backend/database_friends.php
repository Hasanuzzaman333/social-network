<?php
/**
 * Created by PhpStorm.
 * User: Ashiqur Rahman
 * Date: 19-Dec-16
 * Time: 11:39 PM
 */

$query = "SELECT request_to,r_date_time FROM friendship_requests WHERE request_by = '$query_user_id' ORDER BY r_date_time DESC";
$sent_requ = $connection -> query($query);


$query = "SELECT request_by,r_date_time FROM friendship_requests WHERE request_to = '$query_user_id' ORDER BY r_date_time DESC";
$received_requ = $connection -> query($query);


$query = "SELECT user2_id as friend,f_date_time FROM friendship WHERE friendship.user1_id = $query_user_id
                UNION
          SELECT user1_id as friend,f_date_time FROM friendship WHERE friendship.user2_id = $query_user_id";
$friends = $connection -> query($query);


$query_frnds_id = "SELECT user2_id as friends_id FROM friendship WHERE friendship.user1_id = $query_user_id
                  UNION
                  SELECT user1_id as friends_id FROM friendship WHERE friendship.user2_id = $query_user_id";

$query = "SELECT user2_id as frnd_s_frnd from friendship WHERE user2_id != $query_user_id AND user1_id IN ( ".$query_frnds_id." ) AND user2_id NOT IN (".$query_frnds_id.")
                      UNION
          SELECT user1_id as frnd_s_frnd from friendship WHERE user1_id != $query_user_id AND user2_id IN (".$query_frnds_id.") AND user1_id NOT IN (".$query_frnds_id.")";
$friend_s_friends = $connection -> query($query);

?>