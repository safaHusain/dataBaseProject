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
                if(document.getElementById('Username').value != ''
                    && document.getElementById('Password').value != ''){
                    
                        document.getElementById('sub').disabled = false;
                    }
            }
            
             
        </script>

<div id="main">
    <div class="wrapper">
        <h2>Login</h2>
        <form action="login.php" method="post">
            <div class="input-box">
                <input type="text" id="Username" name="Username" placeholder="Enter your username" autofocus onblur="isValid(this);" required>
                <Label id = "UsernameErr" style="color:red">
            </div>
            <div class="input-box">
                <input type="password" id="Password" name="Password" placeholder="Enter your password" autofocus onblur="isValid(this);" required>
                <Label id = "PasswordErr" style="color:red">
            </div>
            <div class="input-box button">
                <input type="Submit" id="sub" name="submitted" value="Login" disabled>
            </div>
        </form>
    </div>
</div>

<?php
//include 'debugging.php';

if (isset($_POST['submitted'])) {
    $lgnObj = new Users();
    $username = $_POST['Username'];
    $password = $_POST['Password'];

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