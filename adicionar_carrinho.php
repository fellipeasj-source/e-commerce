<?php
session_start();
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: marketplace.php");
    exit;
}

$id = isset($_POST["idproduto"])
    ? (int)$_POST["idproduto"] : 0;

if ($id <= 0) {
    die("Produto inválido.");
}

$sql = mysql_query(
    "SELECT qtdestoque FROM cadastro_prod
     WHERE idproduto = $id"
);

if (!$sql || mysql_num_rows($sql) == 0) {
    die("Produto não encontrado.");
}

$produto = mysql_fetch_assoc($sql);
mysql_free_result($sql);

if ((int)$produto["qtdestoque"] <= 0) {
    die("Este produto está sem estoque.");
}

if (!isset($_SESSION["carrinho"])) {
    $_SESSION["carrinho"] = array();
}

$quantidadeAtual = isset($_SESSION["carrinho"][$id])
    ? (int)$_SESSION["carrinho"][$id] : 0;

if ($quantidadeAtual + 1 > (int)$produto["qtdestoque"]) {
    die("Você já adicionou ao carrinho todas as unidades disponíveis.");
}

$_SESSION["carrinho"][$id] = $quantidadeAtual + 1;

header("Location: carrinho.php");
exit;
?>