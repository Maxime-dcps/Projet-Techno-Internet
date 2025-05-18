<?php
session_start();
include('./admin/src/php/utils/header.php');
include('./admin/src/php/utils/all_includes.php');
?>

<!doctype html>
<html>
<head>
    <title><?php print $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.min.js" integrity="sha256-AlTido85uXPlSyyaZNsjJXeCs07eSv3r43kyCVc8ChI=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./admin/assets/css/style.css">
    <!--<script src="./admin/assets/js/fonctionsJqueryUI.js"></script>-->
</head>

<body>
<div id="page" class="container">
    <header class="img_header"></header>
    <section>
        <nav>
            <?php
            if(file_exists('admin/src/php/utils/public_menu.php'))
                include('admin/src/php/utils/public_menu.php')?>
        </nav>
    </section>
    <section id="contenu">
        <div class="container mt-4">
            <?php
            include($_SESSION['page']);
            ?>
        </div>
    </section>

</div>
<?php include('./admin/src/php/utils/footer.php') ?>
</body>
</html>
