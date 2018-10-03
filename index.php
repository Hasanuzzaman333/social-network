<?php
    include 'backend/database_connection.php';
    header('Cache-Control: max-age=900');
    session_start();
    if(isset($_SESSION['user_id'])){
        //Already Logged In
        header("Location: profile_view.php");
    }

?>


<?php
    //Receiving all email ids and usernames
    $result = $connection -> query("SELECT email FROM profile");
    $emails = [];

    while($row = $result -> fetch_assoc()){
        $emails[] = $row['email'];
    }

?>



<html>
<head>
    <title>Cyber Society</title>
    <link rel="stylesheet" href="sceleton-workspace.css" type="text/css">
</head>
<body>
<div class="container">

    <div class="header-container">
        <header class="header" style="height: 100px; padding-top: 0">
            <div class="logo"> <img src="images/logoFinal.png"> </div>
            <div class="top_info"></div>
            <h1 style="padding-top: 15px">Cyber Society</h1>
        </header>
    </div>

    <section class="content_area">
        <div class="banner">

            <div class="col col-1 slide" style="padding-top:10px; background-color:#fff;font-family:'Open Sans',sans-serif,arial,helvetica,verdana">

                <script src="js/jquery-1.11.3.min.js" type="text/javascript" data-library="jquery" data-version="1.11.3"></script>
                <script src="js/jssor.slider-22.0.15.mini.js" type="text/javascript" data-library="jssor.slider.mini" data-version="22.0.15"></script>
                <script type="text/javascript">
                    jQuery(document).ready(function ($) {

                        var jssor_1_SlideoTransitions = [
                            [{b:0,d:600,y:-290,e:{y:27}}],
                            [{b:0,d:1000,y:185},{b:1000,d:500,o:-1},{b:1500,d:500,o:1},{b:2000,d:1500,r:360},{b:3500,d:1000,rX:30},{b:4500,d:500,rX:-30},{b:5000,d:1000,rY:30},{b:6000,d:500,rY:-30},{b:6500,d:500,sX:1},{b:7000,d:500,sX:-1},{b:7500,d:500,sY:1},{b:8000,d:500,sY:-1},{b:8500,d:500,kX:30},{b:9000,d:500,kX:-30},{b:9500,d:500,kY:30},{b:10000,d:500,kY:-30},{b:10500,d:500,c:{x:87.50,t:-87.50}},{b:11000,d:500,c:{x:-87.50,t:87.50}}],
                            [{b:0,d:600,x:410,e:{x:27}}],
                            [{b:-1,d:1,o:-1},{b:0,d:600,o:1,e:{o:5}}],
                            [{b:-1,d:1,c:{x:175.0,t:-175.0}},{b:0,d:800,c:{x:-175.0,t:175.0},e:{c:{x:7,t:7}}}],
                            [{b:-1,d:1,o:-1},{b:0,d:600,x:-570,o:1,e:{x:6}}],
                            [{b:-1,d:1,o:-1,r:-180},{b:0,d:800,o:1,r:180,e:{r:7}}],
                            [{b:0,d:1000,y:80,e:{y:24}},{b:1000,d:1100,x:570,y:170,o:-1,r:30,sX:9,sY:9,e:{x:2,y:6,r:1,sX:5,sY:5}}],
                            [{b:2000,d:600,rY:30}],
                            [{b:0,d:500,x:-105},{b:500,d:500,x:230},{b:1000,d:500,y:-120},{b:1500,d:500,x:-70,y:120},{b:2600,d:500,y:-80},{b:3100,d:900,y:160,e:{y:24}}],
                            [{b:0,d:1000,o:-0.4,rX:2,rY:1},{b:1000,d:1000,rY:1},{b:2000,d:1000,rX:-1},{b:3000,d:1000,rY:-1},{b:4000,d:1000,o:0.4,rX:-1,rY:-1}]
                        ];

                        var jssor_1_options = {
                            $AutoPlay: true,
                            $Idle: 2000,
                            $CaptionSliderOptions: {
                                $Class: $JssorCaptionSlideo$,
                                $Transitions: jssor_1_SlideoTransitions,
                                $Breaks: [
                                    [{d:2000,b:1000}]
                                ]
                            },
                            $ArrowNavigatorOptions: {
                                $Class: $JssorArrowNavigator$
                            },
                            $BulletNavigatorOptions: {
                                $Class: $JssorBulletNavigator$
                            }
                        };

                        var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);


                        function ScaleSlider() {
                            var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
                            if (refSize) {
                                refSize = Math.min(refSize, 600);
                                jssor_1_slider.$ScaleWidth(refSize);
                            }
                            else {
                                window.setTimeout(ScaleSlider, 30);
                            }
                        }
                        ScaleSlider();
                        $(window).bind("load", ScaleSlider);
                        $(window).bind("resize", ScaleSlider);
                        $(window).bind("orientationchange", ScaleSlider);

                    });
                </script>
                <style>

                    .jssorb01 {
                        position: absolute;
                    }
                    .jssorb01 div, .jssorb01 div:hover, .jssorb01 .av {
                        position: absolute;
                        /* size of bullet elment */
                        width: 12px;
                        height: 12px;
                        filter: alpha(opacity=70);
                        opacity: .7;
                        overflow: hidden;
                        cursor: pointer;
                        border: #000 1px solid;
                    }
                    .jssorb01 div { background-color: gray; }
                    .jssorb01 div:hover, .jssorb01 .av:hover { background-color: #d3d3d3; }
                    .jssorb01 .av { background-color: #fff; }
                    .jssorb01 .dn, .jssorb01 .dn:hover { background-color: #555555; }

                    .jssora02l, .jssora02r {
                        display: block;
                        position: absolute;
                        /* size of arrow element */
                        width: 55px;
                        height: 55px;
                        cursor: pointer;
                        background: url('slide_show_images/a02.png') no-repeat;
                        overflow: hidden;
                    }
                    .jssora02l { background-position: -3px -33px; }
                    .jssora02r { background-position: -63px -33px; }
                    .jssora02l:hover { background-position: -123px -33px; }
                    .jssora02r:hover { background-position: -183px -33px; }
                    .jssora02l.jssora02ldn { background-position: -3px -33px; }
                    .jssora02r.jssora02rdn { background-position: -63px -33px; }
                    .jssora02l.jssora02lds { background-position: -3px -33px; opacity: .3; pointer-events: none; }
                    .jssora02r.jssora02rds { background-position: -63px -33px; opacity: .3; pointer-events: none; }
                </style>
                <div id="jssor_1" style="position: relative; margin: 0 auto; top: 0px; left: 0px; width: 600px; height: 300px; overflow: hidden; visibility: hidden;">

                    <div data-u="loading" style="position: absolute; top: 0px; left: 0px;">
                        <div style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
                        <div style="position:absolute;display:block;background:url('slide_show_images/loading.gif') no-repeat center center;top:0px;left:0px;width:100%;height:100%;"></div>
                    </div>
                    <div data-u="slides" style="cursor: default; position: relative; top: 0px; left: 0px; width: 600px; height: 300px; overflow: hidden;">

                        <div data-p="112.50" style="display:none;">
                            <img data-u="image" src="slide_show_images/001.jpg" />
                            <div data-u="caption" data-t="4" style="position:absolute;top:30px;left:30px;width:350px;height:30px;z-index:0;background-color:rgba(235,81,0,0.6);font-size:20px;color:#ffffff;line-height:30px;text-align:center;">Explore the Massive Virtual World</div>
                        </div>

                        <div data-p="112.50">
                            <img data-u="image" src="slide_show_images/002.jpg" />
                            <div data-u="caption" data-t="0" style="position:absolute;top:320px;left:30px;width:350px;height:30px;z-index:0;background-color:rgba(235,81,0,0.5);font-size:20px;color:#ffffff;line-height:30px;text-align:center;">Have Fun With Friends</div>
                        </div>

                        <div data-p="112.50" style="display:none;">
                            <img data-u="image" src="slide_show_images/003.jpg" />
                            <div data-u="caption" data-t="2" style="position:absolute;top:30px;left:-380px;width:350px;height:30px;z-index:0;background-color:rgba(235,81,0,0.5);font-size:20px;color:#ffffff;line-height:30px;text-align:center;">Make Your Own Community</div>
                        </div>

                        <a data-u="any" href="http://www.jssor.com" style="display:none">Image Slider</a>
                        <div data-p="112.50" style="display:none;">
                            <img data-u="image" src="slide_show_images/004.jpg" />
                            <div data-u="caption" data-t="5" style="position:absolute;top:30px;left:600px;width:350px;height:30px;z-index:0;background-color:rgba(235,81,0,0.5);font-size:20px;color:#ffffff;line-height:30px;text-align:center;">May The Sky Be Your Limit</div>
                        </div>
                        <div data-p="112.50" style="display:none;">
                            <img data-u="image" src="slide_show_images/005.jpg" />
                            <div data-u="caption" data-t="6" style="position:absolute;top:30px;left:30px;width:350px;height:30px;z-index:0;background-color:rgba(235,81,0,0.5);font-size:20px;color:#ffffff;line-height:30px;text-align:center;">Join With US</div>
                        </div>


                    </div>
                    <!-- Bullet Navigator -->
                    <div data-u="navigator" class="jssorb01" style="bottom:16px;right:16px;">
                        <div data-u="prototype" style="width:12px;height:12px;"></div>
                    </div>
                    <!-- Arrow Navigator -->
                    <span data-u="arrowleft" class="jssora02l" style="top:0px;left:8px;width:55px;height:55px;" data-autocenter="2"></span>
                    <span data-u="arrowright" class="jssora02r" style="top:0px;right:8px;width:55px;height:55px;" data-autocenter="2"></span>
                </div>
                <!-- #endregion Jssor Slider End -->




            </div>

            <div class="col col-2 sign">


                <script>
                    function formValidation(divName) {
                        var emarray = <?php echo json_encode($emails) ?>;

                        if(document.getElementById(divName).style.display != "block" && divName == 'login'){
                            document.getElementById('signup').style.display = "none";
                            document.getElementById(divName).style.display = "block";
                            return false;
                        }
                        else if(document.getElementById(divName).style.display != "block" && divName == 'signup'){
                            document.getElementById('login').style.display = "none";
                            document.getElementById(divName).style.display = "block";
                            return false;
                        }

                        if (document.getElementById('login').style.display == "block" ) {
                            if(document.forms["loginform"]["email"].value == ""){
                                document.getElementById("email").focus();
                                alert("Email must be filled out");
                                return false;
                            }
                            if(document.forms["loginform"]["pwd"].value == ""){
                                document.getElementById("pwd").focus();
                                alert("Password must be filled out");
                                return false;
                            }
                        }
                        else if (document.getElementById('signup').style.display == "block" ) {
                            if(document.forms["signupform"]["fname"].value == ""){
                                document.getElementById("fname").focus();
                                alert("First Name must be filled out");
                                return false;
                            }
                            if(document.forms["signupform"]["lname"].value == ""){
                                document.getElementById("lname").focus();
                                alert("Last Name must be filled out");
                                return false;
                            }
                            if(document.forms["signupform"]["email"].value == ""){
                                document.getElementById("email").focus();
                                alert("Email must be filled out");
                                return false;
                            }
                            if (emarray.includes(document.forms["signupform"]["email"].value)) {
                                alert("This Email is already used! Give another one.");
                                document.getElementById("email").focus();
                                return false;
                            }
                            if(document.forms["signupform"]["bdate"].value == ""){
                                document.getElementById("bdate").focus();
                                alert("Birth date must be filled out");
                                return false;
                            }
                            if(document.forms["signupform"]["pwd1"].value == ""){
                                document.getElementById("pwd1").focus();
                                alert("Password must be filled out");
                                return false;
                            }
                            if(document.forms["signupform"]["pwd2"].value == ""){
                                document.getElementById("pwd2").focus();
                                alert("Confirm password");
                                return false;
                            }

                            if(document.forms["signupform"]["pwd1"].value != document.forms["signupform"]["pwd2"].value){
                                document.getElementById("pwd2").focus();
                                alert("Password Mismatch");
                                return false;
                            }
                        }
                    }

                </script>
                <form name="loginform" action="backend/login.php" method="POST" onsubmit="return(formValidation('login'))">


                    <div class="login" id="login" name="login" style=" display:block">

                        <label >Email</label> <input type="email" name="email" id="email">
                        <label >Password</label> <input type="password" name="pwd" id="pwd">
                        <text >Forgot Password? <a href="password_recovery.php">Click Here</a></text>

                    </div>
                    <input type="submit" value="Login" />

                </form>

                <form name="signupform" action="backend/signup.php" method="POST" onsubmit="return(formValidation('signup'))">
                    <div class="signup" id="signup" style="display:none">

                        <label >First Name</label><input type="text" id="fname" name="fname">

                        <label >Last Name</label><input type="text" id="lname" name="lname">

                        <label >Email</label><input type="email" id="email" name="email">

                        <label >Password</label><input type="password" id="pwd1" name="pwd1">
                        <label >Confirm Password</label><input type="password" id="pwd2" name="pwd2">

                        <input type="radio" name="gender" value="Male" checked><label>Male</label><br>
                        <input type="radio" name="gender" value="Female"><label>Female</label><br>

                        <label >Date of Birth</label><input type="date" placeholder="yyyy-mm-dd eg:2010-12-31" id="bdate" name="bdate">

                    </div>
                    <input type="submit"  value="Signup"/>
                </form>

            </div>

        </div>

    </section>
    <footer class="footer">Under Copyright<sup style="font-size: 5px">TM</sup></footer>
</div>
</body>
</html>﻿
