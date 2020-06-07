<?php

    session_start();

    require_once "utils/Autoloader.php";

    try{
        
        if(isset($_GET["search"])){

            $validator = new classes_Validation;
            $error = $validator->checkSearch($_GET);

            // Se não houver erros
            if($error){

                $db = new classes_Database;

                $searcher = new classes_Search($db);
                $result = $searcher->searchText($_GET);
            }

        }

    }

    catch(Exception $e){
        $_GET = null;
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
    <script src="../front-end/script/script.js"></script>
    <title>Artigos</title>
</head>

<body>

    <noscript>Tem de ativar o javascript para interagir com esta página corretamente</noscript>

    <div id="myNav" class="overlay">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <div class="overlay-content">
                <a href="index.php" onclick="closeNavButton()">Home</a>
                <hr class="hr-first-overlay">
                <a href="index.php#sobre" onclick="closeNavButton()">Sobre Nós</a>
                <a href="list.php" onclick="closeNavButton()">Artigos</a>
                <a href="index.php#contactos" onclick="closeNavButton()">Contactos</a>
                <hr class="hr-second-overlay">
                <a class="btn-pesquisa" href="list.php">Pesquisar</a>
            </div>
        </div>

            <nav id="top">
                <div class="row">
                    <span class="colu-1-of-3" style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>
                    <a class="colu-1-of-3" href="#"><img src="../front-end/img/bila.png" alt="Logótipo" class="header__logo"></a>
                    
                    <div class="colu-1-of-3 header__nav">

                        <div class="search-box header__nav--item">
                            <form action="" method="get">
                            <button type="submit"><div class="header__nav--search">&#9906</div></button>
                            <input type="text" placeholder="Pesquisa" name="search" onkeyup="showResult(this.value)">
                            <div id="livesearch"></div>
                            </form>
                        </div>

                        <!--<li class="header__nav--item"><a class="header__nav--search">&#9906</a></li>-->
                        <?php 
                        if(empty($_SESSION)){
                            echo "<div class='header__nav--item'><a class='header__nav--link' href='regist.php' title='Registo'>";
                        }
                        else{
                            echo "<div class='header__nav--item'><a class='header__nav--link' href='logout.php' title='Sair'>";
                        }
                        ?>
                        <img src="../front-end/img/login_user_117687.png" alt="Login"></a></div>
                    </div>
                    <hr class="hr-first">
                </div>
            </nav>

            <?php

                    if(!empty($_SESSION) && $_SESSION["tipo_user"] == 3){
                        echo "<div class='row'>
                        <a class='link-article link-download' href='add_article.php'>Adicionar artigo</a>
                        </div>";
                    }
                    else{
                        echo "<br><br><br><br>";
                    }
            ?>

            <?php

            if(!isset($_GET["search"])) {
                
                $db = new classes_Database;

                $article = new classes_ArticleManager($db);

                $result = $article->displayArticle(null, true);

                $display = $result->fetchAll();
                                
                    foreach($display as $col){

                        $limit = 500;
                            
                        $col['texto'] = substr($col['texto'],0,$limit);
                        $col['texto'] = substr($col['texto'],0,strrpos($col['texto'],' '));
                        $col["texto"] .= "...";
                        
                        echo 
                        "<div class='row'>
                        <div class='colu col-1-of-2'>
                            <figure class='main-list-imagem'>
                                <img class='list-imagem' src=upload/".$col["imagem"]." alt='Artigo'>
                            </figure>
                        </div>
                        <div class='colu col-1-of-2'>
                        <section class='main__info'>
                            <h2 class='list-title'>".$col["titulo"]."</h2>
                        </section>
                            <p class='list-text'>".$col["texto"]."</p>
                            <a class='btn-list' href='article.php?id=$col[id]'>Ler mais</a>
                        </div>
                        </div>";
                        
                    }
                
            }

            elseif(isset($_GET["search"])){

                if(is_string($error)){

                    echo "
                    <div class='row'>
                        <p class='error'>".$error."</p>
                    </div>";
                }

                elseif($result){

                    $rows = $result->fetchAll();

                    if(empty($rows)){

                        echo "
                            <div class='row'>
                            <p class='error'>Não foram encontrados resultados para a sua pesquisa</p>
                            </div>";
                    }
                            
                        foreach($rows as $col){
                            
                            $limit = 500;
                            
                            $col['texto'] = substr($col['texto'],0,$limit);
                            $col['texto'] = substr($col['texto'],0,strrpos($col['texto'],' '));
                            $col["texto"] .= "...";

                            echo 
                            "<div class='row'>
                            <div class='colu col-1-of-2'>
                                <figure class='main-list-imagem'>
                                    <img class='list-imagem' src=upload/".$col["imagem"]." alt='Artigo'>
                                </figure>
                            </div>
                            <div class='colu col-1-of-2'>
                            <section class='main__info'>
                                <h2 class='list-title'>".$col["titulo"]."</h2>
                            </section>
                                <p class='list-text'>".$col["texto"]."</p>
                                <a class='btn-list' href='article.php?id=$col[id]'>Ler mais</a>
                            </div>
                            </div>";

                            if(strlen($col["texto"]) > 0){

                            }
                        }                       
                    
                }

            }

        ?>
        <!--
            <div class="row">
                <div class="colu col-1-of-2">
                    <figure class="main-list-imagem">
                        <img class="list-imagem"  src="../front-end/img/local-img-1.jpg" alt="Artigo">
                    </figure>
                </div>
                <div class="colu col-1-of-2">
                <section class="main__info">
                    <h2 class="list-title" id="sobre">Sobre Nós</h2>
                </section>
                    <p class="list-text">Não há outra Revista como a Bila. Uma voz que se passeia pela tradição 
                    livremente, sem deixar de flirtar elengantemente com a cultura da Bila.
                     Alargando de uma forma muito pessoal, o raio de ação da cultura de
                     Vila Real. Mas aquilo que a distingue é não apenas a sua forma
                     diferente como ela observa o sentimento da Bila.Não há outra Revista como a Bila. Uma voz que se passeia pela tradição 
                    livremente, sem deixar de flirtar elengantemente com a cultura da Bila.
                     Alargando de uma forma muito pessoal, o raio de ação da cultura de
                     Vila Real. Mas aquilo que a distingue é não apenas a sua forma
                     diferente como ela observa o sentimento da Bila.Não há outra Revista como a Bila. Uma voz que se passeia pela tradição 
                    livremente, sem deixar de flirtar elengantemente com a cultura da Bila.
                     Alargando de uma forma muito pessoal, o raio de ação da cultura de
                     Vila Real. Mas aquilo que a distingue é não apenas a sua forma
                     diferente como ela observa o sentimento da Bila.</p>
                     Se o strlen do texto for maior do que um determinado valor, 
                     pôr um read more e redirecionar para a página do artigo
                </div>
            </div>

            <div class="row">
                <div class="colu col-1-of-2">
                    <figure class="main-list-imagem">
                        <img class="list-imagem"  src="../front-end/img/local-img-1.jpg" alt="Artigo">
                    </figure>
                </div>
                <div class="colu col-1-of-2">
                <section class="main__info">
                    <h2 class="list-title" id="sobre">Sobre Nós</h2>
                </section>
                    <p class="list-text">Não há outra Revista como a Bila. Uma voz que se passeia pela tradição 
                    livremente, sem deixar de flirtar elengantemente com a cultura da Bila.
                     Alargando de uma forma muito pessoal, o raio de ação da cultura de
                     Vila Real. Mas aquilo que a distingue é não apenas a sua forma
                     diferente como ela observa o sentimento da Bila.</p>
                </div>
            </div>
            -->
        <footer class="footer">
            <div class="row">
                    <ul class="footer__colu">
                    <p class="arrow-footer"><a href="#top">&or;</a></p>
                    <br>
                    <img src="../front-end/img/bila.png" alt="Logótipo" width="60px" height="60px">
                        <br>
                        <hr class="hr-footer">
                        <p>&copy; Copyright 2019 Bila</p>
                        <br>
                        <p>by Bila</p>
                        <br>
                    </ul>
            </div>
        </footer>

</body>

</html>

<?php

if(isset($_GET["action"])){

    switch ($_GET["action"]) {
        case 'add':
            echo "<script>alert('Artigo adicionado com sucesso')</script>";
        break;

        case 'delete':
            echo "<script>alert('Artigo apagado com sucesso')</script>";
        break;

        case 'edit':
            echo "<script>alert('Artigo editado com sucesso')</script>";
        break;
    
    }

}

?>