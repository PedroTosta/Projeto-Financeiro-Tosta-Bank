<?php 
    include_once('../controller/conexao.php');

    if (!isset($_SESSION)) session_start();
    
    if($_SESSION['login'] == false){
        header('Location: index.html');
        exit();
    }

    if($_SESSION['UsuarioBanco'] == null || $_SESSION['UsuarioAgencia'] == null){
        header('Location: associar.php');
        exit();
    }

    if(isset($_POST['btnDeslogar'])){
        session_destroy();
        header('Location: index.html');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>TostaBank</title>
        <meta charset="UTF-8">        
        <link rel="icon" href="../image/moeda.png" >
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">    
        <style>
            .btn:hover{
                box-shadow: 0px 0px 10px #0d6efd;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
                <a href="#" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none" style="cursor:default;">
                    <img src="../image/moeda.png" alt="moeda">
                </a>

                <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                    <li><span class="nav-link px-2 fw-bold texto" style="color:black; font-size:32px; cursor:default;">TostaBANK</span></li>
                </ul>

                <div class="col-md-3 text-end">
                    <form method="POST">
                        <button type="submit" name="btnDeslogar" class="btn btn-outline-primary me-2">SAIR</button>
                    </form>
                </div>
            </header>
        </div>
        <div class="text-center">
            <?php 
                try {
                    $consulta = $pdo->query("SELECT * FROM Usuarios WHERE (`id` = '".$_SESSION['UsuarioID']."');");    
                    $linhas = $consulta->rowCount();        
                    if ($linhas == 1) {
                        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                        $saldo = $resultado['saldo'];
                    }else{
                        echo '<div class="alert alert-danger">ERRO</div>';
                    }
                } catch(PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                }            
            ?>
            <div class="col-lg-9 mx-auto">
                <h1 class="display-9 fw-bold">Bem vindo, <?php $nome = $_SESSION['UsuarioNome']; $nome = explode(" ", $nome); echo $nome[0]; ?></h1>
                <h5>Saldo de R$<?php echo $saldo;?></h5>
                <div class="px-0 py-3 my-1 d-grid d-sm-flex justify-content-sm-center">                
                    <div class="row d-grid col-auto">                        
                        <form action="exec.php" class="row d-grid gap-3 mx-auto" method="POST">
                            <button type="submit" class="btn btn-outline-primary btn-lg" name="btnSacar">Sacar</button>
                            <button type="submit" class="btn btn-outline-primary btn-lg" name="btnTransferir">Transferir</button>
                            <button type="submit" class="btn btn-outline-primary btn-lg" name="btnDepositar">Depositar</button>
                            <?php 
                                if($_SESSION['UsuarioNivel'] == 2){
                                    echo '<button type="submit" class="btn btn-outline-primary btn-lg" name="btnEditUser">Editar Usuários</button>';
                                    echo '<button type="submit" class="btn btn-outline-primary btn-lg" name="btnEditBanco">Editar Bancos</button>';
                                    echo '<button type="submit" class="btn btn-outline-primary btn-lg" name="btnEditAgencia">Editar Agência</button>';
                                }
                            ?>
                        </form>
                    </div>                    
                </div>
            </div>
        </div>
    </body>
</html>
