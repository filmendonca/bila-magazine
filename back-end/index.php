<?php

    if(isset($_GET["search"])){
        header("Location: list.php?search=".$_GET["search"]);
        die();
    }

    session_start();

    require_once "utils/Autoloader.php";

try{
    
    if(!empty($_POST)){

        $error = array();

        $validator = new classes_Validation;
        $error["nome"] = $validator->checkName($_POST["nome"]);
        $error["email"] = $validator->checkEmail($_POST["email"]);
        $error["mensagem"] = $validator->checkText($_POST["mensagem"]);

        $j = 0;

        foreach($error as $key => $value) {
            if(is_null($value)){
                $j++;
            }
        }

        // Se não houver erros
        if($j == 3){

            $db = new classes_Database;
            $userManager = new classes_UserManager($db);

            $result = $userManager->sendEmail($_POST);

            if($result){
                header("Location: index.php?email=success");
                die();
            }

            else{
                header("Location: index.php?email=fail");
                die();
            }

        }

    }

}

catch(Exception $e){
    $_POST = null;
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
    <title>Home</title>
</head>

<body>
    <noscript>Tem de ativar o javascript para interagir com esta página corretamente</noscript>
    <div class="wrapper">

        <div id="myNav" class="overlay">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <div class="overlay-content">
                <a href="#" onclick="closeNavButton()">Home</a>
                <hr class="hr-first-overlay">
                <a href="#sobre" onclick="closeNavButton()">Sobre Nós</a>
                <a href="list.php" onclick="closeNavButton()">Artigos</a>
                <a href="#contactos" onclick="closeNavButton()">Contactos</a>
                <hr class="hr-second-overlay">
                <a class="btn-pesquisa" href="list.php">Pesquisar</a>
            </div>
        </div>

        <header class="header" id="top">
            <nav>
                <div class="row">
                    <span class="colu-1-of-3" style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>
                    <a class="colu-1-of-3" href="#"><img src="../front-end/img/bila.png" alt="Logótipo" class="header__logo"></a>
                    
                    <div class="colu-1-of-3 header__nav">

                        <div class="search-box header__nav--item">
                            <form action="" method="get">
                            <button type="submit"><div class="header__nav--search">&#9906</div></button>
                            <input type="text" placeholder="Pesquisa" name="search">
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
            <div class="header__text-box">
                <h1 class="header__text-box--title-primary">Cultura artística Vila Real</h1>
                <h1 class="header__text-box--title-secondary">Bila Magazine</h1>
                <hr class="hr-second">
                <p class="arrow"><a href="#artigos">&or;</a></p>
                <!--<a class="btn btn--color" href="#pratos">Pratos</a>
                <a class="btn btn--transparent" href="#marcar">Mesa</a>-->
            </div>
        </header>

        <main class="main" id="artigos">

                        <!--Secção ARTIGOS-->
                        <div class="row">
                <section class="main__meal">
                    <h2 class="main__meal--title">Artigos</h2>
                    <p class="main__info--text">O diário da nossa revista.
                    <br>Novidades sobre tudo o que fazemos, mas também sobre tudo o que nos emociona.
                    <br>Sem regras nem livros de estilo. Livres de estilos.</p>
                </section>
            </div>
            <section class="main__meals">
                <ul class="main__meals--images">
                    <li class="main__meals--images-list" id="pratos">
                        <figure>
                        <img src="../front-end/img/IMG_5939.jpg" alt="Prato">
                        </figure>
                    </li>
                    <li class="main__meals--images-list">
                        <figure>
                        <img src="../front-end/img/IMG_5007.jpg" alt="Prato">
                        </figure>
                    </li>
                    <li class="main__meals--images-list">
                        <figure>
                        <img src="../front-end/img/IMG_5076.jpg" alt="Prato">
                        </figure>
                    </li>
                    <li class="main__meals--images-list">
                        <figure>
                        <img src="../front-end/img/IMG_4867.jpg" alt="Prato">
                        </figure>
                    </li>
                    <hr class="hr-article">
                </ul>
                
            </section>
            
            <div class="row">
                <!--Secção SOBRE-->
                
                <section class="main__info">
                    <h2 class="main__info--title" id="sobre">Sobre Nós</h2>
                </section>
            </div>
            <div class="row">
                <div class="colu col-1-of-2">
                    <p class="main__colu--text">Não há outra Revista como a Bila. Uma voz que se passeia pela tradição 
                    <br>livremente, sem deixar de flirtar elengantemente com a cultura da Bila.
                    <br> Alargando de uma forma muito pessoal, o raio de ação da cultura de
                    <br> Vila Real. Mas aquilo que a distingue é não apenas a sua forma
                    <br> diferente como ela observa o sentimento da Bila.</p>
                </div>
                <div class="colu col-1-of-2">
                    <figure class="main-sobre-imagem">
                        <img class="sobre-imagem"  src="../front-end/img/IMG_0513.jpg" alt="Artigo">
                    </figure>
                </div>
            </div>

            <!--Secção CONTACTOS-->
            <div class="row">
                <section class="main__book">
                    <h2 class="main__book--title" id="contactos">Contactos</h2>
                </section>
            </div>
            <div class="row">
                <div class="colu col-1-of-2">
                    <figure class="main-contacto-imagem">
                        <img class="contacto-imagem"  src="../front-end/img/DSC00404.jpg" alt="Prato">
                    </figure>
                </div>
                <div class="colu col-1-of-2">
                <form action="" method="POST" class="main__book--form">
                    <div class="emai row">
                    <hr class="hr-form">
                        <div class="colu col-2-of-2">
                            <label for="email">Email</label>
                            <br>
                            <input type="email" name="email"
                            value="<?php if(!empty($_POST) && empty($error['email'])){echo $_POST['email'];} ?>">
                            <?php
                            if(!empty($_POST) && $error["email"]){
                                echo $error["email"];
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="colu col-2-of-2">
                            <label for="nome">Nome</label>
                            <br>
                            <input type="text" name="nome"
                            value="<?php if(!empty($_POST) && empty($error['nome']))
                            {$_POST['nome']=trim($_POST['nome']); echo $_POST['nome'];} ?>">
                            <?php
                            if(!empty($_POST) && $error["nome"]){
                                echo $error["nome"];
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="colu col-2-of-2">
                            <label>Escreva-nos</label>
                            <br>
                            <textarea name="mensagem"><?php if(!empty($_POST) && empty($error['mensagem']))
                            {$_POST['mensagem']=trim($_POST['mensagem']); echo $_POST['mensagem'];} ?></textarea>
                            <?php
                            if(!empty($_POST) && $error["mensagem"]){
                                echo $error["mensagem"];
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="colu col-1-of-3">
                            <label></label>
                        </div>
                        <div class="colu col-2-of-3">
                            <input type="submit" value="Enviar" class="btn">
                        </div>
                    </div>
                </form>
                </div>
                <div class="row">
                    <a class="contacts" href="mailto:bilamagazine@gmail.com">bilamagazine@gmail.com</a>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/bila.magazine/"><img src="../front-end/img/facebook.png" alt="Facebook" width="20px" height="20px"></a>
                        <a href="https://www.instagram.com/bila.magazine/"><img src="../front-end/img/instagram.png" alt="Instagram" width="20px" height="20px"></a>
                    </div>
                    <hr class="hr-contacts">
                </div>
            </div>
        </main>

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

    </div>

</body>

</html>

<?php

if(isset($_GET["reg"])){

    switch ($_GET["reg"]) {
        case 'success':
            echo "<script>alert('Registo efetuado com sucesso')</script>";
            break;
    
        case 'login':
            echo "<script>alert('Login efetuado com sucesso')</script>";
            break;
    
        case 'logout':
            echo "<script>alert('Logout efetuado com sucesso')</script>";
            break;
    }

}

if(isset($_GET["email"])){

    switch ($_GET["email"]) {
        case 'success':
            echo "<script>alert('Email enviado com sucesso')</script>";
            break;
    
        case 'fail':
            echo "<script>alert('Erro ao enviar email')</script>";
            break;
    }

}

?>