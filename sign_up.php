<?php

require_once "config.php";
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errorMessage = "";
    $uname = trim($_POST['txt_uname']);
    $pass = trim($_POST['txt_pwd']);
    $confPass = trim($_POST['txt_conf_pwd']);


    // Prepare a select statement
    $sqlCheckUser = "SELECT userid FROM user WHERE username = ?";

    if ($stmt = $mysqli->prepare($sqlCheckUser)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $param_username);

        // Set parameters
        $param_username = $uname;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // store result
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $errorMessage = "This username is already taken.";
            } else {
                $uname = trim($_POST['txt_uname']);
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        $stmt->close();
    }



    if ($pass != $confPass) {
        $errorMessage = "Password did not match.";
    }


    if ($errorMessage == "") {

        // Prepare an insert statement
        $sql = "INSERT INTO user (username, password) VALUES (?, ?)";

        if ($stmt = $mysqli->prepare($sql)) {

            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ss", $param_username, $param_password);

            // Set parameters
            $param_username = $uname;
            $param_password = $pass;


            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to login page

                header("location: index.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    } else {
        echo $errorMessage;
        $errorMessage = "";
    }
}

// Close statement
$mysqli->close();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>SignUp - RS2</title>
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
                    <span class="login100-form-title p-b-20"> Sign Up </span>

                    <div class="wrap-input100 validate-input m-b-23" data-validate="Username is reauired">
                        <span class="label-input100">Username</span> <input class="input100" type="text" name="txt_uname" placeholder="Type your username"> <span class="focus-input100" data-symbol="&#xf206;"></span>
                    </div>

                    <div class="wrap-input100 validate-input m-b-23" data-validate="Password is required">
                        <span class="label-input100">Password</span> <input class="input100" type="password" name="txt_pwd" placeholder="Type your password"> <span class="focus-input100" data-symbol="&#xf190;"></span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Confirm Password is required">
                        <span class="label-input100">Confirm Password</span> <input class="input100" type="password" name="txt_conf_pwd" placeholder="Type your confirm password"> <span class="focus-input100" data-symbol="&#xf190;"></span>
                    </div>


                    <div class="text-right p-t-8 p-b-15">
                        <!-- <a href="#"> Forgot password? </a> -->
                    </div>

                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <input type="submit" value="Sign-Up" name="but_submit" id="but_submit" class="login100-form-btn" />
                        </div>


                        <div class="flex-col-c p-t-15">

                            <a href="index.php" class="txt2"> Or Login </a>
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