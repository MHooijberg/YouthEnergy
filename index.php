<?php
/** @file index.php
 * Index for the public website
 *
 * @author Martin Molema <martin.molema@nhlstenden.com>
 * @copyright 2022
 *
 * Show a very basis HTML Bootstrap template
 */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Energy Portal</title>
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light" role="navigation">
    <a class="navbar-brand" href="#">
        <img src="/images/logo.png" width="30" height="30" alt="">
    </a>
    <ul class="navbar-nav mr-auto">
        <li class="nav-item"><a href="/" class="nav-link">Home</a></li>
        <li class="nav-item"><a href="/intranet" class="nav-link">Intranet</a></li>
    </ul>
</nav>
<main class="container-fluid">
    <article class="main">
        <header><h1>Welkom bij onze website</h1></header>
        <section>
            Pietje puk test
        </section>
    </article>
</main>
<? include_once './partials/footer.php'; ?>
</body>
</html>