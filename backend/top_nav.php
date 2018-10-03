

<?php
    $tooltip = "Search for people, communities, posts or emails";
    echo '<ul>';
        echo '<li><a href="timeline.php">Timeline</a></li>';
        echo '<li><a href="profile_view.php">Profile</a></li>';
        echo '<li><a href="people.php">People</a></li>';
        echo '<li><a href="messaging.php">Message</a></li>';
        echo '<li><a href="community.php">Community</a></li>';
        echo '<li>';
            echo '<div id="search" name="search">';
                echo '<form name="searchform" class="searchform" action="search.php" method="POST" onsubmit="return(formValidation())">';
                    echo '<input title="'.$tooltip.'" type="search" name="search" placeholder="Search" ><input type="submit" value="">';
                echo '</form>';
            echo '</div>';
        echo '</li>';

    echo '</ul>'

?>

<script>
    function formValidation() {
        if(document.forms["searchform"]["search"].value == ""){
            document.getElementById("search").focus();
            /*alert("Enter search key");*/
            return false;
        }
    }
</script>


