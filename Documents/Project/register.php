<?php
include 'header.php';
?>

<script>
            function isValid(obj){
                var errField = obj.id + 'Err';
                var valid = false;
                
                var value = obj.value.trim();
                
                if (value == ''){
                    obj.style.backgroundColor = "yellow";
                    document.getElementById(errField).innerHTML = obj.id + ' field may not be blank';
                    document.getElementById('sub').disabled = true;
                }else{
                    obj.style.backgroundColor = "#fff";
                    document.getElementById(errField).innerHTML = '';
                    valid = true;
                    enableButton();
                }
                
                return valid;
            }
            
            function enableButton(){
                if(document.getElementById('UserName').value != ''
                    && document.getElementById('Email').value != ''
                    && document.getElementById('Password').value != ''){
                    
                        document.getElementById('sub').disabled = false;
                    }
            }
            
             
        </script>

        <html>
            <head>
                <link rel="stylesheet" href="style.css" type="text/css" media="screen" />
            </head>
<div id="main">
    <div class="wrapper">
        <h2>Registration</h2>
        <form action="register.php" method="post">
            <div class="input-box">
                <input type="text" id="UserName" name="UserName" placeholder="Enter your username" autofocus onblur="isValid(this);" required>
                <Label id = "UserNameErr" style="color:red">
            </div>
            <div class="input-box">
                <input type="text" id="Email" name="Email" placeholder="Enter your email" autofocus onblur="isValid(this);" required>
                <Label id = "EmailErr" style="color:red">
            </div>
            <div class="input-box">
                <input type="password" id="Password" name="Password" placeholder="Create password" autofocus onblur="isValid(this);" required>
                <Label id = "PasswordErr" style="color:red">
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
        </html>>

<?php

//include 'debugging.php';

if (isset($_POST['submitted'])) {
    $user = new Users;
    $user->setEmail($_POST['Email']);
    $user->setPassword($_POST['Password']);
    $user->setUsername($_POST['UserName']);

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