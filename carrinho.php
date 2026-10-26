<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION["carrinho"])) {
    $_SESSION["carrinho"] = array();
}

/* Remover um produto do carrinho */
if (isset($_POST["remover"])) {
    $idRemover = (int)$_POST["remover"];

    unset($_SESSION["carrinho"][$idRemover]);

    header("Location: carrinho.php");
    exit;
}

/* Atualizar a quantidade desejada */
if (isset($_POST["atualizar_id"])) {
    $id = (int)$_POST["atualizar_id"];
    $quantidade = isset($_POST["quantidade"])
        ? (int)$_POST["quantidade"] : 1;

    if ($quantidade <= 0) {
        unset($_SESSION["carrinho"][$id]);
    } else {
        $consulta = mysql_query(
            "SELECT qtdestoque FROM cadastro_prod
             WHERE idproduto = $id"
        );

        if ($consulta && mysql_num_rows($consulta) > 0) {
            $produtoAtual = mysql_fetch_assoc($consulta);

            if ($quantidade <= (int)$produtoAtual["qtdestoque"]) {
                $_SESSION["carrinho"][$id] = $quantidade;
            } else {
                $_SESSION["aviso"] =
                    "Quantidade maior que o estoque disponível.";
            }

            mysql_free_result($consulta);
        }
    }

    header("Location: carrinho.php");
    exit;
}

$total = 0;
$totalItens = array_sum($_SESSION["carrinho"]);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Carrinho</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

<header class="topo">
    <h1>Meu carrinho</h1>
    <p>Confira os produtos antes de comprar</p>
</header>

<nav class="menu">
    <a href="marketplace.php">Continuar comprando</a>
    <a href="carrinho.php">Meu carrinho</a>
</nav>

<main class="container">
    <section class="cartao">
        <h2>Produtos selecionados</h2>

        <?php
        if (isset($_SESSION["aviso"])) {
            echo '<div class="mensagem erro">'
                . htmlspecialchars($_SESSION["aviso"], ENT_QUOTES, "UTF-8")
                . '</div>';
            unset($_SESSION["aviso"]);
        }
        ?>

        <?php if (empty($_SESSION["carrinho"])) { ?>

            <div class="mensagem">
                Seu carrinho está vazio.
            </div>

            <a href="marketplace.php" class="botao">
                Ver produtos
            </a>

        <?php } else { ?>

            <div class="tabela-responsiva">
                <table>
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Preço unitário</th>
                            <th>Quantidade</th>
                            <th>Subtotal</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php
                    foreach ($_SESSION["carrinho"] as $id => $quantidade) {
                        $id = (int)$id;
                        $quantidade = (int)$quantidade;

                        $consulta = mysql_query(
                            "SELECT descricao, valorvenda, qtdestoque
                             FROM cadastro_prod
                             WHERE idproduto = $id"
                        );

                        if (!$consulta || mysql_num_rows($consulta) == 0) {
                            unset($_SESSION["carrinho"][$id]);
                            continue;
                        }

                        $produto = mysql_fetch_assoc($consulta);
                        mysql_free_result($consulta);

                        $nome = htmlspecialchars(
                            $produto["descricao"], ENT_QUOTES, "UTF-8"
                        );

                        $preco = (float)$produto["valorvenda"];
                        $subtotal = $preco * $quantidade;
                        $total += $subtotal;
                        ?>

                        <tr>
                            <td><?php echo $nome; ?></td>

                            <td>
                                R$ <?php echo number_format($preco, 2, ",", "."); ?>
                            </td>

                            <td>
                                <form method="post" action="carrinho.php">
                                    <input type="hidden" name="atualizar_id"
                                           value="<?php echo $id; ?>">

                                    <input type="number" name="quantidade"
                                           min="0"
                                           max="<?php echo (int)$produto["qtdestoque"]; ?>"
                                           value="<?php echo $quantidade; ?>"
                                           style="width:70px;">

                                    <button type="submit" class="botao botao-azul">
                                        Atualizar
                                    </button>
                                </form>
                            </td>

                            <td>
                                R$ <?php echo number_format($subtotal, 2, ",", "."); ?>
                            </td>

                            <td>
                                <form method="post" action="carrinho.php">
                                    <button type="submit" name="remover"
                                            value="<?php echo $id; ?>"
                                            class="botao">
                                        Remover
                                    </button>
                                </form>
                            </td>
                        </tr>

                    <?php } ?>

                    </tbody>
                </table>
            </div>

            <h2>
                Total: R$ <?php echo number_format($total, 2, ",", "."); ?>
            </h2>

            <form action="finalizar_compra.php" method="post"
                  onsubmit="return confirm('Deseja confirmar a compra?');">
                <button type="submit" class="botao">
                    Finalizar compra
                </button>
            </form>

        <?php } ?>
    </section>
</main>

<footer class="rodape">Marketplace — Sua loja virtual</footer>

</body>
</html>