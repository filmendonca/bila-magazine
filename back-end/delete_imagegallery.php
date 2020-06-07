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

$id = $_SESSION["id"];
$articleId = $_GET["id"];

$db = new classes_Database;

$article = new classes_ArticleManager($db);

$resultGallery = $article->displayGallery($articleId);

$displayGallery = $resultGallery->fetchAll();

$idsAllowed = array();

foreach($displayGallery as $col){
    $idsAllowed[] = $col["id"];
}

try{
    
    if(!empty($_POST)){

        $error = array();

        $imageId = $_POST["num"];

        $imageId = (int)$imageId;

        if($imageId <= 0){
            $error["num"] = "* Tem de inserir um número positivo";
        }
        else{
            $error["num"] = null;
        }

        if(!is_int($imageId)){
            $error["num"] = "* Tem de inserir um número inteiro";
        }
        else{
            $error["num"] = null;
        }

        if(!in_array($imageId, $idsAllowed)){
            $error["num"] = "* O número inserido não pertence a nenhuma imagem";
        }
        else{
            $error["num"] = null;
        }

        $j = 0;

        foreach($error as $key => $value) {
            if(is_null($value)){
                $j++;
            }
        }

        // Se não houver erros
        if($j == 1){

            $db = new classes_Database;
            $articleManager = new classes_ArticleManager($db);

            $result = $articleManager->deleteImageGallery($articleId, $imageId);

            header("Location: article.php?id=".$articleId."&action=delete");
            die();

        }

    }

}

catch(Exception $e){
    $_POST = null;
    echo $e->getMessage();
    echo "wrong data";
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="../front-end/css/styles.css">
    <title>Apagar imagem da galeria</title>
</head>

<body>


    <?php

        foreach($displayGallery as $col){
        
        echo $col["id"];
        echo "<div class='row1'>
        <div class='column1'>
            <img src=upload/gallery/".$articleId."/".$col['imagem']." class='hover-shadow' alt='imagem'>
        </div>";

        }

        echo "</div>";

    ?>
        
        <div class="row">
            <a class="colu-1-of-3" href="#"><img src="../front-end/img/bila.png" alt="Logótipo" class="logo-login"></a>
            <div class="col-1-of-3 login-text">
                <p>Apague uma imagem
                </p>
            </div>
        </div>

        <div class="row">
                <form action="" method="POST" class="login-form">
                <div class="row">
                        <div class="colu col-2-of-2">
                            <label for="titulo">Escolha o número da imagem</label>
                            <br>
                            <input type="number" name="num">
                            <?php
                            if(!empty($_POST) && isset($error["num"])){
                                echo "<br>";
                                echo $error["num"];
                            }
                            
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="colu col-2-of-3">
                            <input type="submit" value="Apagar" class="btn">
                        </div>
                        <br><br><br><br><br><br><br><br><br><br>
                    </div>
                </form>
        </div>

</body>

</html>