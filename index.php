<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {

        $username = $_POST["username"];
        $password = $_POST["password"];

        $server = "localhost";

        try 
        {
            $connection = new mysqli($server, $username, $password);
            session_start(); 
            
            $_SESSION["username"] = $username;
            $_SESSION["password"] = $password;
            $_SESSION["server"] = $server;

            header("Location: menu.php");
            exit();
        }
        catch(Exception $exception)
        {
            $error = "An error occured: " . $exception->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 25px;
            background-color: #f0f0f0;
        }
    </style>

</head>
<body>

    <h2>Login</h2>

    <?php if (isset($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
            <button type="submit" class="btn btn btn-outline-primary">Login</button>
    </form>

</body>
</html>
