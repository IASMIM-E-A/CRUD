<?php
include("conexao.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica se o e-mail já existe
    $check = $conn->prepare("SELECT id FROM cadastro WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('E-mail já cadastrado!');</script>";
    } else {
        $sql = $conn->prepare("INSERT INTO cadastro (nome, email, senha) VALUES (?, ?, ?)");
        $sql->bind_param("sss", $nome, $email, $senha);
        if ($sql->execute()) {
            echo "<script>alert('Cadastro realizado com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta http-equiv='X-UA-Compatible' content='IE=edge'>
<title>Cadastro de Usuários</title>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<link rel="stylesheet" href="CSS/main.css">
</head>
<body>

<header>
  <h1>Cadastro de Usuários</h1>
</header>

<main>
  <section class="pag-cadastro">
    <h3>Novo Cadastro</h3>
    <form method="post">
      <div class="labels">
        <label>Nome</label>
        <input type="text" name="nome" required>

        <label>E-mail</label>
        <input type="email" name="email" required>

        <label>Senha</label>
        <input type="password" name="senha" required>
      </div>
      <button class="enviar" type="submit">Cadastrar</button>
    </form>
  </section>
</main>

<section class="opcoes">
  <button><a href="atualizar.php">Atualizar Cadastro</a></button>
  <button><a href="deletar.php">Deletar Cadastro</a></button>
  <button><a href="consultar.php">Consultar Cadastro</a></button>
</section>

<footer>
  <p>&copy; MIM e BÊ 🚀</p>
</footer>

</body>
</html>
