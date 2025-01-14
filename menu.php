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

    <h2>New year's resolutions</h2>

    <?php if (isset($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php elseif ($connection->query("SELECT COUNT(*) FROM Resolution")->numrows == 0) : ?>
        <h4 style="color: gray;">No registered data. Please, feel free to add some entries</h4>
    <?php else: ?>
        <table>
            <tr>
                <th>name</th>
            </tr>
            <tr>
                <td>DATE NOT NULL default NOW()</td>
            </tr>
            <tr>
                <td>PRIMARY KEY</td>
            </tr>
        </table>
    <?php endif; ?>

</body>
</html>
