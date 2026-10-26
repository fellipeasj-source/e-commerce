<?php
include 'verificar_admin.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

<header class="topo">
    <h1>Controle de Produtos</h1>
    <p>Sistema de cadastro e gerenciamento de estoque</p>
</header>

<nav class="menu">
    <a href="marketplace.php">Início</a>
    <a href="listagem.php">Listar produtos</a>
    
    
</nav>

<main class="container">
    <section class="cartao">
        <h2>Novo produto</h2>
        <p class="subtitulo">Preencha os dados para cadastrar um produto no estoque.</p>

        <form action="gravar.php" method="post" enctype="multipart/form-data">
            <div class="campo">
                <label for="descricao">Descrição do produto</label>
                <input type="text" id="descricao" name="descricao"
                       maxlength="150" required>
            </div>

            <div class="campo">
                <label for="categoria">Categoria</label>
                <input type="text" id="categoria" name="categoria"
                       maxlength="100" required>
            </div>

            <div class="campo">
                <label for="valorcompra">Valor de compra (R$)</label>
                <input type="number" id="valorcompra" name="valorcompra"
                       min="0" step="0.01" placeholder="Ex.: 100.99" required>
            </div>

            <div class="campo">
                <label for="valorvenda">Valor de venda (R$)</label>
                <input type="number" id="valorvenda" name="valorvenda"
                       min="0" step="0.01" placeholder="Ex.: 149.90" required>
            </div>

            <div class="campo">
                <label for="qtdestoque">Quantidade em estoque</label>
                <input type="number" id="qtdestoque" name="qtdestoque"
                       min="0" step="1" required>
            </div>

            <div class="campo">
                <label for="imagem">Imagem do produto</label>
                <input type="file" id="imagem" name="imagem"
                    accept="image/jpeg,image/png,image/webp" required>
            </div>

            <div class="acoes">
                <button type="submit" class="botao">Cadastrar produto</button>
                <a href="listagem.php" class="botao botao-azul">Ver produtos</a>
            </div>
        </form>
    </section>
</main>

<footer class="rodape">Sistema de Controle de Produtos</footer>

</body>
</html>
