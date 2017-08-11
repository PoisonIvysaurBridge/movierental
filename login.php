<!DOCTYPE html>
<?php require_once 'ti.php' ?>
<?php
    session_start();
    if (isset($_SESSION['user']))
        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/home.php");
?>
<html>
<header>
    <title>MOVIE RENTAL SYSTEM</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
    html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif;}
    /*body{background-image:url("http://moshi-koshi.com/images/24japan-16fukuoka-128streets_5-1920x.jpg");}*/
    #header {
        margin:0;
        position:relative;
        left: 0;
        top:0;
        width: 100%;
        height: 80px;
        position: center;
        background-color:black;
        margin: 0;
        border-radius: 0;
    }
    </style>
    <?php startblock('style') ?>
        <style>
            h3, h4, a,p{
                text-align: center;
                border-radius: 10px;
            }
            div.outer{
                border-radius: 40px;
                text-align:center;
                /*background-color: rgba(0,200,200,.5);*/
            }
            input, a{
                border-radius: 10px;
                padding: 5px 8px;
            }
            div.w3-container{
                border-radius: 40px;
            }
            div.w3-main{ /* THIS OVERRIDES THE w3-main CLASS IN THE W3.CSS */
                margin: 0 -10px 0 0;
            }
        </style>
    <?php endblock() ?>
</header>

<body class="w3-light-grey">
    
    <!-- Top container -->
    <div id="header" class="w3-bar w3-top w3-black w3-large" style="z-index:4">
        <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button>
        <span class="w3-bar-item w3-center" style="display:block;margin-left:auto; margin:right;">
            <h1 style="display:block;margin-left:auto; margin:right;">Movie Rental System</h1>
            
        </span>
        <img src="http://www.knotandgownfilms.com/wp-content/uploads/2016/03/Film-reel-public-domain.png" style="height:90px">
    </div>
    
    <!-- !PAGE CONTENT! -->
    <div class="w3-main">
        <?php startblock('content') ?>
            <?php

                //session_start();
                require_once('mysql_connect.php');
                /*
                    if (isset($_SESSION['badlogin'])){
                        if ($_SESSION['badlogin']>=5)
                            header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/blocked.php");
                    }
                */
                $message = NULL;
                if (isset($_POST['submit'])){

                    //if(empty($_POST['username']) || empty($_POST['password'])){
                        if (empty($_POST['username'])){
                            $_SESSION['username']=FALSE;
                            $message.='<p>You forgot to enter your username!</p>';
                        }
                        else {
                            $_SESSION['username']=$_POST['username'];
                        }

                        if (empty($_POST['password'])){
                            $_SESSION['password']=FALSE;
                            $message.='<p>You forgot to enter your password!</p>';
                        }
                        else {
                            $_SESSION['password']=$_POST['password'];
                        }

                        $query='SELECT STAFF_ID, STORE_ID, FIRST_NAME, LAST_NAME, ACTIVE, USERNAME FROM STAFF WHERE USERNAME="'.$_SESSION["username"].'" AND PASSWORD = password("'.$_SESSION["password"].'") AND ACTIVE = 1';
                        $result=mysqli_query($dbc,$query);
                        $row=mysqli_fetch_array($result,MYSQLI_ASSOC);

                        if (!$result) {
                            echo mysqli_error($dbc);
                        }

                        if (!empty($row)) {
                            $_SESSION['user'] = $row['STAFF_ID'];
                            $_SESSION['storeID'] = $row['STORE_ID'];
                            header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/home.php");
                        }

                        else {
                            $message.='<h5 style="color:red;text-align:center;">Your username and password didn\'t match. Please try again.</h5>';
                            if (isset($_SESSION['badlogin']))
                                $_SESSION['badlogin']++;
                            else
                                $_SESSION['badlogin']=1;
                        }

                }/*End of main Submit conditional*/

                echo $message;
            ?>

            <div class="outer">
                <h1>Welcome!</h1>
                <h5>Please login to continue.</h5>

                <div class="w3-container w3-dark-grey w3-padding-32" style="width: 500px;margin: 20px auto auto auto">
                    <div class="w3-container" align="center">
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <!--
                            {% csrf_token %}
                                {% if form.errors %}
                                    <h5 style="color:red;">Your username and password didn't match. Please try again.</h5>
                                {% endif %}
                            -->
                                <h2>User Login</h2><hr>
                                <h4>User Name: <input type="text" name="username" size="20" maxlength="30" required value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>"/><h4>
                                <h4>Password: <input type="password" name="password" size="20" maxlength="20" required/></h4>
                                <h4><h4>
                                <input type="submit" name=submit value="Login" />
                                <!--<input type="hidden" name="next" value="{{ next }}" />-->
                            </form>
                        <!--
                            <hr>OR &nbsp 
                            <a class="btn btn-default" href="registration.php" style="border-radius:10px;">Register!</a>
                        -->
                    </div>
                </div>
            </div>
            
        <?php endblock() ?>
        <!-- Footer -->
        <footer>
            <p align=center>IVAPOS</a></p>
        </footer>
    <!-- End page content -->
    </div>

</body>
</html>





