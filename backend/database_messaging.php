<?php

    $query = "SELECT first_name, last_name, user_id FROM profile WHERE user_id IN 
                      ( SELECT sender_id AS to_id FROM chat WHERE receiver_id = '$uid'
                            UNION
                        SELECT receiver_id AS to_id FROM chat WHERE sender_id = '$uid'
                      ) ORDER BY first_name";
    $all_receiver = $connection -> query($query);

    $query = "SELECT * FROM chat WHERE (sender_id = '$uid' AND receiver_id = '$query_user_id') OR
                                        (sender_id = '$query_user_id' AND receiver_id = '$uid') ORDER BY sending_date_time DESC LIMIT ".$count;
    $chat_result = $connection -> query($query);

    $query = "SELECT first_name, last_name FROM profile WHERE user_id= '$query_user_id'";
    $receiver = $connection -> query($query) ->fetch_assoc();
    $receiver_name = $receiver['first_name'].' '.$receiver['last_name'];

    $query = "SELECT first_name, last_name FROM profile WHERE user_id= '$uid'";
    $user = $connection -> query($query) ->fetch_assoc();
    $user_name = $user['first_name'].' '.$user['last_name'];

?>