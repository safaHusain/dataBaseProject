<?php
include 'header.php';
// echo '*********' . $_SESSION['uid'];
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
            //obj.style.backgroundColor = "#fff";
            document.getElementById(errField).innerHTML = '';
            valid = true;
            enableButton();
        }

        return valid;
    }

    function enableButton() {
        if (document.getElementById('Username').value != '' &&
            document.getElementById('Password').value != '') {

            document.getElementById('sub').disabled = false;
        }
    }
</script>

<!DOCTYPE html>

<head>
    <style>
        .login_form {
            background-color: #d7d7d9;
            padding: 20px;
        }

        .loginTitle {
            background-color: #d7d7d9;
            color: white;
            padding: 10px;
        }

        .input-box input {
            background-color: #d7d7d9;
            color: balck;
        }

        .input-box input::placeholder {
            color: balck;
        }

        .input-box button {
            background-color: #d7d7d9;
            color: balck;
        }

        .input-box button:disabled {
            background-color: #9e9e9e;
            color: #707070;
        }
    </style>
</head>

<body>
    <div id="main">
        <div class="wrapper">
            <h2 class="loginTitle">Login</h2>
            <form class="login_form" action="login.php" method="post">
                <div class="input-box">
                    <input type="text" id="Username" name="Username" placeholder="Enter your username" autofocus onblur="isValid(this);" required>
                    <label id="UsernameErr" style="color:red"></label>
                </div>
                <div class="input-box">
                    <input type="password" id="Password" name="Password" placeholder="Enter your password" autofocus onblur="isValid(this);" required>
                    <label id="PasswordErr" style="color:red"></label>
                </div>
                <div class="input-box button">
                    <input type="Submit" id="sub" name="submitted" value="Login" disabled>
                </div>
            </form>
        </div>
    </div>
</body>



<?php
//include 'debugging.php';

if (isset($_POST['submitted'])) {
    $lgnObj = new Users();
    $username = trim($_POST['Username']);
    $password = trim($_POST['Password']);

    if ($lgnObj->login($username, $password)) {
        if ($_SESSION['role'] == "admin") {
            header('Location: admin_panel.php');
        } elseif ($_SESSION['role'] == "author") {
            header('Location: author_panel.php');
        } elseif ($_SESSION['role'] == "user") {
            header('Location: index.php');
            alert("you are logged in");
        }
    } else {
        echo $error = 'wrong login values';
    }
}
?>