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
            
            if($_SESSION['login'] == false){
                header('Location: index.html');
                exit();
            }    

            if(isset($_POST['btnSacar'])){
                try {
                    $consulta = $pdo->query("SELECT * FROM Usuarios WHERE (`id` = '".$_SESSION['UsuarioID']."');");    
                    $linhas = $consulta->rowCount();        
                    if ($linhas == 1) {
                        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                    }else{
                        echo '<div class="alert alert-danger">ERRO</div>';
                    }
                } catch(PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                }
                    echo '
                    <div id="divp" class="modal modal-signin position-static d-block py-5" tabindex="-1" role="dialog" id="modalSignin">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header p-5 pb-4 border-bottom-0">
                                    <h1 class="fw-bold mb-0 fs-2">Sacar</h1>
                                    <a href="banco.php" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                                </div>
                                <div class="modal-body p-5 pt-0">
                                    <form class="" method="POST">
                                        <small class="" style="color: black; font-weight: bold; font-size: 16px;">Saldo: R$'.$resultado['saldo'].'</small>
                                        <div class="input-group mb-3">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" min="1" max="'.($resultado['saldo']-5).'" class="form-control" aria-label="Quantia para sacar" name="campoSacar" required="" id="campoSacarID">
                                        </div>                                
                                        <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit" name="btnSacar" id="botaoSacar">Sacar</button>
                                        <small class="" style="color: red; font-weight: bold; font-size: 16px;">Para sacar, será descontado uma quantia de <b>R$5,00</b></small>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>';

                    
                if(isset($_POST['campoSacar'])){
                    $sacar = $_POST['campoSacar'];
                    if($sacar == 0){
                        echo '
                            <script>
                        let btnCadastrar = document.getElementById("botaoSacar");
                        btnCadastrar.classList.add("is-invalid");
                        let divPai = btnCadastrar.parentNode;
                        let div = document.createElement("div");
                        div.classList.add("alert");
                        div.classList.add("alert-danger");
                        let text = document.createTextNode("Informe uma quantia para sacar!");
                        div.appendChild(text);                                       
                        divPai.insertBefore(div, btnCadastrar);
                        </script>';         
                        exit();
                    }
                    if($sacar + 5 > $resultado['saldo']){
                        echo '
                            <script>
                        let btnCadastrar = document.getElementById("botaoSacar");
                        btnCadastrar.classList.add("is-invalid");
                        let divPai = btnCadastrar.parentNode;
                        let div = document.createElement("div");
                        div.classList.add("alert");
                        div.classList.add("alert-danger");
                        let text = document.createTextNode("Saldo insuficiente para sacar essa quantia!");
                        div.appendChild(text);                                       
                        divPai.insertBefore(div, btnCadastrar);
                        </script>';              
                    }else{
                        try{
                            $stmt = $pdo->prepare('UPDATE Usuarios SET saldo = saldo - :sacar - 5 WHERE id = :id');
                            $stmt->execute(array(
                            ':sacar' => $sacar,
                            ':id' => $_SESSION['UsuarioID']
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
                                                Saque de <b>R$'.$sacar.'</b> feito com sucesso! Redirecionando para a tela principal...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

                            echo '
                            <script> 
                            let divp = document.getElementById("divp");
                            divp.remove();                   
                            setTimeout(function(){
                                window.location.href = "banco.php";
                            }, 5000);
                            </script>';
                            exit();
                        } catch(PDOException $e) {
                            echo 'Error: ' . $e->getMessage();
                        } 
                    }
                }
            }

            if(isset($_POST['btnTransferir'])){
                echo '
                    <div class="modal modal-signin position-static d-block py-2" tabindex="-1" role="dialog" id="modalSignin">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header p-5 pb-4 border-bottom-0">
                                    <h1 class="fw-bold mb-0 fs-2">Transferir</h1>
                                    <a href="banco.php" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                                </div>
                                <div class="modal-body p-5 pt-0">
                                    <form class="" method="POST">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control rounded-3" name="campoBuscaUsuario" id="campoBuscaUsuarioID" placeholder="Nome" required>
                                            <label for="campoBuscaUsuarioID">Nome ou número conta</label>
                                        </div>         
                                        <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit" name="btnTransferir" id="botaoBuscaUsuario">Buscar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>';

                if(isset($_POST['campoBuscaUsuario'])){
                    $campo = $_POST['campoBuscaUsuario'];
                    try {
                        $consulta = $pdo->query("SELECT Usuarios.id as UsuarioID, Usuarios.nome as NomeUsuario, Banco.nome as NomeBanco FROM Usuarios INNER JOIN Banco ON Usuarios.bancoID = Banco.id WHERE (Usuarios.id LIKE '".$campo."%' OR Usuarios.nome LIKE '".$campo."%');");    
                        $linhas = $consulta->rowCount();    
                        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                        if ($linhas > 0) {
                            do{
                                if($_SESSION['UsuarioID'] != $resultado['UsuarioID']){                        
                                    echo '
                        <div class="modal modal-signin position-static d-block py-1" tabindex="-1" role="dialog" id="modalSignin">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content rounded-4 shadow">
                                    <div class="modal-header p-5 pb-4 border-bottom-0">
                                        <h1 class="fw-bold mb-0 fs-2"></h1>
                                    </div>
                                    <div class="modal-body p-5 pt-0">
                                        <form action="exect.php" method="POST">
                                            <div class="form-floating mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">Número conta</span>
                                                    <input type="text" class="form-control" value="'.$resultado['UsuarioID'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioID" readonly>
                                                    
                                                </div>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">Nome completo</span>
                                                    <input type="text" class="form-control" value="'.$resultado['NomeUsuario'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioNome" readonly>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">Banco</span>
                                                    <input type="text" class="form-control" value="'.$resultado['NomeBanco'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioBanco" readonly>
                                                </div>
                                            </div>
                                            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit" name="btnSelect">Selecionar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>';
                                }
                            }while($resultado = $consulta->fetch(PDO::FETCH_ASSOC));
                        }else{
                            echo '
                            <script>
                            let btnCadastrar = document.getElementById("botaoBuscaUsuario");
                            let divPai = btnCadastrar.parentNode;
                            let div = document.createElement("div");
                            div.classList.add("alert");
                            div.classList.add("alert-danger");
                            let text = document.createTextNode("Nenhum resultado");
                            div.appendChild(text);                                       
                            divPai.insertBefore(div, btnCadastrar.nextSibling);
                            </script>';                         
                        }
                    } catch(PDOException $e) {
                        echo 'Error: ' . $e->getMessage();
                    }
                }
            
            }


            if(isset($_POST['btnDepositar'])){
                echo '
                    <div id="divp" class="modal modal-signin position-static d-block py-1" tabindex="-1" role="dialog" id="modalSignin">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header p-5 pb-4 border-bottom-0">
                                    <h1 class="fw-bold mb-0 fs-2">Depositar</h1>
                                    <a href="banco.php" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                                </div>
                                <div class="modal-body p-5 pt-0">
                                    <form class="" method="POST">
                                        <div class="input-group mb-3">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" min="1" max="100000" class="form-control" aria-label="Quantia para depositar" name="campoDepositar" required="">
                                        </div>                                
                                        <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit" name="btnDepositar" id="botaoDepositar">Depositar</button>                                        
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>';


                if(isset($_POST['campoDepositar'])){
                    $valor = $_POST['campoDepositar'];
                    try{
                            $stmt = $pdo->prepare('UPDATE Usuarios SET saldo = saldo + :valor WHERE id = :id');
                            $stmt->execute(array(
                            ':valor' => $valor,
                            ':id' => $_SESSION['UsuarioID']
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
                                                Depósito de <b>R$'.$valor.'</b> feito com sucesso! Redirecionando para a tela principal...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

                            echo '
                            <script>                    
                            let divp = document.getElementById("divp");
                            divp.remove();
                            setTimeout(function(){
                                window.location.href = "banco.php";
                            }, 5000);
                            </script>';
                            exit();
                        } catch(PDOException $e) {
                            echo 'Error: ' . $e->getMessage();
                        } 
                }

            }

            if(isset($_POST['btnEditUser'])){
                if($_SESSION['UsuarioNivel'] != 2){
                    Header('Location: banco.php');
                    exit();
                }
                echo '
                    <div class="modal modal-signin position-static d-block py-2" tabindex="-1" role="dialog" id="modalSignin">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header p-5 pb-4 border-bottom-0">
                                    <h1 class="fw-bold mb-0 fs-2">Editar Usuários</h1>
                                    <a href="banco.php" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                                </div>
                                <div class="modal-body p-5 pt-0">
                                    <form class="" method="POST">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control rounded-3" name="campoBuscaUsuario" id="campoBuscaUsuarioID" placeholder="Nome" required>
                                            <label for="campoBuscaUsuarioID">Nome ou número conta</label>
                                        </div>         
                                        <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit" name="btnEditUser" id="botaoBuscaUsuario">Buscar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>';

                if(isset($_POST['campoBuscaUsuario'])){
                    $campo = $_POST['campoBuscaUsuario'];
                    try {
                        $consulta = $pdo->query("SELECT Usuarios.id as UsuarioID, Usuarios.nome as UsuarioNome, Usuarios.email as UsuarioEmail, Usuarios.nivel as UsuarioNivel, Usuarios.saldo as UsuarioSaldo, Banco.id as BancoID, Banco.nome as BancoNome, Agencia.id as AgenciaID, Agencia.nome as AgenciaNome FROM Usuarios INNER JOIN Banco ON Usuarios.bancoID = Banco.id INNER JOIN Agencia ON Usuarios.AgenciaID = Agencia.ID WHERE Usuarios.id LIKE '".$campo."%' OR Usuarios.nome LIKE '".$campo."%';");    
                        $linhas = $consulta->rowCount();
                        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                        if ($linhas > 0) {
                            do{       
                                    echo '
                        <div class="modal modal-signin position-static d-block py-1" tabindex="-1" role="dialog" id="modalSignin">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content rounded-4 shadow">
                                    <div class="modal-header p-5 pb-4 border-bottom-0">
                                        <h1 class="fw-bold mb-0 fs-2"></h1>
                                    </div>
                                    <div class="modal-body p-5 pt-0">
                                        <form action="exect.php" method="POST">
                                            <div class="form-floating mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">Número conta</span>
                                                    <input type="text" class="form-control" value="'.$resultado['UsuarioID'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioID" readonly>
                                                    
                                                </div>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">Nome completo</span>
                                                    <input type="text" class="form-control" value="'.$resultado['UsuarioNome'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioNome" readonly>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">Email</span>
                                                    <input type="text" class="form-control" value="'.$resultado['UsuarioEmail'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioEmail" readonly>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">Saldo</span>
                                                    <input type="text" class="form-control" value="'.$resultado['UsuarioSaldo'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioSaldo" readonly>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">Nível de acesso</span>
                                                    <input type="text" class="form-control" value="'.$resultado['UsuarioNivel'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioNivel" readonly>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">ID Banco</span>
                                                    <input type="text" class="form-control" value="'.$resultado['BancoID'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioBancoID" readonly>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">Banco</span>
                                                    <input type="text" class="form-control" value="'.$resultado['BancoNome'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioBancoNome" readonly>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">ID Agencia</span>
                                                    <input type="text" class="form-control" value="'.$resultado['AgenciaID'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioAgenciaID" readonly>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">Agencia</span>
                                                    <input type="text" class="form-control" value="'.$resultado['AgenciaNome'].'" aria-label="Username" aria-describedby="basic-addon1" name="UsuarioAgenciaNome" readonly>
                                                </div>
                                            </div>
                                            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit" name="btnEditUser">Editar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>';
                            }while($resultado = $consulta->fetch(PDO::FETCH_ASSOC));
                        }else{
                            echo '
                            <script>
                            let btnCadastrar = document.getElementById("botaoBuscaUsuario");
                            let divPai = btnCadastrar.parentNode;
                            let div = document.createElement("div");
                            div.classList.add("alert");
                            div.classList.add("alert-danger");
                            let text = document.createTextNode("Nenhum resultado");
                            div.appendChild(text);                                       
                            divPai.insertBefore(div, btnCadastrar.nextSibling);
                            </script>';                         
                        }
                    } catch(PDOException $e) {
                        echo 'Error: ' . $e->getMessage();
                    }
                }
                
            
            }


            if(isset($_POST['btnEditBanco'])){
                if($_SESSION['UsuarioNivel'] != 2){
                    Header('Location: banco.php');
                    exit();
                }
                echo '
                    <div class="modal modal-signin position-static d-block py-2" tabindex="-1" role="dialog" id="modalSignin">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header p-5 pb-4 border-bottom-0">
                                    <h1 class="fw-bold mb-0 fs-2">Editar Banco</h1>
                                    <a href="banco.php" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                                </div>
                                <div class="modal-body p-5 pt-0">
                                    <form class="" method="POST">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control rounded-3" name="campoBuscaUsuario" id="campoBuscaUsuarioID" placeholder="Nome" required>
                                            <label for="campoBuscaUsuarioID">Nome ou número do banco</label>
                                        </div>         
                                        <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit" name="btnEditBanco" id="botaoBuscaUsuario">Buscar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>';

                if(isset($_POST['campoBuscaUsuario'])){
                    $campo = $_POST['campoBuscaUsuario'];
                    try {
                        $consulta = $pdo->query("SELECT * FROM Banco WHERE id LIKE '".$campo."%' OR nome LIKE '".$campo."%'; ");    
                        $linhas = $consulta->rowCount();
                        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                        if ($linhas > 0) {
                            do{       
                                    echo '
                        <div class="modal modal-signin position-static d-block py-1" tabindex="-1" role="dialog" id="modalSignin">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content rounded-4 shadow">
                                    <div class="modal-header p-5 pb-4 border-bottom-0">
                                        <h1 class="fw-bold mb-0 fs-2"></h1>
                                    </div>
                                    <div class="modal-body p-5 pt-0">
                                        <form action="exect.php" method="POST">
                                            <div class="form-floating mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">ID Banco</span>
                                                    <input type="text" class="form-control" value="'.$resultado['id'].'" aria-label="Username" aria-describedby="basic-addon1" name="BancoID" readonly>                                                    
                                                </div>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">Nome</span>
                                                    <input type="text" class="form-control" value="'.$resultado['nome'].'" aria-label="Username" aria-describedby="basic-addon1" name="BancoNome" readonly>
                                                </div>
                                            </div>
                                            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit" name="btnEditBanco">Editar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>';
                            }while($resultado = $consulta->fetch(PDO::FETCH_ASSOC));
                        }else{
                            echo '
                            <script>
                            let btnCadastrar = document.getElementById("botaoBuscaUsuario");
                            let divPai = btnCadastrar.parentNode;
                            let div = document.createElement("div");
                            div.classList.add("alert");
                            div.classList.add("alert-danger");
                            let text = document.createTextNode("Nenhum resultado");
                            div.appendChild(text);                                       
                            divPai.insertBefore(div, btnCadastrar.nextSibling);
                            </script>';                         
                        }
                    } catch(PDOException $e) {
                        echo 'Error: ' . $e->getMessage();
                    }
                }
            }



            
            if(isset($_POST['btnEditAgencia'])){
                if($_SESSION['UsuarioNivel'] != 2){
                    Header('Location: banco.php');
                    exit();
                }
                echo '
                    <div class="modal modal-signin position-static d-block py-2" tabindex="-1" role="dialog" id="modalSignin">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header p-5 pb-4 border-bottom-0">
                                    <h1 class="fw-bold mb-0 fs-2">Editar Agencia</h1>
                                    <a href="banco.php" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                                </div>
                                <div class="modal-body p-5 pt-0">
                                    <form class="" method="POST">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control rounded-3" name="campoBuscaUsuario" id="campoBuscaUsuarioID" placeholder="Nome" required>
                                            <label for="campoBuscaUsuarioID">Nome, número da agencia ou CEP</label>
                                        </div>         
                                        <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit" name="btnEditAgencia" id="botaoBuscaUsuario">Buscar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>';

                if(isset($_POST['campoBuscaUsuario'])){
                    $campo = $_POST['campoBuscaUsuario'];
                    try {
                        $consulta = $pdo->query("SELECT * FROM Agencia WHERE id LIKE '".$campo."%' OR nome LIKE '".$campo."%' OR cep LIKE '".$campo."%'; ");    
                        $linhas = $consulta->rowCount();
                        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                        if ($linhas > 0) {
                            do{       
                                    echo '
                        <div class="modal modal-signin position-static d-block py-1" tabindex="-1" role="dialog" id="modalSignin">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content rounded-4 shadow">
                                    <div class="modal-header p-5 pb-4 border-bottom-0">
                                        <h1 class="fw-bold mb-0 fs-2"></h1>
                                    </div>
                                    <div class="modal-body p-5 pt-0">
                                        <form action="exect.php" method="POST">
                                            <div class="form-floating mb-3">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">ID Agência</span>
                                                    <input type="text" class="form-control" value="'.$resultado['id'].'" aria-label="Username" aria-describedby="basic-addon1" name="AgenciaID" readonly>
                                                    
                                                </div>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">Nome</span>
                                                    <input type="text" class="form-control" value="'.$resultado['nome'].'" aria-label="Username" aria-describedby="basic-addon1" name="AgenciaNome" readonly>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">CEP</span>
                                                    <input type="text" class="form-control" value="'.$resultado['cep'].'" aria-label="Username" aria-describedby="basic-addon1" name="AgenciaCEP" readonly>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">Banco ID</span>
                                                    <input type="number" class="form-control" value="'.$resultado['bancoID'].'" aria-label="Username" aria-describedby="basic-addon1" name="AgenciaBancoID" readonly>
                                                </div>                                                
                                            </div>
                                            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit" name="btnEditAgencia">Editar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>';
                            }while($resultado = $consulta->fetch(PDO::FETCH_ASSOC));
                        }else{
                            echo '
                            <script>
                            let btnCadastrar = document.getElementById("botaoBuscaUsuario");
                            let divPai = btnCadastrar.parentNode;
                            let div = document.createElement("div");
                            div.classList.add("alert");
                            div.classList.add("alert-danger");
                            let text = document.createTextNode("Nenhum resultado");
                            div.appendChild(text);                                       
                            divPai.insertBefore(div, btnCadastrar.nextSibling);
                            </script>';                         
                        }
                    } catch(PDOException $e) {
                        echo 'Error: ' . $e->getMessage();
                    }
                }
                
            
            }

        ?>
    </body>
</html>