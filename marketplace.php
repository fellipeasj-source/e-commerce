<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION["carrinho"])) {
    $_SESSION["carrinho"] = array();
}

$sql = mysql_query(
    "SELECT idproduto, descricao, categoria,
            valorvenda, qtdestoque, imagem
     FROM cadastro_prod
     ORDER BY descricao ASC"
);

if (!$sql) {
    die("Erro ao carregar produtos: " . mysql_error());
}

$totalItens = array_sum($_SESSION["carrinho"]);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
    <link rel="stylesheet" href="estilo.css">

    <style>
        .vitrine {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 22px;
            margin-top: 25px;
        }

        .produto {
            background: white;
            border: 1px solid #d6deea;
            border-radius: 12px;
            overflow: hidden;
        }

        .produto-imagem {
            width: 100%;
            height: 190px;
            object-fit: contain;
            padding: 12px;
        }

        .produto-info {
            padding: 18px;
            border-top: 1px solid #edf0f5;
        }

        .produto-info h3 {
            color: #164e8a;
            margin: 8px 0;
        }

        .preco {
            font-size: 23px;
            font-weight: bold;
            color: #164e8a;
        }

        .categoria, .estoque {
            color: #66758a;
            font-size: 13px;
        }

        .produto form {
            margin-top: 10px;
        }

        .produto button {
            width: 100%;
        }

        .carrinho-link {
            background: #f28c28;
            color: white !important;
        }

        .carrinho-link:hover {
            background: #d97412 !important;
        }
    </style>
</head>
<body>

<header class="topo">
    <h1>Marketplace</h1>
    <p>Encontre os produtos que você procura</p>
</header>

<nav class="menu">
    <a href="marketplace.php">Início</a>
    <a href='login.html'>Sair</a>

    <?php if (isset($_SESSION["tipo"]) &&
              $_SESSION["tipo"] === "admin") { ?>

        <a href="cadastroprod.php">Cadastrar produto</a>
        <a href="listagem.php">Gerenciar estoque</a>

    <?php } ?>

    <a href="carrinho.php">Meu carrinho</a>

    <?php if (isset($_SESSION["email"])) { ?>

        <span>
            Olá, <?php
            echo htmlspecialchars(
                $_SESSION["usuario"],
                ENT_QUOTES,
                "UTF-8"
            );
            ?>
        </span>

    <?php } else { ?>

        <a href="login.html">Entrar</a>

    <?php } ?>
</nav>

<main class="container">

    <h2 style="color:#164e8a;">Nossos produtos</h2>
    <p class="subtitulo">Escolha seus produtos favoritos.</p>

    <div class="vitrine">

    <?php
    $encontrou = false;

    while ($produto = mysql_fetch_assoc($sql)) {
        $encontrou = true;

        $id = (int)$produto["idproduto"];
        $descricao = htmlspecialchars(
            $produto["descricao"], ENT_QUOTES, "UTF-8"
        );
        $categoria = htmlspecialchars(
            $produto["categoria"], ENT_QUOTES, "UTF-8"
        );
        $preco = number_format(
            (float)$produto["valorvenda"], 2, ",", "."
        );
        $imagem = basename($produto["imagem"]);
        ?>

        <article class="produto">

            <?php
            if ($imagem != "" &&
                file_exists(__DIR__ . "/imagens/" . $imagem)) {
                ?>
                <img
                    class="produto-imagem"
                    src="imagens/<?php echo rawurlencode($imagem); ?>"
                    alt="<?php echo $descricao; ?>"
                >
                <?php
            } else {
                ?>
                <div class="produto-imagem"
                     style="display:flex;align-items:center;justify-content:center;">
                    Imagem indisponível
                </div>
                <?php
            }
            ?>

            <div class="produto-info">

                <span class="categoria">
                    <?php echo $categoria; ?>
                </span>

                <h3><?php echo $descricao; ?></h3>

                <p class="preco">R$ <?php echo $preco; ?></p>

                <p class="estoque">
                    Estoque: <?php echo (int)$produto["qtdestoque"]; ?>
                </p>

                <?php if ((int)$produto["qtdestoque"] > 0) { ?>

                    <form action="adicionar_carrinho.php" method="post">
                        <input type="hidden" name="idproduto"
                               value="<?php echo $id; ?>">

                        <button type="submit" class="botao botao-azul">
                            Adicionar ao carrinho
                        </button>
                    </form>

                    <form action="comprar.php" method="post"
                          onsubmit="return confirm('Comprar uma unidade deste produto?');">
                        <input type="hidden" name="idproduto"
                               value="<?php echo $id; ?>">

                        <button type="submit" class="botao">
                            Comprar agora
                        </button>
                    </form>

                <?php } else { ?>

                    <button class="botao" disabled
                            style="background:#999;cursor:not-allowed;">
                        Sem estoque
                    </button>

                <?php } ?>

            </div>
        </article>

        <?php
    }

    if (!$encontrou) {
        echo "<p>Nenhum produto cadastrado.</p>";
    }

    mysql_free_result($sql);
    ?>

    </div>
</main>

<footer class="rodape">Marketplace — Sua loja virtual</footer>

</body>
</html>