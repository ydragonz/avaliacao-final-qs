<?php

    //Conexão com a URL
    $url = "https://www.balldontlie.io/api/v1/teams";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    $total_teams = json_decode($response, true);
    $teams = $total_teams['data'];

    //Conexão com o Banco de Dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "exam_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("A conexão com o banco de dados falhou falhou: " . $conn->connect_error);
    }

?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="icon" href="images/ad7wp-ov4g8.svg">
    <title>Api Prova Final</title>
  </head>
  <body>
    <h1>NBA</h1><hr>
    <h3>Lista de times</h3>

    
    <?php
        // Percorrendo os dados JSON e armazenando nos campos do Banco de Dados
        foreach ($teams as $team) {

            // Verifica se o ID já está inserido
            if(!$team['id']) {
                $sql = "INSERT INTO teams (ids, abbreviation, city, conference, division, full_name, name)
                VALUES (
                    '".$team['id']."',
                    '".$team['abbreviation']."',
                    '".$team['city']."',
                    '".$team['conference']."',
                    '".$team['division']."',
                    '".$team['full_name']."',
                    '".$team['name']."')";
    
                if ($conn->query($sql) === FALSE) {
                    ?>
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-octagon-fill" viewBox="0 0 16 16">
                            <path d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                        &#160
                        <div>
                            <?php echo "Erro: " . $sql . "<br>" . $conn->error; ?>
                        </div>
                    </div>
                    <?php
                }
            }
        }
        
        $sql = "SELECT id, abbreviation, city, conference, division, full_name, name FROM teams";
        $result = $conn->query($sql);

        // Verifica se há registros no Banco de Dados
        if ($result->num_rows > 0) {

            echo "<table class='table table-striped'>
            <thead>
                <tr>
                <th scope='col'>ID</th>
                <th scope='col'>Sigla</th>
                <th scope='col'>Cidade</th>
                <th scope='col'>Conferencia</th>
                <th scope='col'>Divisão</th>
                <th scope='col'>Nome completo</th>
                <th scope='col'>Nome</th>
                </tr>
            </thead>
            <tbody>";

            // Enquanto tiverem registror irá percorrer e imprimir eles na tabela
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<th scope='row'>".$row['id']."</th>";
                echo "<td>".$row['abbreviation']."</td>";
                echo "<td>".$row['city']."</td>";
                echo "<td>".$row['conference']."</td>";
                echo "<td>".$row['division']."</td>";
                echo "<td>".$row['full_name']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";

            ?>
            <div class="alert alert-secondary d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-capslock-fill" viewBox="0 0 16 16">
                    <path d="M7.27 1.047a1 1 0 0 1 1.46 0l6.345 6.77c.6.638.146 1.683-.73 1.683H11.5v1a1 1 0 0 1-1 1h-5a1 1 0 0 1-1-1v-1H1.654C.78 9.5.326 8.455.924 7.816L7.27 1.047zM4.5 13.5a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1h-5a1 1 0 0 1-1-1v-1z"/>
                </svg>
                &#160
                <div>
                    Fim da tabela.
                </div>
            </div>
            <?php

        }
        else {
            ?>
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                <div>
                    Nenhum dado encontrado na tabela.
                </div>
            </div>
            <?php
            echo "Nenhum resultado encontrado";
        }
        $conn->close();
    ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  </body>
</html>