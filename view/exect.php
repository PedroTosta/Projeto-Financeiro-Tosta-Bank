<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>TostaBank</title>
        <meta charset="UTF-8">        
        <link rel="icon" href="../image/moeda.png" >
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">    
    </head>
    <body>
        <?php 
            include_once('../controller/conexao.php');

            if (!isset($_SESSION)) session_start();
            
            //Verifico se o usuário está logado.
            if($_SESSION['login'] == false){
                header('Location: index.html');
                exit();
            }    
        
            //Verifico se o usuário clicou no botão transferir.
            if(isset($_POST['btnTransferir'])){
                $valor = $_POST['campoTransferir'];
                $id = $_POST['UsuarioID'];

                try {
                    $consulta = $pdo->query("SELECT * FROM Usuarios WHERE (`id` = '".$_SESSION['UsuarioID']."');");
                    $linhas = $consulta->rowCount();        
                    if ($linhas == 1) {
                        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                        if($valor > $resultado['saldo']){
                            echo '
                    <div class="modal modal-signin position-static d-block py-5" tabindex="-1" role="dialog" id="modalSignin">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header p-5 pb-4 border-bottom-0">
                                    <a href="banco.php" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                                </div>
                                <div class="modal-body p-5 pt-0">
                                    <div class="alert alert-success">  
                                        Saldo insuficiente para completar a transferência.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                            echo '
                            <script>                    
                            setTimeout(function(){
                                window.location.href = "banco.php";
                            }, 5000);
                            </script>';
                            exit();
                        }
                    }else{
                        echo '<div class="alert alert-danger">ERRO</div>';
                        exit();
                    }
                } catch(PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                }

                try{
                    $stmt = $pdo->prepare('UPDATE Usuarios SET saldo = saldo - :valor WHERE id = :id');
                    $stmt->execute(array(
                    ':valor' => $valor,
                    ':id' => $_SESSION['UsuarioID']
                    ));

                    $stmt = $pdo->prepare('UPDATE Usuarios SET saldo = saldo + :valor WHERE id = :id');
                    $stmt->execute(array(
                    ':valor' => $valor,
                    ':id' => $id
                    ));                

                     echo '
                    <div class="modal modal-signin position-static d-block py-5" tabindex="-1" role="dialog" id="modalSignin">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header p-5 pb-4 border-bottom-0">
                                    <a href="banco.php" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                                </div>
                                <div class="modal-body p-5 pt-0">
                                    <div class="alert alert-success">  
                                        Transferencia de <b>R$'.$valor.'</b> feito com sucesso para <b>'.$_POST['UsuarioNome'].'</b>. Redirecionando para a tela principal...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';

                    echo '
                    <script>                    
                    setTimeout(function(){
                        window.location.href = "banco.php";
                    }, 5000);
                    </script>';
                    exit();
                } catch(PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                } 
            }

            //Verifico se o admin clicou no botão editar usuários.
            if(isset($_POST['btnEditUserS'])){
                try{
                    $stmt = $pdo->prepare('UPDATE Usuarios SET nome = :nome, email = :email, nivel = :nivel, bancoID = :bancoID, agenciaID = :agenciaID WHERE id = :id');
                    $stmt->execute(array(
                    ':nome' => $_POST['UsuarioNome'],
                    ':email' => $_POST['UsuarioEmail'],
                    ':nivel' => $_POST['UsuarioNivel'],
                    ':bancoID' => $_POST['UsuarioBancoID'],
                    ':agenciaID' => $_POST['UsuarioAgenciaID'],
                    ':id' => $_POST['UsuarioID'],
                    ));     

                     echo '
                    <div class="modal modal-signin position-static d-block py-5" tabindex="-1" role="dialog" id="modalSignin">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header p-5 pb-4 border-bottom-0">
                                    <a href="banco.php" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                                </div>
                                <div class="modal-body p-5 pt-0">
                                    <div class="alert alert-success">  
                                        Dados atualizados com sucesso! Retornando para a tela principal...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';

                    echo '
                    <script>                    
                    setTimeout(function(){
                        window.location.href = "banco.php";
                    }, 5000);
                    </script>';
                    exit();
                } catch(PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                } 
            }

            //Verifico se o admin clicou no botão editar banco.
            if(isset($_POST['btnEditBancoS'])){
                try{
                    $stmt = $pdo->prepare('UPDATE Banco SET nome = :nome WHERE id = :id;');
                    $stmt->execute(array(
                    ':nome' => $_POST['BancoNome'],
                    ':id' => $_POST['BancoID']
                    ));     

                     echo '
                    <div class="modal modal-signin position-static d-block py-5" tabindex="-1" role="dialog" id="modalSignin">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header p-5 pb-4 border-bottom-0">
                                    <a href="banco.php" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                                </div>
                                <div class="modal-body p-5 pt-0">
                                    <div class="alert alert-success">  
                                        Dados atualizados com sucesso! Retornando para a tela principal...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';

                    echo '
                    <script>                    
                    setTimeout(function(){
                        window.location.href = "banco.php";
                    }, 5000);
                    </script>';
                    exit();
                } catch(PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                } 
            }


            //Verifico se o admin clicou no botão editar agência.
            if(isset($_POST['btnEditAgenciaS'])){
                try{
                    $stmt = $pdo->prepare('UPDATE Agencia SET nome = :nome, cep = :cep, bancoID = :bancoID WHERE id = :id;');
                    $stmt->execute(array(
                    ':nome' => $_POST['AgenciaNome'],
                    ':cep' => $_POST['AgenciaCEP'],
                    ':bancoID' => $_POST['AgenciaBancoID'],
                    ':id' => $_POST['AgenciaID']
                    ));     

                     echo '
                    <div class="modal modal-signin position-static d-block py-5" tabindex="-1" role="dialog" id="modalSignin">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header p-5 pb-4 border-bottom-0">
                                    <a href="banco.php" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                                </div>
                                <div class="modal-body p-5 pt-0">
                                    <div class="alert alert-success">  
                                        Dados atualizados com sucesso! Retornando para a tela principal...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';

                    echo '
                    <script>                    
                    setTimeout(function(){
                        window.location.href = "banco.php";
                    }, 5000);
                    </script>';
                    exit();
                } catch(PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                } 
            }


            //Verifico se o ID informado é igual o logado.
            if(isset($_POST['btnSelect'])){
                $id = $_POST['UsuarioID'];            
                if($id == $_SESSION['UsuarioID']){
                    echo '<div class="alert alert-danger">ERRO! Redirecionando para a tela principal...</div>';
                    echo '<script>setTimeout(function(){window.location.href = "login.php";}, 5000);</script>';
                    exit();
                }
                try {
                    $consulta = $pdo->query("SELECT * FROM Usuarios WHERE (`id` = '".$_SESSION['UsuarioID']."');");    
                    $linhas = $consulta->rowCount();        
                    if ($linhas == 1) {
                        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                    }else{
                        echo '<div class="alert alert-danger">ERRO</div>';
                        exit();
                    }
                } catch(PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                }
                $saldo = $resultado['saldo'];
                try {
                    $consulta = $pdo->query("SELECT * FROM Usuarios WHERE (`id` = '".$id."');");
                    $linhas = $consulta->rowCount();        
                    if ($linhas == 1) {
                        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                    }else{
                        echo '<div class="alert alert-danger">ERRO</div>';
                        exit();
                    }
                } catch(PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                }
                echo '
                    <div class="modal modal-signin position-static d-block py-5" tabindex="-1" role="dialog" id="modalSignin">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header p-5 pb-4 border-bottom-0">
                                    <h1 class="fw-bold mb-0 fs-2">Transferir</h1>
                                    <a href="banco.php" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                                </div>
                                <div class="modal-body p-5 pt-0">
                                    <form class="" method="POST">
                                        <small class="" style="color: black; font-weight: bold; font-size: 16px;">Destinatário:</small>
                                        <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">Número conta</span>
                                                    <input type="text" class="form-control" value="'.$_POST['UsuarioID'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioID" readonly>
                                                    
                                                </div>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">Nome</span>
                                                    <input type="text" class="form-control" value="'.$_POST['UsuarioNome'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioNome" readonly>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">Banco</span>
                                                    <input type="text" class="form-control" value="'.$_POST['UsuarioBanco'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioBanco" readonly>
                                                </div>
                                        <small class="" style="color: black; font-weight: bold; font-size: 16px;">Seu saldo: R$'.$saldo.'</small>
                                        <div class="input-group mb-3">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" min="1" max="'.$saldo.'" class="form-control" aria-label="Quantia para transferir" name="campoTransferir" required="">
                                        </div>                                
                                        <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit" name="btnTransferir">Transferir</button>                                  
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>';

            }

            //Verifico se o admin clicou no botão editar usuário
            if(isset($_POST['btnEditUser'])){                
                    echo '
                <div class="modal modal-signin position-static d-block py-1" tabindex="-1" role="dialog" id="modalSignin">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content rounded-4 shadow">
                            <div class="modal-header p-5 pb-4 border-bottom-0">
                                <h1 class="fw-bold mb-0 fs-2">Editar Usuário</h1>
                            </div>
                            <div class="modal-body p-5 pt-0">
                                <form action="exect.php" method="POST">
                                    <div class="form-floating mb-3">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">Número conta</span>
                                            <input type="text" class="form-control" value="'.$_POST['UsuarioID'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioID" readonly required="">
                                            
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">Nome completo</span>
                                            <input type="text" class="form-control" value="'.$_POST['UsuarioNome'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioNome" required="">
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">Email</span>
                                            <input type="text" class="form-control" value="'.$_POST['UsuarioEmail'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioEmail" required="">
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">Saldo</span>
                                            <input type="number" class="form-control" value="'.$_POST['UsuarioSaldo'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioNivel" required="">
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">Nível de acesso</span>
                                            <input type="number" class="form-control" value="'.$_POST['UsuarioNivel'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioNivel" required="">
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">ID Banco</span>
                                            <input type="number" class="form-control" value="'.$_POST['UsuarioBancoID'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioBancoID">
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">Banco</span>
                                            <input type="text" class="form-control" value="'.$_POST['UsuarioBancoNome'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioBancoNome" readonly>
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">ID Agencia</span>
                                            <input type="number" class="form-control" value="'.$_POST['UsuarioAgenciaID'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioAgenciaID">
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">Agencia</span>
                                            <input type="text" class="form-control" value="'.$_POST['UsuarioAgenciaNome'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioAgenciaNome" readonly>
                                        </div>
                                    </div>
                                    <button class="w-100 mb-2 btn btn-lg rounded-3 btn-success" type="submit" name="btnEditUserS">Salvar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>';                                                                           
            }

            //Verifico se o admin clicou no botão editar banco.
            if(isset($_POST['btnEditBanco'])){                
                    echo '
                <div class="modal modal-signin position-static d-block py-1" tabindex="-1" role="dialog" id="modalSignin">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content rounded-4 shadow">
                            <div class="modal-header p-5 pb-4 border-bottom-0">
                                <h1 class="fw-bold mb-0 fs-2">Editar Banco</h1>
                            </div>
                            <div class="modal-body p-5 pt-0">
                                <form action="exect.php" method="POST">
                                    <div class="form-floating mb-3">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">ID Banco</span>
                                            <input type="text" class="form-control" value="'.$_POST['BancoID'].'" aria-label="Username" aria-describedby="basic-addon1" name="BancoID" readonly required="">
                                            
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">Nome</span>
                                            <input type="text" class="form-control" value="'.$_POST['BancoNome'].'" aria-label="Username" aria-describedby="basic-addon1" name="BancoNome" required="">
                                        </div>                                        
                                    </div>
                                    <button class="w-100 mb-2 btn btn-lg rounded-3 btn-success" type="submit" name="btnEditBancoS">Salvar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>';                                                                           
            }

            //Verifico se o admin clicou no botão editar agência.
            if(isset($_POST['btnEditAgencia'])){                
                    echo '
                <div class="modal modal-signin position-static d-block py-1" tabindex="-1" role="dialog" id="modalSignin">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content rounded-4 shadow">
                            <div class="modal-header p-5 pb-4 border-bottom-0">
                                <h1 class="fw-bold mb-0 fs-2">Editar Agência</h1>
                            </div>
                            <div class="modal-body p-5 pt-0">
                                <form action="exect.php" method="POST">
                                    <div class="form-floating mb-3">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">ID Agência</span>
                                            <input type="text" class="form-control" value="'.$_POST['AgenciaID'].'" aria-label="Username" aria-describedby="basic-addon1" name="AgenciaID" readonly required="">
                                            
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">Nome</span>
                                            <input type="text" class="form-control" value="'.$_POST['AgenciaNome'].'" aria-label="Username" aria-describedby="basic-addon1" name="AgenciaNome" required="">
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">CEP</span>
                                            <input type="text" class="form-control" value="'.$_POST['AgenciaCEP'].'" aria-label="Username" aria-describedby="basic-addon1" name="AgenciaCEP" required="">
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">Banco ID</span>
                                            <input type="number" class="form-control" value="'.$_POST['AgenciaBancoID'].'" aria-label="Username" aria-describedby="basic-addon1" name="AgenciaBancoID" required="">
                                        </div>                                        
                                    </div>
                                    <button class="w-100 mb-2 btn btn-lg rounded-3 btn-success" type="submit" name="btnEditAgenciaS">Salvar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>';                                                                           
            }


        ?>
    </body>
</html>
