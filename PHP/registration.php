<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../css/registration.css">
</head>
<body>
    <div class="container">
        <h1>Sign Up</h1>
        <form action="registration.php" method="post">
            <label for="firstname">First name:</label>
            <input type="text" id="firstname" name="firstname" required>
            <br><br>

            <label for="lastname">Last name:</label>
            <input type="text" id="lastname" name="lastname" required>
            <br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <br><br>

            <label for="pass">Password:</label>
            <input type="password" id="password" name="pass"   required>
            <span class="toggle-password" onclick="togglePassword()">Show Password</span>

            <br><br>

            <input type="submit" value="Sign Up">
        </form>
        <p>Already have an account? <a class="login-link" href="login.php">Log In</a></p>
    </div>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
          const form = document.querySelector('form');
          const password = document.getElementById('password');
          const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    password.addEventListener('input', function() {
        if (!passwordPattern.test(password.value)) {
            password.setCustomValidity('Password should contain at least 8 characters , at least one uppercase letter, one lowercase letter, one number, and one special character.');
        } else {
            password.setCustomValidity('');
        }
    });

    form.addEventListener('submit', function(event) {
        if (!password.checkValidity()) {
            event.preventDefault();
        }
    });
});
function togglePassword() {
            var passwordField = document.getElementById("password");
            var toggleButton = document.querySelector(".toggle-password");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleButton.textContent = "Hide Password";
            } else {
                passwordField.type = "password";
                toggleButton.textContent = "Show Password";
            }
        }
    </script>
</html>

<?php
 include "SQL_connection.php" ;

 if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["firstname"]) && isset($_POST["lastname"]) && isset($_POST["email"]) && isset($_POST["pass"])) {
    $first_name = $_POST["firstname"];
    $last_name = $_POST["lastname"];
    $email = $_POST["email"];

    if (strpos($email, "@") === false) {// questo e per vedere se la email e valida o no nell controllo server side
        echo "Email non valida.";
        exit();
    }
    
$password = $_POST["pass"];
$passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';

if (!preg_match($passwordPattern, $password)) {// questo e per vedere se la password e valida o no nell controllo server side
    echo "password non valido.";
    exit();
}   
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);// se il password coretto allora lo criptiamo con la funzione password_hash

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    try {
        $sql = "INSERT INTO user (firstname, lastname , email, password) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "ssss", $first_name, $last_name, $email, $hashedPassword);

        if (mysqli_stmt_execute($stmt) ) {
            header("Location: login.php");
            exit;
        } else {
            echo "Registration failed.";
        }
    } catch (mysqli_sql_exception $ex) {
        echo "name or email already exists.";
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
 }
?>