<?php
session_start();
if (isset($_SESSION['name']))
    header('Location:articles.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('connect.php');
    if (isset($_POST["uname"]) && isset($_POST["psw"])) {
        $uname = $_POST["uname"];
        $pass = $_POST["psw"];
        $results = $client->run("match(a:author) return a");
        $check = false;
        foreach ($results as $result) {
            $node = $result->get('a');
            $n = $node->getProperty('nom');
            $p = $node->getProperty('password');
            if (strtolower($n) == strtolower($uname) && $p == $pass) {
                $_SESSION['name'] = $n;
                header("Location:articles.php");
                $check = true;
            }
            if(!$check){
                header("Location:login.php");
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin-top: 100Px;
        }

        form {
            border: 3px solid #f1f1f1;
            width: 400px;
            margin: 0 auto;
        }

        input[type=text],
        input[type=password] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        button {
            background-color: #04AA6D;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            opacity: 0.8;
        }

        .container {
            padding: 16px;

        }

        span.psw {
            float: right;
            padding-top: 16px;
        }
    </style>

</head>

<body>
    <form action="login.php" method="post">
        <div class="container">
            <label for="uname"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="uname" required>
            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="psw" required>
            <button type="submit">Login</button>
        </div>
    </form>

</body>

</html>