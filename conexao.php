<?php
$servidor = "127.0.0.1";
$usuario = "root";
$senha = "usbw";
$banco = "cadastro_produto";

$conecta_db = mysql_connect($servidor, $usuario, $senha)
    or die("Erro ao conectar ao MySQL: " . mysql_error());

mysql_select_db($banco, $conecta_db)
    or die("Erro ao selecionar o banco: " . mysql_error());
?>
