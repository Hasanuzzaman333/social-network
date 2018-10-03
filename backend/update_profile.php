
<?php
//Update Pro_pic


if(isset($_POST['fname'])){

//Initialising raw values
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $nname = $_POST['nname'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $bdate = $_POST['bdate'];
    $phone = $_POST['phone'];
    $street_no = $_POST['street-no'];
    $house_no = $_POST['house-no'];
    $district = $_POST['district'];
    $country = $_POST['country'];

    $occupation = $_POST['occupation'];
    $about = $_POST['about'];
    $quote = $_POST['quote'];

    $class_1 = $_POST['class-1'];
    $class_2 = $_POST['class-2'];
    $class_3 = $_POST['class-3'];

    $institute_1 = $_POST['institute-1'];
    $institute_2 = $_POST['institute-2'];
    $institute_3 = $_POST['institute-3'];

    $pass_year_1 = $_POST['pass-year-1'];
    $pass_year_2 = $_POST['pass-year-2'];
    $pass_year_3 = $_POST['pass-year-3'];

    $connection -> query("DELETE FROM education WHERE user_id = $uid");

    if ($class_1 != "" & $institute_1 != "" & $pass_year_1 != ""){
        $connection -> query("INSERT INTO education(user_id,edu_status,institute,year_of_passing) VALUES('$uid','$class_1','$institute_1','$pass_year_1')");
    }
    if ($class_2 != "" & $institute_2 != "" & $pass_year_2 != ""){
        $connection -> query("INSERT INTO education(user_id,edu_status,institute,year_of_passing) VALUES('$uid','$class_2','$institute_2','$pass_year_2')");
    }
    if ($class_3 != "" & $institute_3 != "" & $pass_year_3 != ""){
        $connection -> query("INSERT INTO education(user_id,edu_status,institute,year_of_passing) VALUES('$uid','$class_3','$institute_3','$pass_year_3')");
    }

    $update_query= "UPDATE profile 
                SET first_name='$fname',last_name='$lname',nick_name='$nname',
                email='$email',gender='$gender',b_date='$bdate',phone_no='$phone',
                street_no='$street_no',house_no='$house_no',district='$district',
                country='$country',occupation='$occupation',about='$about',fav_quote='$quote'
                WHERE user_id = $uid";

    if ($connection -> query($update_query)){
        //push notify
        echo '<div id="edit-profile" style="display:none">';
        header("Location: settings.php#edit-profile");
    }
    else{
        header("Location: profile_view.php");
    }
}
?>


