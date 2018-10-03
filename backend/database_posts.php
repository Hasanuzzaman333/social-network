
<?php
/**
 * Created by PhpStorm.
 * User: Ashiqur Rahman
 * Date: 19-Dec-16
 * Time: 11:54 PM
 */

$query = "SELECT * FROM posting NATURAL JOIN posts
          WHERE user_id = '$query_user_id' AND is_community_post= -1 ORDER BY postdate_time DESC";
$post_posting = $connection->query($query);

$query = "SELECT postdate_time, post, pic_id, post_id
          FROM ((posting NATURAL JOIN posts)NATURAL JOIN post_pictures)
          WHERE user_id = '$query_user_id' AND is_community_post= -1 ORDER BY postdate_time DESC";
$post_posting = $connection->query($query);

$query = "SELECT first_name,last_name,user_id,postdate_time,post_id,post,pic_id
		  FROM (((profile NATURAL JOIN posting)NATURAL JOIN posts)NATURAL JOIN post_pictures)
		  WHERE profile.user_id in
          (SELECT user2_id as friends_id FROM friendship WHERE friendship.user1_id = $query_user_id UNION
		  SELECT user1_id as friends_id FROM friendship WHERE friendship.user2_id = $query_user_id) ORDER BY postdate_time DESC";

$timeline_post = $connection->query($query);



$query ="SELECT user_id,first_name,last_name,postdate_time,post_id,post,is_community_post,pic_id,comm_id,member_type
		FROM (((((profile NATURAL JOIN posting) NATURAL JOIN posts) NATURAL JOIN post_pictures)NATURAL JOIN posts_in_community)NATURAL JOIN membership)
		WHERE (member_type = 1 OR member_type = 2) AND is_community_post=1 ORDER BY postdate_time DESC";

$only_community_post = $connection->query($query);




$query ="(SELECT user_id,first_name,last_name,postdate_time,post_id,post,pic_id
		FROM (((((profile NATURAL JOIN posting) NATURAL JOIN posts) NATURAL JOIN post_pictures)NATURAL JOIN posts_in_community)NATURAL JOIN membership)
		WHERE (member_type = 1 OR member_type = 2) AND is_community_post=1) UNION 
		
		(SELECT user_id,first_name,last_name,postdate_time,post_id,post,pic_id
		  FROM (((profile NATURAL JOIN posting)NATURAL JOIN posts)NATURAL JOIN post_pictures)
		  WHERE (user_id=$query_user_id) OR profile.user_id in
          (SELECT user2_id as friends_id FROM friendship WHERE friendship.user1_id = $query_user_id UNION
		  SELECT user1_id as friends_id FROM friendship WHERE friendship.user2_id = $query_user_id)) ORDER BY postdate_time DESC	";

$all_post = $connection->query($query);



?>
