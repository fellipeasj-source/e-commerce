<?php
session_start();
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: login.html");
    exit;
}

$email = isset($_POST["txt_email"])
    ? trim($_POST["txt_email"]) : "";

$senha = isset($_POST["txt_senha"])
    ? $_POST["txt_senha"] : "";

if ($email == "" || $senha == "") {
    die("Preencha o e-mail e a senha.");
}

$email_sql = mysql_real_escape_string($email);
$senha_sql = mysql_real_escape_string($senha);

$sql = mysql_query(
    "SELECT usuario, email, tipo
     FROM tb_login
     WHERE email = '$email_sql'
     AND senha = '$senha_sql'
     LIMIT 1"
);

if (!$sql) {
    die("Erro ao verificar login: " . mysql_error());
}

if (mysql_num_rows($sql) > 0) {

    $conta = mysql_fetch_assoc($sql);

    session_regenerate_id(true);

    $_SESSION["email"] = $conta["email"];
    $_SESSION["usuario"] = $conta["usuario"];
    $_SESSION["tipo"] = $conta["tipo"];

    mysql_free_result($sql);

    if ($conta["tipo"] === "admin") {
        header("Location: cadastroprod.php");
        exit;
    } else {
        header("Location: marketplace.php");
        exit;
    }

} else {
    mysql_free_result($sql);

    echo "<!DOCTYPE html>
    <html lang='pt-BR'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Erro no login</title>
        <link rel='stylesheet' href='estilo.css'>
    </head>
    <body>
        <main class='container'>
            <section class='cartao'>
                <h2>Não foi possível entrar</h2>
                <div class='mensagem erro'>
                    E-mail ou senha incorretos.
                </div>
                <a href='login.html' class='botao'>
                    Tentar novamente
                </a>
            </section>
        </main>
    </body>
    </html>";
}
?>