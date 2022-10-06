<?php
    require_once('connect.php');

    session_start();
    if($_SESSION['name'] == null)
    {
        header("location:login.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <nav>
        <ul class="nav bg-dark justify-content-center">
            <li class="nav-item">
              <a class="nav-link " href="articles.php">Articles</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="liste.php">Recommendations</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">Deconnecter</a>
            </li>
        </ul>
    </nav>

    <section class='container shadow-lg p-2 rounded'>
        <h2>Liste des recommendations</h2>
        <table class='table'>
            <thead class='table-dark'> 
                <tr>
                    <th>Matricule</th>
                    <th>Prenom</th>
                    <th>Nom</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $author = $_SESSION['name'];
                $results = $client->run("MATCH (per1:author{nom:'$author'})-[:published]->(:article)-[:defined_by]->(k:keyword)<-[:defined_by]-(:article)<-[:published]-(per2:author)
                WHERE per1 <> per2 AND NOT (per1)-[:published]->(:article)<-[:published]-(per2)
                RETURN per2, count(*) as occur
                ORDER BY occur DESC");

                foreach($results as $res)
                {
                    $person = $res->get('per2');
                    $matricule = $person->getProperty('matricule');
                    $nom = $person->getProperty('nom');
                    $prenom = $person->getProperty('prenom');
                    $occur = $res->get('occur');
                    if($occur >= 2)
                    {
                ?>
                        <tr>
                            <td><?= $matricule ?></td>
                            <td><?= $prenom ?></td>
                            <td><?= $nom ?></td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>	
    </section>
</body>
</html>