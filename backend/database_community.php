<?php

$query = "SELECT member_type FROM `membership` WHERE comm_id = $viewable_comm_id AND user_id = $uid";
$member_type = $connection -> query($query) -> fetch_assoc();
$member_type = $member_type['member_type'];


$query = "SELECT name, type, logo_pic_id, TIMESTAMPDIFF(MONTH, creation_date_time, CURDATE()) AS age_month
          FROM community WHERE comm_id = $viewable_comm_id";
$comm_details = $connection -> query($query) -> fetch_assoc();

$query = "SELECT COUNT(*) AS members 
          FROM membership WHERE comm_id = $viewable_comm_id AND (member_type = 1 OR member_type = 2) ";
$no_of_members = $connection -> query($query) -> fetch_assoc();

$query = "SELECT * FROM profile WHERE user_id IN 
          (SELECT user_id FROM membership WHERE comm_id = $viewable_comm_id AND member_type = 1)";
$admin_profile_rows = $connection -> query($query);

$query = "SELECT * FROM profile WHERE user_id IN 
          (SELECT user_id FROM membership WHERE comm_id = $viewable_comm_id AND member_type = 2)";
$member_profile_rows = $connection -> query($query);

$query = "SELECT * FROM profile WHERE user_id IN 
          (SELECT user_id FROM membership WHERE comm_id = $viewable_comm_id AND member_type = 4)";
$banned_profile_rows = $connection -> query($query);

$query = "SELECT * FROM profile WHERE user_id IN 
          (SELECT user_id FROM membership WHERE comm_id = $viewable_comm_id AND member_type = 5)";
$request_profile_rows = $connection -> query($query);

$query ="SELECT user_id,first_name,last_name,postdate_time,post_id,post,is_community_post,pic_id,comm_id
		FROM ((((profile NATURAL JOIN posting) NATURAL JOIN posts) NATURAL JOIN post_pictures)NATURAL JOIN posts_in_community)
		WHERE comm_id = $viewable_comm_id AND is_community_post=1 ORDER BY postdate_time DESC";

$community_post = $connection->query($query);

?>