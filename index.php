<?php

    //Conexão com a URL
    $url = "https://reqres.in/api/users?page=2";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    $total_users = json_decode($response, true);
    $users = $total_users['data'];

    //Conexão com o Banco de Dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "unaerp";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Api Unaerp</title>
  </head>
  <body>
    <h1>Lista de usuários</h1>

    <?php
        foreach ($users as $user) {
            $sql = "INSERT INTO users (id, first_name, last_name, email, avatar)
            VALUES (
                '".$user['id']."',
                '".$user['first_name']."',
                '".$user['last_name']."',
                '".$user['email']."',
                '".$user['avatar']."')";

            if ($conn->query($sql) === FALSE) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        //LISTAR os usuários inseridos
        $sql = "SELECT id, 
                        first_name, 
                        last_name,
                        email,
                        avatar
                FROM users";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {

            echo "<table class='table'>
            <thead>
                <tr>
                <th scope='col'>#</th>
                <th scope='col'>Email</th>
                <th scope='col'>First Name</th>
                <th scope='col'>Last Name</th>
                <th scope='col'>Avatar</th>
                </tr>
            </thead>
            <tbody>";

            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<th scope='row'>".$row['id']."</th>";
                echo "<td>".$row['email']."</td>";
                echo "<td>".$row['first_name']."</td>";
                echo "<td>".$row['last_name']."</td>";
                echo "<td><img src='".$row['avatar']."'></td>";
                echo "</tr>";
            }
            echo "</tbody></table>";

        }
        else {
            echo "0 results";
        }
        $conn->close();
    ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  </body>
</html>