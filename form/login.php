<?php
    session_start();
    if(isset($_SESSION["user"])){
        header(("Location: index.php"));
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="registration.css">
</head>

<body>
    <div class="container">
        <?php 
            if(isset($_POST["login"])){
                $email = $_POST["email"];
                $password = $_POST["password"];
                require_once "database.php";
                $sql = "SELECT * FROM users WHERE email = '$email'";
                $result = mysqli_query($conn, $sql);
                $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
                if($user){
                    if(password_verify($password, $user["password"])){
                        session_start();
                        $_SESSION["user"] = "yes";
                        header("Location: index.php");
                        die();
                    }else{
                        echo "<div class='alert alert-danger'>Password does not match!</div>";
                    }
                }else{
                    echo "<div class='alert alert-danger'>Email does not Exists!</div>";
                }
            }
        ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <input type="email" name="email" class="form-control" id="" placeholder="Enter your Email">
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" id="" placeholder="Password">
            </div>
            <div class="form-btn">
                <input type="submit" name="login" class="btn btn-primary" id="" value="Login">
            </div>
        </form>
        <div><p>Not registered yet <a href="registration.php">Register Here</a></p></div>
    </div>
</body>

</html>