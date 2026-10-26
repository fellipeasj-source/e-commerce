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

/* Busca o nome do produto */
$consulta = mysql_query(
    "SELECT descricao FROM cadastro_prod
     WHERE idproduto = $id"
);

if (!$consulta || mysql_num_rows($consulta) == 0) {
    die("Produto não encontrado.");
}

$produto = mysql_fetch_assoc($consulta);
$nomeProduto = $produto["descricao"];

mysql_free_result($consulta);

/*
Diminui o estoque somente se ainda houver unidades.
A operação é feita diretamente no banco para evitar
vender uma unidade que já não está disponível.
*/
$sql = mysql_query(
    "UPDATE cadastro_prod
     SET qtdestoque = qtdestoque - 1
     WHERE idproduto = $id
     AND qtdestoque > 0"
);

if (!$sql) {
    die("Erro ao processar a compra: " . mysql_error());
}

if (mysql_affected_rows($conecta_db) == 1) {
    $mensagem = "Compra realizada com sucesso!";
    $classe = "sucesso";
} else {
    $mensagem = "Produto sem estoque disponível.";
    $classe = "erro";
}

$nomeProduto = htmlspecialchars(
    $nomeProduto, ENT_QUOTES, "UTF-8"
);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado da compra</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

<header class="topo">
    <h1>Marketplace</h1>
    <p>Resultado da compra</p>
</header>

<main class="container">
    <section class="cartao">
        <h2>Compra</h2>

        <div class="mensagem <?php echo $classe; ?>">
            <?php echo $mensagem; ?>
        </div>

        <p>Produto: <strong><?php echo $nomeProduto; ?></strong></p>

        <div class="acoes">
            <a href="marketplace.php" class="botao">
                Voltar ao marketplace
            </a>

            <a href="carrinho.php" class="botao botao-azul">
                Ver carrinho
            </a>
        </div>
    </section>
</main>

<footer class="rodape">Marketplace — Sua loja virtual</footer>

</body>
</html>