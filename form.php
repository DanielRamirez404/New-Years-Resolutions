<?php

    session_start();
    $postAction = htmlspecialchars($_SERVER["PHP_SELF"]);    
    
    try
    {
        $connection = new mysqli($_SESSION["server"], $_SESSION["username"], $_SESSION["password"], "Resolutions");

        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
        }
    }
    catch(Exception $exception)
    {
        $error = "An error occured: " . $exception->getMessage();
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

    <h2 style="font-weight: bold; margin-bottom: 25px;">Resolución</h2>

    <?php if (isset($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form class="card p-3">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Resolución" aria-label="Username" aria-describedby="basic-addon1">
        </div>
        <div class="input-group">
            <span class="input-group-text">Descripción</span>
            <textarea class="form-control" aria-label="Descripción"></textarea>
        </div>
            <button style="width: 100px" type="submit" class="mt-3 mx-auto btn btn btn-outline-primary">Guardar</button>
    </form>

</body>
</html>
