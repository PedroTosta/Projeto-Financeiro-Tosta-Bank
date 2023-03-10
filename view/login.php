<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>TostaBANK</title>
        <meta charset="UTF-8">
        <link rel="icon" href="../image/moeda.png" >
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">        
        <style>
            /*
            .btn{                
                position: relative;
                transition: all 100ms ease-in-out;
                top: 0;    
                box-shadow: 0 0.6em #0dcaf0, 0 0.9em rgba(0, 0, 0, 0.4);
            }
            .btn:hover, btn:focus{
                position: relative;
                background-color: white;
                top: 0.2em;
                box-shadow: 0 0.4em #0dcaf0, 0 0.7em rgba(0, 0, 0, 0.4);
            }

            .btn:active{
                top: 0.4em;
                box-shadow: 0em 0.2em #0dcaf0, 0 0.5em rgba(0, 0, 0, 0.4);
            }
            */
            .alert-danger{
                margin-top: 10px;
            }
        </style>
    </head>
    <body>
        <div class="modal modal-signin position-static d-block  py-5" tabindex="-1" role="dialog" id="modalSignin">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-4 shadow">
                <div class="modal-header p-5 pb-4 border-bottom-0">
                    <h1 class="fw-bold mb-0 fs-2">Login</h1>
                    <a href="index.html" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                </div>
                <div class="modal-body p-5 pt-0">
                    <form class="" name="formularioLogin" method="POST">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control rounded-3" name="emailNome" id="emailID" placeholder="nome@dominio.com" required>
                        <label for="emailID">E-mail</label>
                        <p id="pemail" style="color:red; font-weight: bold; margin: 5px;"></p>
                    </div>
                    <div class="form-floating mb-3">                          
                        <input type="password" class="form-control rounded-3" name="senhaNome" id="senhaID" placeholder="Senha123" required>
                        <label for="senhaID">Senha</label>    
                        <p id="psenha" style="color:red; font-weight: bold; margin: 5px;"></p>
                    </div>                    
                    <button class="btn w-100 btn-outline-info btn-lg px-4 fw-bold" type="submit" id="btnLoginID" name="btnLogin">Login</button>
                    </form>
                </div>                
                </div>
            </div>
        </div>
    </body>
</html>

<?php
    include_once('../controller/conexao.php');

    if (!isset($_SESSION)) session_start();
    
    //Verifico se o usu??rio est?? logado.
    if($_SESSION['login'] == true){ 
        header('Location: banco.php');
        exit();
    }    

    //Verifico se o usu??rio clicou no bot??o login.
    if(!isset($_POST['btnLogin'])){
        exit();
    }
    
    //Pego e-mail e a senha.
    $email = $_POST['emailNome'];
    $senha = md5($_POST['senhaNome']);
    try {
        $consulta = $pdo->query("SELECT * FROM Usuarios WHERE (`email` = '".$email ."') AND (`senha` = '".$senha ."');");    
        $linhas = $consulta->rowCount();	    
	//Verifico se com base no e-mail e senha possui algo cadastrado no banco de dados, se caso encontrar uma linha de resultado, continuar o login.
	    
        if ($linhas == 1) {
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $_SESSION['login'] = true;
            $_SESSION['UsuarioID'] = $resultado['id'];
            $_SESSION['UsuarioNome'] = $resultado['nome'];
            $_SESSION['UsuarioEmail'] = $resultado['email'];
            $_SESSION['UsuarioNivel'] = $resultado['nivel'];
            $_SESSION['UsuarioBanco'] = $resultado['bancoID'];
            $_SESSION['UsuarioAgencia'] = $resultado['agenciaID'];
            header('Location: banco.php');
            exit();
	    //Pego os atributos do BD, coloco na sess??o e redireciono para p??gina principal.
        }else{
            echo '<script>
                let btnLogin = document.getElementById("btnLoginID");
                let divPai = btnLogin.parentNode;
                let div = document.createElement("div");
                div.classList.add("alert");
                div.classList.add("alert-danger");
                let text = document.createTextNode("Dados incorretos!");
                div.appendChild(text);                                       
                divPai.insertBefore(div, btnLoginID.nextSibling);
                </script>';                
		//Caso n??o encontre nada no BD com as informa????es inseridas, informe um erro.
        }
    } catch(PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }

?>
