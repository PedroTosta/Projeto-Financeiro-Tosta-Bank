<?php ob_start(); ?>
<?php    
    include_once('action.php');
    $username = 'epiz_32979791';
    $password = 'Ewo1wL4SeDSRe';
    try{
		$pdo = new PDO('mysql:host=sql110.epizy.com;dbname=epiz_32979791_ProjetoFinanceiro;charset=utf8',$username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(Exception $e){
        echo "Erro: ".$e;
    }
    ob_end_flush();
?>