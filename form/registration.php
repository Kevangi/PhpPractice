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
    <title>Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="registration.css">
</head>
<body>
    <div class="container">
        <?php
            if(isset($_POST["submit"])){
                $firstName = $_POST["firstname"];
                $lastName = $_POST["lastname"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                $passwordRepeat = $_POST["pass"];

                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                $errors = array();

                if(empty($firstName) OR empty($lastName) OR empty($email) OR empty($password) OR empty($passwordRepeat)){
                    array_push($errors, "All fields are required");
                }
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    array_push($errors, "Email is not valid");
                }
                if(strlen($password)<8){
                    array_push($errors, "Password must be at least 8 character long");
                }
                if($password !== $passwordRepeat){
                    array_push($errors, "password does not match");
                }

                require_once "database.php";
                $sql = "SELECT * FROM users WHERE email = '$email'";
                $result = mysqli_query($conn, $sql);
                $rowcount = mysqli_num_rows($result);
                if($rowcount>0){
                    array_push($errors, "Email already exists");
                }
                if(count($errors)>0){
                    foreach($errors as $error){
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                }else{
                    
                    $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES ( ?, ?, ?, ?)";
                    $stmt = mysqli_stmt_init($conn);
                    $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
                    if($prepareStmt){
                        mysqli_stmt_bind_param($stmt,"ssss", $firstName, $lastName, $email, $passwordHash);
                        mysqli_stmt_execute($stmt);
                        echo "<div class='alert alert-success'>You are registered successfully</div>";
                    }else{
                        die("Something went worng");
                    }
                }
            }
        ?>

        <form action="registration.php" method="post">
            <div class="form-group">
                <input type="text" name="firstname" class="form-control" id="" placeholder="Enter your first name">
            </div>
            <div class="form-group">
                <input type="text" name="lastname" class="form-control" id="" placeholder="Enter your last name">
            </div>
            <div class="form-group">
                <input type="email" name="email" class="form-control" id="" placeholder="Enter your Email">
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" id="" placeholder="Password">
            </div>
            <div class="form-group">
                <input type="password" name="pass" class="form-control" id="" placeholder="Repeat your Password">
            </div>
            <div class="form-btn">
                <input type="submit" name="submit" class="btn btn-primary" id="" value="Register">
            </div>
        </form>
        <div><p>Already registered<a href="login.php">Login Here</a></p></div>
    </div>
</body>
</html>