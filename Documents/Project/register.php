<?php
include 'header.php';
?>

<script>
    function isValid(obj) {
        var errField = obj.id + 'Err';
        var valid = false;

        var value = obj.value.trim();

        if (value == '') {

            document.getElementById(errField).innerHTML = obj.id + ' field may not be blank';
            document.getElementById('sub').disabled = true;
        } else {
            obj.style.backgroundColor = "#fff";
            document.getElementById(errField).innerHTML = '';
            valid = true;
            enableButton();
        }

        return valid;
    }

    function enableButton() {
        if (document.getElementById('UserName').value != '' &&
            document.getElementById('Email').value != '' &&
            document.getElementById('Password').value != '') {

            document.getElementById('sub').disabled = false;
        }
    }
</script>

<html>

<head>
    <link rel="stylesheet" href="style.css" type="text/css" media="screen" />
    <style>
        /* Your existing styles */

        /* Add the new CSS code below */
        #main {
            background-color: #707070;
        }

        .wrapper {
            margin: 0 auto;
            position: relative;
            max-width: 430px;
            width: 100%;
            background: #88898c;
            padding: 34px;
            border-radius: 6px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }

        .wrapper h2 {
            position: relative;
            font-size: 22px;
            font-weight: 600;
            color: #333;
        }

        .wrapper h2::before {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            height: 3px;
            width: 28px;
            border-radius: 12px;
            background: #4070f4;
        }

        .wrapper form {
            margin-top: 30px;
        }

        .wrapper form .input-box {
            height: 52px;
            margin: 18px 0;
        }

        form .input-box input {
            height: 100%;
        }

        .text {
            margin-top: 20px;
            text-align: center;
            color: #333;
        }

        .text h3 {
            font-size: 16px;
        }

        .text h3 a {
            color: #4070f4;
            text-decoration: none;
        }

        .text h3 a:hover {
            text-decoration: underline;
        }

        /* end of new CSS code */

        /* Your existing styles */

        /* Add your existing styles here */
        /* Your existing styles */
    </style>
</head>

<body>
    <div id="main">
        <div class="wrapper">
            <h2>Registration</h2>
            <form action="register.php" method="post">
                <div class="input-box">
                    <input type="text" id="UserName" name="UserName" placeholder="Enter your username" autofocus onblur="isValid(this);" required>
                    <Label id="UserNameErr" style="color:red"></Label>
                </div>
                <div class="input-box">
                    <input type="text" id="Email" name="Email" placeholder="Enter your email" autofocus onblur="isValid(this);" required>
                    <Label id="EmailErr" style="color:red"></Label>
                </div>
                <div class="input-box">
                    <input type="password" id="Password" name="Password" placeholder="Create password" autofocus onblur="isValid(this);" required>
                    <Label id="PasswordErr" style="color:red"></Label>
                </div>
                <div class="input-box button">
                    <input type="Submit" id="sub" name="submitted" value="Register Now" disabled>
                </div>
                <div class="text">
                    <h3>Already have an account? <a href="login.php">Login now</a></h3>
                </div>
            </form>
        </div>
    </div>
</body>

</html>


<?php

//include 'debugging.php';

if (isset($_POST['submitted'])) {
    $user = new Users;
    $user->setEmail(trim($_POST['Email']));
    $user->setPassword(trim($_POST['Password']));
    $user->setUsername(trim($_POST['UserName']));

    if ($user->initWithUsername()) {
        if ($user->registerUser()) {
            echo '<p style="color:green"> registered successfully </p>';
        } else {
            echo '<p style="color:red"> registration not successfull </p>';
        }
    } else {
        echo '<p style="color:red"> username already exists </p>';
    }
}
?>