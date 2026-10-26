
<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Acesse esta página pelo formulário de cadastro.");
}

$usuario = isset($_POST["txt_usuario"])
    ? trim($_POST["txt_usuario"]) : "";

$email = isset($_POST["txt_email"])
    ? trim($_POST["txt_email"]) : "";

$senha = isset($_POST["txt_senha"])
    ? $_POST["txt_senha"] : "";

if ($usuario == "" || $email == "" || $senha == "") {
    die("Preencha todos os campos!");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Informe um e-mail válido.");
}

$usuario_sql = mysql_real_escape_string($usuario);
$email_sql = mysql_real_escape_string($email);
$senha_sql = mysql_real_escape_string($senha);

$sql = "INSERT INTO tb_login (usuario, email, senha)
        VALUES ('$usuario_sql', '$email_sql', '$senha_sql')";

if (mysql_query($sql)) {
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Conta criada</title>
        <link rel="stylesheet" href="estilo.css">
    </head>
    <body>
    <header class="topo">
        <h1>Controle de Produtos</h1>
        <p>Cadastro de usuários</p>
    </header>
    <nav class="menu">
        <a href="cadastroprod.html">Cadastrar produto</a>
        <a href="listagem.php">Listar produtos</a>
        <a href="login.html">Login</a>
    </nav>
    <main class="container">
        <section class="cartao">
            <h2>Conta criada!</h2>
            <div class="mensagem sucesso">
                Seu cadastro foi realizado com sucesso.
            </div>
            <div class="acoes">
                <a href="login.html" class="botao">Entrar na conta</a>
                <a href="cadastroprod.html" class="botao botao-azul">Voltar ao sistema</a>
            </div>
        </section>
    </main>
    <footer class="rodape">Sistema de Controle de Produtos</footer>
    </body>
    </html>
    <?php
} else {
    echo "Erro ao cadastrar a conta: " . mysql_error();
}
?>
