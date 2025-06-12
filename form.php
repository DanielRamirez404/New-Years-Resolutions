<?php

    session_start();
    $postAction = htmlspecialchars($_SERVER["PHP_SELF"]);    
    
    try
    {
        $connection = $_SESSION["con"];

        $name = null;
        $description = null;

        if (isset($_SESSION["modify_id"]))
        {
            $id = $_SESSION["modify_id"]; 
            $searchQuery = "SELECT name, description FROM Resolution WHERE id=$id";
            $result = $connection->query($searchQuery)->fetch_row();
            $name = $result[0];
            $description = $result[1]; 
        }


        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            if (isset($_POST['go_back']))
            {
                header("Location: menu.php");
                exit();
            }
            else if (isset($_POST['save']))
            {

                $preparedQuery = null;

                if (isset($_SESSION["modify_id"]))
                {
                    $id = $_SESSION["modify_id"]; 
                    $preparedQuery = $connection->prepare("UPDATE Resolution SET name = ?, description = ? WHERE id = $id");
                }
                else 
                {
                    $preparedQuery = $connection->prepare("INSERT INTO Resolution (name, description) VALUES (?, ?)");
                }

                $preparedQuery->bind_param("ss", $_POST['name'], $_POST['description']);
                $preparedQuery->execute();
              
                header("Location: menu.php");
                exit();
            }
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

    <form class="card p-3" method="POST" action="<?php echo $postAction ?>">
        <div class="input-group mb-3">
        <input name="name" type="text" class="form-control" placeholder="Resolución" aria-label="Username" aria-describedby="basic-addon1" required value="<?php echo $name; ?>">
        </div>
        <div class="input-group">
            <span class="input-group-text">Descripción</span>
            <textarea name="description" class="form-control" aria-label="Descripción"><?php echo $description;?></textarea>
        </div>
        <input type="hidden" name="save">
        <button style="width: 100px" type="submit" class="mt-3 mx-auto btn btn btn-outline-primary">Guardar</button>
    </form>
    <form method="POST" action="<?php echo $postAction ?>">
        <input type="hidden" name="go_back">
        <button style="position: relative; left: 115px; bottom: 70px; width: 100px" type="submit" class="mt-3 mx-auto btn btn btn-outline-secondary">Atrás</button>
    </form>

</body>
</html>
