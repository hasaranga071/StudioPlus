<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        /* Set up full-screen background image */
        body {
            background-image: url('https://static.vecteezy.com/system/resources/previews/046/897/132/non_2x/a-studio-with-spotlights-and-shooting-equipment-with-beautiful-fog-and-light-photo.jpg'); /* Replace with your image */
            background-size: cover;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        

        .login-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 400px;
            text-align: center;
        }
        
        .login-container h2 {
            margin-bottom: 20px;
        }
        
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
            box-sizing: border-box;
        }
        
        .login-container button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        
        .login-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
</div>

<?php
// Simple login check (hardcoded for demonstration)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Replace 'admin' and 'password' with your desired credentials
    if ($username === 'admin' && $password === 'password') {
        echo "<script>alert('Login successful!');</script>";
    } else {
        echo "<script>alert('Invalid username or password.');</script>";
    }
}
?>

</body>
</html>
