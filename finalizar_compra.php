<?php
session_start();
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: carrinho.php");
    exit;
}

if (empty($_SESSION["carrinho"])) {
    header("Location: carrinho.php");
    exit;
}

/*
Inicia uma transação para tentar concluir
toda a compra de uma só vez.
*/
if (!mysql_query("START TRANSACTION")) {
    die("Não foi possível iniciar a compra.");
}

$erro = false;

foreach ($_SESSION["carrinho"] as $id => $quantidade) {
    $id = (int)$id;
    $quantidade = (int)$quantidade;

    if ($id <= 0 || $quantidade <= 0) {
        $erro = true;
        break;
    }

    /*
    O desconto só acontece se houver estoque suficiente.
    */
    $sql = mysql_query(
        "UPDATE cadastro_prod
         SET qtdestoque = qtdestoque - $quantidade
         WHERE idproduto = $id
         AND qtdestoque >= $quantidade"
    );

    if (!$sql || mysql_affected_rows($conecta_db) != 1) {
        $erro = true;
        break;
    }
}

if ($erro) {
    mysql_query("ROLLBACK");

    $mensagem = "Não foi possível concluir a compra. "
              . "Um ou mais produtos podem estar sem estoque "
              . "suficiente. Confira seu carrinho.";
    $classe = "erro";
} else {
    if (!mysql_query("COMMIT")) {
        mysql_query("ROLLBACK");
        die("Erro ao confirmar a compra.");
    }

    $_SESSION["carrinho"] = array();

    $mensagem = "Compra realizada com sucesso! "
              . "O estoque foi atualizado.";
    $classe = "sucesso";
}

$mensagem = htmlspecialchars(
    $mensagem, ENT_QUOTES, "UTF-8"
);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalização da compra</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

<header class="topo">
    <h1>Marketplace</h1>
    <p>Finalização da compra</p>
</header>

<main class="container">
    <section class="cartao">
        <h2>Resultado</h2>

        <div class="mensagem <?php echo $classe; ?>">
            <?php echo $mensagem; ?>
        </div>

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