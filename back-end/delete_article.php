<?php

session_start();

require_once "utils/Autoloader.php";

if(empty($_SESSION) || $_SESSION["tipo_user"] != 3){
    header("Location: forbidden.php");
    die();
}

elseif(empty($_GET)){
    header("Location: notfound.php");
    die();
}

$id = $_GET["id"];

$db = new classes_Database;
$articleManager = new classes_ArticleManager($db);
$result = $articleManager->deleteArticle($id);

header("Location: list.php?action=delete");
die();

?>