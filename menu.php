<?php

    session_start();

    $connection = null;

    try
    {
        $connection = new mysqli($_SESSION["server"], $_SESSION["username"], $_SESSION["password"], "Resolutions");

        $tableCreationQuery = <<<QUERY
            CREATE TABLE IF NOT EXISTS Resolution(
                id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
                name VARCHAR(30) NOT NULL,
                description VARCHAR(150) NOT NULL,
                creationDate DATE NOT NULL default NOW()
            );
            QUERY;

        $connection->query($tableCreationQuery);
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

    <h2 style="font-weight: bold;">New year's resolutions</h2>

    <?php if (isset($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php elseif ($connection->query("SELECT COUNT(*) FROM Resolution")->numrows === 0) : ?>
    <h4 style="color: gray;">No registered data. Please, feel free to add some entries</h4>
    <?php else: ?>
        <br>
        <div class="row row-cols 2">
        <?php
            $result = $connection->query("SELECT name, description, creationDate FROM Resolution ORDER BY creationDate");
            while ($entry = $result->fetch_row()) {
                echo <<<ENTRY
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title" style="font-weight: bold;">$entry[0]</h5>
                        <h6 class="card-subtitle mb-2 text-muted">$entry[2]</h6>
                        <p class="card-text">$entry[1]</p>
                        <button type="button" class="btn btn-outline-primary">Edit</button>
                        <button type="button" class="btn btn-outline-danger">Delete</button>
                    </div>
                </div>
                ENTRY;
            }    
      ?>
      <div>
        

    <?php endif; ?>

</body>
</html>
