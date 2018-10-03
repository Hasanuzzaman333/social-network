
<?php
/**
 * Created by PhpStorm.
 * User: Ashiqur Rahman
 * Date: 14-Dec-16
 * Time: 12:51 AM
 */
    session_start();
    session_destroy();

    header("Location: ../index.php");

?>