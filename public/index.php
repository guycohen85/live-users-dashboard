<?php
$rootFolder = $_SERVER['DOCUMENT_ROOT'];

require_once $rootFolder . "/classes/Routes.php";
require_once $rootFolder . "/classes/Auth.php";

if (Auth::get_instance()->is_logged_in()) {
    $user = unserialize($_SESSION['user']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live User Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <header class="flex-between">
        <div class="flex-between">
            <div class="logo">GC</div>
            <h1>Live User Dashboard</h1>
        </div>
        <?php if (Auth::get_instance()->is_logged_in()) : ?>
            <form class="logout-form" method="POST" action="/">
                <input type="hidden" name="form" value="logout" />
                <button type="submit">Logout</button>
            </form>
        <?php endif; ?>
    </header>

    <main>
        <?php if (Auth::get_instance()->is_logged_in()) : ?>
            <p class="container-no-bg">Hello, <strong><?= $user->username ?></strong></p>
            <div class="container">
                <users-collections data-user='<?=  json_encode($user) ?>'></users-collections>
            </div>
        <?php else : ?>
            <div class="mini-container auth">
                <!-- <register-form></register-form> -->
                <login-form></login-form>
                <?= (isset($_SESSION["error"])) ? "<div class='error'>".$_SESSION["error"]."</div>" : "" ?>
            </div>
        <?php endif; ?>
    </main>

    <?php unset($_SESSION["error"]) ?>
    <script type="module" src="js/main.js"></script>
</body>

</html>