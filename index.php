<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {

        $username = $_POST["username"];
        $password = $_POST["password"];

        $server = "localhost";
        $db = "resolutions";

        try 
        {
            $conn = new mysqli($server, $username, $password, $db); 
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
    <title>Login</title>
</head>
<body>

    <h2>Login</h2>

    <?php if (isset($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

</body>
</html>
