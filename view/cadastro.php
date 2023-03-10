<?php 
    if (!isset($_SESSION)) session_start();
    
    //Caso o usuário esteja logado.
    if($_SESSION['login'] == true){
        header('Location: banco.php');
        exit();
    } 
?>
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

            .btn:hover, .btn:focus{
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
        </style>
    </head>
    <body>
        <div class="modal modal-signin position-static d-block  py-5" tabindex="-1" role="dialog" id="modalSignin">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-4 shadow">
                <div class="modal-header p-5 pb-4 border-bottom-0">
                    <h1 class="fw-bold mb-0 fs-2">Cadastro</h1>
                    <a href="index.html" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                </div>

                <div class="modal-body p-5 pt-0">
                    <form class="" name="formularioCadastro" method="POST">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control rounded-3" name="usuarioNome" id="usuarioID" placeholder="Nome" required onblur="verificaNome()" onchange="verificaNome()" onkeypress="verificaNome()">
                        <label for="usuarioID">Nome</label>
                        <p id="pusuario" style="color:red; font-weight: bold; margin: 5px;"></p>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control rounded-3" name="emailNome" id="emailID" placeholder="name@example.com" required onblur="validarEmail()" onchange="validarEmail()">
                        <label for="emailID">E-mail</label>
                        <p id="pemail" style="color:red; font-weight: bold; margin: 5px;"></p>
                    </div>
                    <div class="form-floating mb-3">                          
                        <input type="password" class="form-control rounded-3" name="senha1Nome" id="senha1ID" placeholder="Password" required onblur="verificaSenha()" onchange="verificaSenha()" onkeyup="verificaSenha()">
                        <label for="senha1ID">Senha</label>    
                        <p id="psenha" style="color:red; font-weight: bold; margin: 5px;"></p>
                    </div>
                    <div class="form-floating mb-3" id="divConfirmarSenha">
                        <input type="password" class="form-control rounded-3" name="senha2Nome" id="senha2ID" placeholder="Password" required onblur="verificaSenha()" onchange="verificaSenha()">                        
                        <label for="senha2ID">Confirmar senha</label>
                        <p id="psenha2" style="color:red; font-weight: bold; margin: 5px;"></p>
                    </div>
                    <button class="btn w-100 btn-outline-info btn-lg px-4 fw-bold" type="submit" id="btnCadastrarID" name="btnCadastrar">Cadastrar</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
        <script>
            function tamanho(){                                
                let nome = document.getElementById('usuarioID');
                let email = document.getElementById('emailID');
                let senha = document.getElementById('senha1ID');
                if(nome.value.length > 199){
                    nome.value = nome.value.substring(0, 199);
                }
                if(email.value.length > 199){
                    email.value = email.value.substring(0, 199);
                }
                if(senha.value.length > 30){
                    senha.value = senha.value.substring(0, 30);
                }

            }

            function verificaNome(){
                setTimeout("tamanho()",1);
                let nome = document.getElementById('usuarioID');
                if(nome.value.length < 6){
                    document.getElementById('pusuario').innerHTML = "Informe nome e sobrenome";
                    nome.classList.add("is-invalid");
                    return false;
                }else{
                    document.getElementById('pusuario').innerHTML = "";
                    nome.classList.remove("is-invalid");
                    return true;
                }
                 
            }

            function validarEmail() {
                setTimeout("tamanho()",1);
                let email = document.getElementById('emailID');
                var re = /\S+@\S+\.\S+/;
                if(re.test(email.value) == false){
                    document.getElementById('pemail').innerHTML = "Informe um e-mail válido";
                    email.classList.add("is-invalid");
                    return false;
                }else{
                    document.getElementById('pemail').innerHTML = "";
                    email.classList.remove("is-invalid");
                    return true;
                }
            }

            function verificaSenha(){
                setTimeout("tamanho()",1);
                let senha1 = document.getElementById('senha1ID');
                let senha2 = document.getElementById('senha2ID');
                if(senha1.value.length < 6){
                    document.getElementById('psenha').innerHTML = "A senha precisa ter no mínimo 6 e no máximo 30 caracteres";
                    senha1.classList.add("is-invalid");
                }else{
                    document.getElementById('psenha').innerHTML = "";
                    senha1.classList.remove("is-invalid");
                }
                if(senha1.value != senha2.value){
                    document.getElementById('psenha2').innerHTML = "Senhas não são iguais";
                    senha2.classList.add("is-invalid");
                    return false;
                }else{
                    document.getElementById('psenha2').innerHTML = "";
                    senha2.classList.remove("is-invalid");
                    return true;
                }
            }
            
        </script>
    </body>
</html>

<?php
	include_once('../controller/conexao.php');    

    //Caso o usuário clique no botão cadastrar.
    if(!isset($_POST['btnCadastrar'])){
        exit();
    }
    
    //Pego os dados inseridos.
    $nome = $_POST['usuarioNome'];
    $email = $_POST['emailNome'];
    $senha1 = md5($_POST['senha1Nome']);
    $senha2 = md5($_POST['senha2Nome']);

    //Verificações de tamanho e igualdade.
    if(strlen($nome) >= 200){
        echo '<script>
                let btnCadastrar = document.getElementById("btnCadastrarID");
                let divPai = btnCadastrar.parentNode;
                let div = document.createElement("div");
                div.classList.add("alert");
                div.classList.add("alert-danger");
                let text = document.createTextNode("Nome muito grande!");
                div.appendChild(text);                                       
                divPai.insertBefore(div, btnCadastrar.nextSibling);
                </script>';                        
                exit();
    }
    if(strlen($email) >= 200){
        echo '<script>
                let btnCadastrar = document.getElementById("btnCadastrarID");
                let divPai = btnCadastrar.parentNode;
                let div = document.createElement("div");
                div.classList.add("alert");
                div.classList.add("alert-danger");
                let text = document.createTextNode("E-mail muito grande!");
                div.appendChild(text);                                       
                divPai.insertBefore(div, btnCadastrar.nextSibling);
                </script>';                        
                exit();
    }
    if($senha1 != $senha2){
        echo '<script>
                let btnCadastrar = document.getElementById("btnCadastrarID");
                let divPai = btnCadastrar.parentNode;
                let div = document.createElement("div");
                div.classList.add("alert");
                div.classList.add("alert-danger");
                let text = document.createTextNode("As senhas não são iguais!");
                div.appendChild(text);                                       
                divPai.insertBefore(div, btnCadastrar.nextSibling);
                </script>';                        
                exit();
    }

    try {
	//Verificando se o e-mail já está cadastrado.
        $consulta = $pdo->query("SELECT * FROM Usuarios WHERE (`email` = '".$email ."');");    
        $linhas = $consulta->rowCount();
        if ($linhas > 0) {
            echo '<script>
                let btnCadastrar = document.getElementById("btnCadastrarID");
                let divPai = btnCadastrar.parentNode;
                let div = document.createElement("div");
                div.classList.add("alert");
                div.classList.add("alert-danger");
                let text = document.createTextNode("E-mail já possui conta!");
                div.appendChild(text);                                       
                divPai.insertBefore(div, btnCadastrar.nextSibling);
                </script>';
                exit();
        }    
    } catch(PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }

    //Insiros as informações no banco de dados.
    try{
        $stmt = $pdo->prepare('INSERT INTO Usuarios (nome, email, senha, nivel, saldo) VALUES (:nome, :email, :senha, 1, 0)');
        $stmt->execute(array(
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => $senha1
        ));

        echo '<script>
                let btnCadastrar = document.getElementById("btnCadastrarID");
                let divPai = btnCadastrar.parentNode;
                let div = document.createElement("div");
                div.classList.add("alert");
                div.classList.add("alert-success");
                let text = document.createTextNode("Cadastrado com sucesso! Redirecionando para o login em 5 segundos...");
                div.appendChild(text);                                       
                divPai.insertBefore(div, btnCadastrar.nextSibling);
                setTimeout(function(){
                    window.location.href = "login.php";
                }, 5000);
              </script>';
        exit();

    } catch(PDOException $e){
        echo 'Error: ' . $e->getMessage();
        exit();
    }

?>
