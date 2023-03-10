<?php 
    
    include_once('../controller/conexao.php');

    if (!isset($_SESSION)) session_start();

    //Verifico se o usuário está logado.
    if($_SESSION['login'] == false){
        header('Location: index.html');
        exit();
    }   

    //Verifico se o usuário possui banco ou agência.
    if($_SESSION['UsuarioBanco'] != null && $_SESSION['UsuarioAgencia'] != null){
        header('Location: banco.php');
        exit();
    }

    if(isset($_POST['btnDeslogar'])){
        session_destroy();
        header('Location: index.html');
        exit();
    }        

    //Caso o usuário escolha um banco, faço a verificação se o botão foi clicado e atualizo a base de dados com o ID do banco escolhido.
    if(isset($_POST['btnBanco'])){ 
        $id = $_POST['listGroupRadios'];
        try{
            $stmt = $pdo->prepare('UPDATE Usuarios SET bancoID = :bancoID WHERE id = :id');
            $stmt->execute(array(
            ':bancoID' => $id,
            ':id' => $_SESSION['UsuarioID']
            ));
            $_SESSION['UsuarioBanco'] = $id;
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        } 
    }

    //Caso o usuário escolha uma agência, faço a verificação se o botão foi clicado e atualizo a base de dados com o ID da agência escolhida.
    if(isset($_POST['btnAgencia'])){ 
        $id = $_POST['listGroupRadios'];
        try{
            $stmt = $pdo->prepare('UPDATE Usuarios SET agenciaID = :agenciaID WHERE id = :id');
            $stmt->execute(array(
            ':agenciaID' => $id,
            ':id' => $_SESSION['UsuarioID']
            ));
            $_SESSION['UsuarioAgencia'] = $id;
            header('Location: banco.php');
            exit();
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        } 
    }

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>TostaBank</title>
        <meta charset="UTF-8">        
        <link rel="icon" href="../image/moeda.png">
        <link href="https://getbootstrap.com/docs/5.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">    
    </head>
    <body>
        <div class="modal modal-sheet position-static d-block py-5" tabindex="-1" role="dialog" id="modalSheet">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-4 shadow">
                    <div class="modal-header border-bottom-0">
                        <h1 class="modal-title fs-5">Associar a um Banco</h1>
                    </div>
                                                                
                        <?php
                            if($_SESSION['UsuarioBanco'] == null){
                                echo '<form method="POST"><div class="modal-body py-0">    
                                <p>Bem vindo, <b>'. $_SESSION['UsuarioNome'] .'</b>. Parece que você ainda não é associado de nenhum banco, escolha um banco para associar-se:</p>';
                                echo '
                                <div class="d-flex gap-5 justify-content-center">
                                    <div class="list-group mx-0 w-auto">';
                                            try {
                                                //Mostro todos os bancos.
                                                $consulta = $pdo->query("SELECT * FROM Banco;");    
                                                $linhas = $consulta->rowCount();        
                                                if ($linhas > 0) {
                                                    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                                                    do{
                                                        echo '<label class="list-group-item d-flex gap-2">
                                                            <input class="form-check-input flex-shrink-0" value="'.$resultado['id'].'"type="radio" name="listGroupRadios" id="listGroupRadios1" value="" required="">
                                                            <span>'.$resultado['nome'].'</span></label>';
                                                    }while($resultado = $consulta->fetch(PDO::FETCH_ASSOC));
                                                }else{
                                                    echo '<div class="alert alert-danger" role="alert">
                                                        Não há nenhum banco cadastrado!
                                                        </div>';
                                                    exit();
                                                }
                                            } catch(PDOException $e) {
                                                echo 'Error: ' . $e->getMessage();
                                            }                                            
                                        echo '</div></div></div>';
                                        echo '<div class="modal-footer flex-column border-top-0">
                                                <button type="submit" class="btn btn-lg btn-primary w-100 mx-0 mb-2" name="btnBanco">Próximo</button>
                                              </div>';

                                    echo '</form>';
                            }
                            

                            //Caso o usuário tenha banco mas não possui agência.
                            if($_SESSION['UsuarioBanco'] != null && $_SESSION['UsuarioAgencia'] == null){
                                try{
                                    $consulta = $pdo->query("SELECT nome FROM Banco WHERE id = ".$_SESSION['UsuarioBanco']);    
                                    $linhas = $consulta->rowCount();
                                    if ($linhas == 1) {
                                        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                                    }
                                }catch(PDOException $e) {
                                    echo 'Error: ' . $e->getMessage();
                                    exit();
                                }
                                echo '<form method="POST"><div class="modal-body py-0">';
                                echo '<p>Você já está cadastrado no banco <b>'.$resultado['nome'].'</b> mas ainda precisa uma agência, selecione-a abaixo:</p>';
                                echo '<div class="d-flex gap-5 justify-content-center"><div class="list-group mx-0 w-auto">';
                                try {
                                    $consulta = $pdo->query("SELECT * FROM Agencia WHERE bancoID = ".$_SESSION['UsuarioBanco'].";");
                                    $linhas = $consulta->rowCount();
                                    if ($linhas > 0) {
                                        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                                        do{
                                            echo '<label class="list-group-item d-flex gap-2">
                                                <input class="form-check-input flex-shrink-0" value="'.$resultado['id'].'"type="radio" name="listGroupRadios" id="listGroupRadios1" value="" required="">
                                                <span>Número: '.$resultado['id'].' | '.$resultado['nome'].' - '.$resultado['cep'].'</span></label>';
                                        }while($resultado = $consulta->fetch(PDO::FETCH_ASSOC));                                        
                                    }else{
                                        echo '<div class="alert alert-danger" role="alert">
                                            Não há nenhuma agência cadastrada!
                                            </div>';
                                        exit();
                                    }
                                    echo '</div></div></div>';
                                    echo '<div class="modal-footer flex-column border-top-0">
                                            <button type="submit" class="btn btn-lg btn-primary w-100 mx-0 mb-2" name="btnAgencia">Próximo</button>
                                          </div>';
                                    echo '</form>';
                                } catch(PDOException $e) {
                                    echo 'Error: ' . $e->getMessage();
                                }
                            }
                        ?>
                    
                </div>
            </div>
        </div>

        
    </body>
</html>
<?php
    
?>  
