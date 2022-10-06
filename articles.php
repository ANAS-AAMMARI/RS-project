<?php
    require_once('connect.php');

    session_start();
    if ($_SESSION['name'] == null) {
        header("location:login.php");
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST["titre"]) && isset($_POST["contenu"]) && isset($_POST["Mot"])) {
            $titre = $_POST["titre"];
            $contenu = $_POST["contenu"];
            $mot = $_POST['Mot'];
            $arr = [];
            $arr = explode(',', $mot);
            $author = $_SESSION['name'];
            $query = "MATCH (per:author{nom:'$author'})"
                    . "MERGE (art:article{titre:'$titre',text:'$contenu'})"
                    . "MERGE (per)-[:published]->(art)";
            $client->run($query);
            foreach ($arr as $key) {
                $qr = "MATCH (a:article{titre:'$titre'})"
                    . "MERGE (k:keyword{name:'$key'})"
                    . "MERGE (a)-[:defined_by]->(k)";
                $client->run($qr);
            }
            header("location:articles.php");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <style>
    </style>
    <title>Articles</title>
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

    <section class='container mt-3 shadow-lg p-3 rounded d-flex justify-content-center'>
        <form method='post' action='articles.php'>
            <table>
                <h3>Ajouter un article</h3>
                <tr>
                    <td>Titre :</td>
                    <td><input style='margin-bottom:10px' required class='form-control' name='titre' type='text' /></td>
                </tr>
                <tr>
                    <td>contenu :</td>
                    <td><input style='margin-bottom:10px' required class='form-control' name='contenu' type='text' /></td>
                </tr>
                <tr>
                    <td>Mot clés :</td>
                    <td><input style='margin-bottom:10px' required class='form-control' name='Mot' type='text' placeholder="mot1,mot2,mot3" /></td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                        <button style='width:100px' class='btn btn-warning me-2' name='act_save' type='submit'>Enregistrer</button>
                        <button style='width:100px' class='btn btn-secondary' type='reset' name='act_reset'>Annuler</button>
                    </td>
                </tr>
            </table>
        </form>
    </section>

    <section class='container shadow-lg p-2 rounded'>
        <h2>Liste des articles</h2>
        <table class='table'>
            <thead class='table-dark'>
                <tr>
                    <th>titre</th>
                    <th>Contenu</th>
                    <th>Mots cles</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $nom = $_SESSION['name'];
                $rl = $client->run("match (:author{nom:'$nom'})-[:published]->(a:article) return a");
                $t2 = [];
                $y = 0;
                foreach ($rl as $result) {
                    $nA = $result->get('a');
                    $t2[$y] = $nA->getProperty('titre');
                    $y++;
                }

                $arr = [];
                $h = 0;
                while ($h < $y) {
                    $nk = "";
                    $t1 = $t2[$h];
                    $r1 = $client->run("match(:article{titre:'$t1'})-[:defined_by]->(k:keyword) return k");
                    foreach ($r1 as $rlt) {
                        $nK = $rlt->get('k');
                        $nk .= $nK->getProperty('name') . ',';
                    }
                    $arr[$h] = $nk;
                    $h++;
                }
                $rslt = $client->run("match (:author{nom:'$nom'})-[:published]->(a:article) return a");
                $c = 0;
                foreach ($rslt as $result) {
                    $nA = $result->get('a');
                    $n = $nA->getProperty('text');
                    $t = $nA->getProperty('titre');
                ?>
                    <tr>
                        <td><?= $t ?></td>
                        <td><?= $n ?></td>
                        <td><?= trim($arr[$c], ','); ?></td>
                    </tr>
                <?php $c++;
                }
                ?>
            </tbody>
        </table>
    </section>
</body>

</html>