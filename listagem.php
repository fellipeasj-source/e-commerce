
<?php
include 'conexao.php';

$produto = isset($_POST["cadastro_produto"])
    ? trim($_POST["cadastro_produto"]) : "";

$produto_sql = mysql_real_escape_string($produto);

if ($produto != "") {
    $sql = mysql_query(
        "SELECT * FROM cadastro_prod
         WHERE descricao LIKE '$produto_sql%'
         ORDER BY descricao ASC"
    );
} else {
    $sql = mysql_query(
        "SELECT * FROM cadastro_prod ORDER BY descricao ASC"
    );
}

if (!$sql) {
    die("Erro ao consultar os produtos: " . mysql_error());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

<header class="topo">
    <h1>Controle de Produtos</h1>
    <p>Consulte os produtos cadastrados no estoque</p>
</header>

<nav class="menu">
    <a href="cadastroprod.php">Cadastrar produto</a>
    
    
    
</nav>

<main class="container">
    <section class="cartao">
        <h2>Produtos cadastrados</h2>
        <p class="subtitulo">Pesquise por descrição ou consulte todos os registros.</p>

        <form action="listagem.php" method="post">
            <div class="campo">
                <label for="cadastro_produto">Pesquisar produto</label>
                <input type="text" id="cadastro_produto"
                       name="cadastro_produto"
                       value="<?php echo htmlspecialchars($produto, ENT_QUOTES, "UTF-8"); ?>"
                       placeholder="Digite o início da descrição">
            </div>
            <div class="acoes">
                <button type="submit" class="botao">Pesquisar</button>
                <a href="listagem.php" class="botao botao-azul">Mostrar todos</a>
                <a href="cadastroprod.html" class="botao">Novo produto</a>
            </div>
        </form>

        <div class="tabela-responsiva">
            <table>
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Categoria</th>
                        <th>Valor de venda</th>
                        <th>Lucro unitário</th>
                        <th>Estoque</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $encontrou = false;

                while ($linha = mysql_fetch_assoc($sql)) {
                    $encontrou = true;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($linha["descricao"], ENT_QUOTES, "UTF-8"); ?></td>
                        <td><?php echo htmlspecialchars($linha["categoria"], ENT_QUOTES, "UTF-8"); ?></td>
                        <td>R$ <?php echo number_format((float)$linha["valorvenda"], 2, ",", "."); ?></td>
                        <td>R$ <?php echo number_format((float)$linha["lucrounit"], 2, ",", "."); ?></td>
                        <td><?php echo (int)$linha["qtdestoque"]; ?></td>
                    </tr>
                    <?php
                }

                if (!$encontrou) {
                    echo '<tr><td colspan="5">Nenhum produto encontrado.</td></tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<footer class="rodape">Sistema de Controle de Produtos</footer>

</body>
</html>
<?php
mysql_free_result($sql);
?>
