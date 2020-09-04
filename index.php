<?php
include "config.php";

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: sign_up.php");
    exit;
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $uname = trim($_POST['txt_uname']);
    $pass = trim($_POST['txt_pwd']);


    if ($uname != "" && $pass != "") {

        $sql = "SELECT userid, username, password FROM user WHERE username = ? AND password = ?";

        if ($stmt = $mysqli->prepare($sql)) {

            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ss", $param_username, $param_password);

            // Set parameters
            $param_username = $uname;
            $param_password = $pass;

            $stmt->execute();
            // $result = $stmt -> get_result();
            $stmt->store_result();
            $stmt->bind_result($id, $uname, $pass);

            if ($stmt->num_rows > 0) {

                // if (password_verify("Osama1", $hashed_password)) {

                // Store data in session variables
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $uname;

                // Redirect user to welcome page
                header("location: home.php");
                echo ("The user fund");
            } else {
                print_r("The user not Flund");
            }

            // Close statement
            $stmt->close();
        }

        $mysqli->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login - RS2</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="images/icons/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body>

    <div class="limiter">
        <div class="container-login100" style="background-image: url('images/bg-01.jpg');">
            <div class="wrap-login100 p-l-50 p-r-50 p-t-60 p-b-50">
                <form class="login100-form validate-form" action="" method="post">
                    <span class="login100-form-title p-b-20"> Login </span>

                    <div class="wrap-input100 validate-input m-b-23" data-validate="Username is reauired">
                        <span class="label-input100">Username</span> <input class="input100" type="text" name="txt_uname" placeholder="Type your username"> <span class="focus-input100" data-symbol="&#xf206;"></span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                        <span class="label-input100">Password</span> <input class="input100" type="password" name="txt_pwd" placeholder="Type your password"> <span class="focus-input100" data-symbol="&#xf190;"></span>
                    </div>

                    <div class="text-right p-t-8 p-b-15">
                        <!-- <a href="#"> Forgot password? </a> -->
                    </div>

                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <input type="submit" value="Submit" name="but_submit" id="but_submit" class="login100-form-btn" />
                        </div>


                        <div class="flex-col-c p-t-15">

                            <a href="sign_up.php" class="txt2"> Or Sign Up </a>
                        </div>

                </form>
            </div>
        </div>
    </div>


    <div id="dropDownSelect1"></div>

    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
   <script src="vendor/animsition/js/animsition.min.js"></script>
    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/daterangepicker/moment.min.js"></script>
    <script src="vendor/daterangepicker/daterangepicker.js"></script>
    <script src="vendor/countdowntime/countdowntime.js"></script>
    <script src="js/main.js"></script>

</body>

</html>