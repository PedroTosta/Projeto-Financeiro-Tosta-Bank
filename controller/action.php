<?php
include_once('conexao.php');

function criarUsuario($pdo, $nome, $email, $senha){
    $stmt = $pdo->prepare('INSERT INTO Usuarios(nome, email, senha, nivel) VALUES(:nome,:email,:senha, 1);');
    $stmt->execute(array(
        ':nome' => $nome,
        ':email' => $email,                
        ':senha' => $pass));
    return header("Location: ../view/login.php");
}

?>