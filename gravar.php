<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Acesse esta página pelo formulário.");
}

$descricao = isset($_POST["descricao"])
    ? trim($_POST["descricao"]) : "";

$categoria = isset($_POST["categoria"])
    ? trim($_POST["categoria"]) : "";

$valorcompra = isset($_POST["valorcompra"])
    ? str_replace(",", ".", $_POST["valorcompra"]) : "";

$valorvenda = isset($_POST["valorvenda"])
    ? str_replace(",", ".", $_POST["valorvenda"]) : "";

$qtdestoque = isset($_POST["qtdestoque"])
    ? $_POST["qtdestoque"] : "";

if ($descricao == "" || $categoria == "" ||
    $valorcompra == "" || $valorvenda == "" ||
    $qtdestoque == "") {
    die("Preencha todos os campos.");
}

if (!is_numeric($valorcompra) ||
    !is_numeric($valorvenda) ||
    !is_numeric($qtdestoque) ||
    $valorcompra < 0 || $valorvenda < 0 ||
    $qtdestoque < 0 || floor($qtdestoque) != $qtdestoque) {
    die("Confira os valores informados.");
}

/* Verifica se uma imagem foi enviada */
if (!isset($_FILES["imagem"]) ||
    $_FILES["imagem"]["error"] != UPLOAD_ERR_OK) {
    die("Selecione uma imagem válida.");
}

/* Limita o tamanho a 5 MB */
if ($_FILES["imagem"]["size"] > 5 * 1024 * 1024) {
    die("A imagem deve ter no máximo 5 MB.");
}

/* Confere se o arquivo é realmente uma imagem */
$infoImagem = getimagesize($_FILES["imagem"]["tmp_name"]);

if ($infoImagem === false) {
    die("O arquivo selecionado não é uma imagem válida.");
}

/* Aceita somente JPEG, PNG e WEBP */
$tiposPermitidos = array(
    "image/jpeg" => "jpg",
    "image/png"  => "png",
    "image/webp" => "webp"
);

$tipoImagem = $infoImagem["mime"];

if (!isset($tiposPermitidos[$tipoImagem])) {
    die("Use uma imagem JPG, PNG ou WEBP.");
}

/* Cria um nome único para evitar sobrescrever outras imagens */
$nomeImagem = uniqid("produto_", true)
    . "." . $tiposPermitidos[$tipoImagem];

$pastaImagens = __DIR__ . DIRECTORY_SEPARATOR . "imagens";

if (!is_dir($pastaImagens)) {
    die("A pasta imagens não existe. Crie-a na pasta do projeto.");
}

$caminhoImagem = $pastaImagens
    . DIRECTORY_SEPARATOR . $nomeImagem;

/* Move o arquivo enviado para a pasta imagens */
if (!move_uploaded_file(
    $_FILES["imagem"]["tmp_name"],
    $caminhoImagem
)) {
    die("Não foi possível salvar a imagem.");
}

/* Calcula o lucro de uma unidade */
$valorcompra = (float)$valorcompra;
$valorvenda = (float)$valorvenda;
$qtdestoque = (int)$qtdestoque;

$lucrounit = $valorvenda - $valorcompra;

/* Prepara os textos para a consulta */
$descricao_sql = mysql_real_escape_string($descricao);
$categoria_sql = mysql_real_escape_string($categoria);
$imagem_sql = mysql_real_escape_string($nomeImagem);

/* Salva os dados e o nome da imagem no banco */
$sql = "INSERT INTO cadastro_prod
        (descricao, categoria, valorcompra, valorvenda,
         qtdestoque, lucrounit, imagem)
        VALUES
        ('$descricao_sql', '$categoria_sql', $valorcompra,
         $valorvenda, $qtdestoque, $lucrounit, '$imagem_sql')";

if (mysql_query($sql)) {
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Produto cadastrado</title>
        <link rel="stylesheet" href="estilo.css">
    </head>
    <body>
        <header class="topo">
            <h1>Marketplace</h1>
            <p>Cadastro de produtos</p>
        </header>

        <main class="container">
            <section class="cartao">
                <h2>Produto cadastrado com sucesso!</h2>

                <div class="mensagem sucesso">
                    O produto foi salvo com sua imagem.
                </div>

                <div class="acoes">
                    <a href="cadastroprod.php" class="botao">
                        Cadastrar outro
                    </a>

                    <a href="marketplace.php" class="botao botao-azul">
                        Ver marketplace
                    </a>
                </div>
            </section>
        </main>
    </body>
    </html>
    <?php
} else {
    /* Se o banco falhar, remove a imagem que acabou de ser enviada */
    if (file_exists($caminhoImagem)) {
        unlink($caminhoImagem);
    }

    die("Erro ao cadastrar o produto: " . mysql_error());
}
?>