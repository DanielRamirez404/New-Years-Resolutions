<?php

    session_start();
    $postAction = htmlspecialchars($_SERVER["PHP_SELF"]);    

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

        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            if (isset($_POST['delete_id']))
            {
                $id = $_POST['delete_id'];
                $deletionQuery = "DELETE FROM Resolution WHERE id=$id";

                if ($connection->query($deletionQuery)) 
                    $alert = "Your entry was succesfully deleted";
                else 
                    $alert = "There was an error tryin to delete your entry";
            }
            else if (isset($_POST['search'])) 
            {
                $searchText = $_POST['search'];

                if ($searchText === "")
                    unset($_SESSION["search_query"]);
                else 
                    $_SESSION["search_query"] = $searchText;
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

    <h2 style="font-weight: bold;">New year's resolutions</h2>

    <?php if (isset($alert)) : ?>
        <script>alert($alert)</script>
    <?php endif; ?>

    <?php if (isset($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php elseif ($connection->query("SELECT COUNT(*) FROM Resolution")->numrows === 0) : ?>
        <h4 style="color: gray;">No registered data. Please, feel free to add some entries</h4>
    <?php else: ?>
        <div class="col">
        <div class="row mx-auto">
            <form style="width: 85%" method="POST" action="<?php echo $postAction ?>" class="my-2 row g-1">
                <input style="width: 195px;" type="text" name="search" class="mx-auto form-control" placeholder="Introduce tu búsqueda">
                <button style="max-width: 35px; font-weight: bold" class="mx-auto btn btn-primary" type="submit">Ir</button>
            </form>
            <form style="width: 15%" method="POST" action="<?php echo $postAction ?>" class="my-2 row g-1">
                <button style="max-width: 35px; font-weight: bold" class="btn btn-secondary" type="submit">+</button>
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
                            <input type="hidden" name="delete_id" value="$entry[0]">
                            <button style="width: 100%;" type="submit" class="btn btn-outline-primary">Edit</button>
                        </form>
                        <form class="col" method="POST" action="$postAction">
                            <input type="hidden" name="delete_id" value="$entry[0]">
                            <button style="width: 100%;" type="submit" class="btn btn-outline-danger">Delete</button>
                        </form>
                        </div>
                    </div>
                </div>
                <br>
                ENTRY;
            }    
      ?>
      <div>

    <?php endif; ?>

</body>
</html>
