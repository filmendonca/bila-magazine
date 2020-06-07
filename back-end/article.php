<?php

if(empty($_GET)){
    header("Location: notfound.php");
    die();
}

session_start();

require_once "utils/Autoloader.php";

$id = $_GET["id"];

$db = new classes_Database;

$article = new classes_ArticleManager($db);

$result = $article->displayArticle($id, false);

$display = $result->fetchAll();

$resultGallery = $article->displayGallery($id);

$displayGallery = $resultGallery->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="../front-end/css/styles.css">
    <script src="../front-end/script/script.js"></script>
    <title><?php foreach($display as $col){echo $col["titulo"];} ?></title>
</head>

<body>

    <noscript>Tem de ativar o javascript para interagir com esta página corretamente</noscript>

        <div class='row'>
        <hr class='hr-article-page'>

        <?php

            if(!empty($_SESSION) && $_SESSION["tipo_user"] == 3){
                echo "<div class='row'>
                <a class='things-article link-download' href='delete_article.php?id=$id'>Apagar artigo</a>
                <a class='things-article manage-article' href='edit_article.php?id=$id'>Editar artigo</a>
                </div>";
            }
        ?>

    <?php
                        
            foreach($display as $col){
                
                echo 
                        "<section class='main__info'>
                            <h2 class='article-title' id='sobre'>".$col["titulo"]."</h2>
                        </section>
                </div>
                    <div class='row'>
                        <div class='colu col-1-of-2'>
                            <p class='article-text'>".$col["texto"]."</p>
                            <p class='article-text'>Evento decorrido em: ".$col["data_evento"]."</p>
                        </div>
                        <div class='colu col-1-of-2'>
                            <figure class='main-article-imagem'>
                                <img class='article-imagem' src=upload/".$col["imagem"]." alt='Artigo'>
                            </figure>
                        </div>
                    </div>";

            }
?>


<div class="row">
  <p class="gallery-text">Galeria</p>
</div>

<?php

$i = 1;

if(!empty($displayGallery)){

  foreach($displayGallery as $col){
  
    echo "<div class='row1'>
    <div class='column1'>
      <img src=upload/gallery/".$id."/".$col['imagem']." onclick='openModal();currentSlide($i)' class='hover-shadow' alt='imagem'>
    </div>";
  
    $i++;
  }
  
  echo "</div>";

}

else{
  echo "<p class='gallery-no-images'>A galeria não tem imagens</p>";
}

if(!empty($displayGallery)){

echo "<!-- The Modal/Lightbox -->
    <div id='myModal' class='modal'>
      <span class='close cursor' onclick='closeModal()'>&times;</span>
      <div class='modal-content'>";
}

if(!empty($displayGallery)){

  foreach($displayGallery as $col){
  
    echo "<div class='mySlides'>
    <img src=upload/gallery/".$id."/".$col['imagem']." style='width:100%'>
    </div>";
  
    $i++;
  }

}

if(!empty($displayGallery)){

echo "<!-- Next/previous controls -->
    <a class='prev' onclick='plusSlides(-1)'>&#10094;</a>
    <a class='next' onclick='plusSlides(1)'>&#10095;</a>

    <!-- Caption text -->
    <div class='caption-container'>
      <p id='caption'></p>
    </div>";


}

?>

</div></div>


 


<?php

if(!empty($_SESSION) && $_SESSION["tipo_user"] == 3){
    echo "<div class='row'>
    <a class='things-article link-download' href='delete_imagegallery.php?id=$id'>Apagar imagem da galeria</a>
    <a class='things-article manage-article' href='add_imagegallery.php?id=$id'>Adicionar imagem à galeria</a>
    </div>";
}
?>



        <div class="row">
            <a class="link-article link-back" href="list.php">Voltar à página de artigos</a>
            <?php

                if(!empty($_SESSION)){
                    echo "<a class='link-article link-download' href='magazine/bila_magazine.html'>
                    Faça download da nossa revista digital(em formato pdf)</a>";
                }
            ?>
        </div>

</body>

</html>

<?php

if(isset($_GET["action"])){

  switch ($_GET["action"]) {
      case 'add':
          echo "<script>alert('Imagem adicionada à galeria')</script>";
          break;
  
      case 'delete':
          echo "<script>alert('Imagem apagada da galeria')</script>";
          break;
  }

}

?>