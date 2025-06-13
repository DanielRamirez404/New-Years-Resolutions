<?php

    include("include/depurar.php");
    include("control/conexion.php");

    session_start();
    $postAction = htmlspecialchars($_SERVER["PHP_SELF"]);    

    try
    {
        $connection = get_session_connexion();

        $tableCreationQuery = <<<QUERY
            CREATE TABLE IF NOT EXISTS Resolution(
                id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
                name VARCHAR(30) NOT NULL,
                description VARCHAR(150) NOT NULL,
                creationDate DATE NOT NULL default NOW()
            );
            QUERY;

            $connection->query($tableCreationQuery);

        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            if (isset($_POST['delete_id']))
            {
                $id = depurar($_POST['delete_id']);
                $deletionQuery = "DELETE FROM Resolution WHERE id=$id";

                if ($connection->query($deletionQuery)) 
                    $alert = "Tu entrada fue eliminada correctamente";
                else 
                    $alert = "Hubo algún error eliminando tu entrada";
            }
            else if (isset($_POST['search'])) 
            {
                $searchText = depurar($_POST['search']);

                if ($searchText === "")
                    unset($_SESSION["search_query"]);
                else 
                    $_SESSION["search_query"] = $searchText;
            }
            else if (isset($_POST['create']))
	    {
                unset($_SESSION["modify_id"]);
                header("Location: form.php"); 
                exit();
            }
            else if (isset($_POST['modify_id']))
            {
                $_SESSION["modify_id"] = depurar($_POST['modify_id']);
                header("Location: form.php");
                exit();
            }
        }
    }
    catch(Exception $exception)
    {
	    header("location: error.php");
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

    <h2 style="font-weight: bold;">Resoluciones de 2025</h2>

    <?php if (isset($alert)) : ?>
        <script>alert($alert)</script>
    <?php endif; ?>

    <?php if (isset($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php elseif ($connection->query("SELECT COUNT(*) FROM Resolution")->fetch_row()[0] == 0) : ?>
        <h4 style="color: gray;">No registered data. Please, feel free to add some entries</h4>
        <div class="col">
        <div class="row mx-auto" style="gap: 5px;">
            <form style="width: 85%" method="POST" action="<?php echo $postAction ?>" class="my-2 row g-1">
            <input style="width: 190px;" type="text" name="search" class="mx-auto form-control" placeholder="Introduce tu búsqueda" value="<?php echo (isset($_SESSION['search_query'])) ? $_SESSION['search_query'] : ""; ?>">
            </form>
            <form style="width: 15%" method="POST" action="<?php echo $postAction ?>" class="my-2 row g-1">
                <input type="hidden" name="create">
                <button style="max-width: 35px; max-height: 35px; font-weight: bold" class="btn btn-secondary" type="submit">+</button>
            </form>
        </div>
    <?php else: ?>
        <div class="col">
        <div class="row mx-auto">
            <form style="width: 85%" method="POST" action="<?php echo $postAction ?>" class="my-2 row g-1">
            <input style="width: 190px;" type="text" name="search" class="mx-auto form-control" placeholder="Introduce tu búsqueda" value="<?php echo (isset($_SESSION['search_query'])) ? $_SESSION['search_query'] : ""; ?>">
                <button style="max-width: 35px; max-height: 35px; font-weight: bold" class="mx-auto btn btn-primary" type="submit">Ir</button>
            </form>
            <form style="width: 15%" method="POST" action="<?php echo $postAction ?>" class="my-2 row g-1">
                <input type="hidden" name="create">
                <button style="max-width: 35px; max-height: 35px; font-weight: bold" class="btn btn-secondary" type="submit">+</button>
            </form>
        </div>
        <?php

            $searchQuery = "SELECT id, name, description, creationDate FROM Resolution";
            $orderStatement = " ORDER BY creationDate";
            $matchCondition = " WHERE CONCAT_WS(' ', name, description) LIKE ?";        

            $result = null;

            if (!isset($_SESSION["search_query"]))
            {
                $result = $connection->query($searchQuery . $orderStatement);
            }
            else 
            {
                $preparedQuery = $connection->prepare($searchQuery . $matchCondition . $orderStatement);
                $searchTerm = '%' . $_SESSION["search_query"] . '%'; 
                $preparedQuery->bind_param("s", $searchTerm);
                $preparedQuery->execute();
                $result = $preparedQuery->get_result();
            }

            $entries = 0;

            while ($entry = $result->fetch_row())
            {
                echo <<<ENTRY
                <div class="mx-auto card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title" style="font-weight: bold;">$entry[1]</h5>
                        <h6 class="card-subtitle mb-2 text-muted">$entry[3]</h6>
                        <p class="card-text">$entry[2]</p>
                        <div class="row">
                        <form class="col" method="POST" action="$postAction">
                            <input type="hidden" name="modify_id" value="$entry[0]">
                            <button style="width: 100%;" type="submit" class="btn btn-outline-primary">Editar</button>
                        </form>
                        <form class="col" method="POST" action="$postAction">
                            <input type="hidden" name="delete_id" value="$entry[0]">
                            <button style="width: 100%;" type="submit" class="btn btn-outline-danger">Borrar</button>
                        </form>
                        </div>
                    </div>
                </div>
                <br>
                ENTRY;
                
                $entries++;
            }
            
            if ($entries === 0)
            {
                echo <<<NONE
                    <div class="my-2 py-4 mx-auto card" style="width: 18rem;">
                        <h2 style="color: gray; font-weight: bold;" class="mx-auto">Sin resultados</h2>
                    </div>
                NONE;
            }    
      ?>
      <div>

    <?php endif; ?>

</body>
</html>
