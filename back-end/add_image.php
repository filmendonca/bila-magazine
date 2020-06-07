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

try{
    
    if(!empty($_FILES)){

        $error = array();

        if($_FILES["imagem"]["error"] == 4){
            $error["imagem"] = "* Tem de inserir uma imagem";
        }

        if($_FILES["imagem"]["error"] == 0){
            $validator = new classes_Validation;
            $error["imagem"] = $validator->checkImage($_FILES);
        }

        $j = 0;

        foreach($error as $key => $value) {
            if(is_null($value)){
                $j++;
            }
        }

        // Se não houver erros
        if($j == 1){

            $image = new classes_Article($_FILES, "image");
            $db = new classes_Database;
            $articleManager = new classes_ArticleManager($db);

            $result = $articleManager->addImage($image, $articleId);

            header("Location: list.php?action=add");
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
    <title>Inserir imagem</title>
</head>

<body>

        <div class="row">
            <a class="colu-1-of-3" href="#"><img src="../front-end/img/bila.png" alt="Logótipo" class="logo-login"></a>
            <div class="col-1-of-3 login-text">
                <p>Insira uma imagem
                </p>
            </div>
        </div>

        <div class="row">
                <form action="" method="POST" class="login-form" enctype="multipart/form-data">
                <div class="row">
                        <div class="colu col-2-of-2">
                            <label for="titulo">Imagem</label>
                            <br>
                            <input type="file" name="imagem">
                            <?php
                            if(!empty($_FILES) && $error["imagem"]){
                                echo "<br>";
                                echo $error["imagem"];
                            }
                            
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="colu col-2-of-3">
                            <input type="submit" value="Inserir" class="btn">
                        </div>
                        <br><br><br><br><br><br><br><br><br><br>
                    </div>
                </form>
        </div>

</body>

</html>