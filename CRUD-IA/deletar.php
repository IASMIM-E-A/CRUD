<?php include("conexao.php"); ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta http-equiv='X-UA-Compatible' content='IE=edge'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<title>Deletar Cadastro</title>
<link rel="stylesheet" href="CSS/deletar.css">
</head>
<body>

<header>
  <h1>Deletar Cadastro</h1>
</header>

<main>
  <?php
  // Lógica para BUSCAR o cadastro
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buscar'])) {
      $email = $_POST['email'];

      // Busca o ID APENAS pelo email
      $buscar = $conn->prepare("SELECT id FROM cadastro WHERE email=?");
      $buscar->bind_param("s", $email);
      $buscar->execute();
      $resultado = $buscar->get_result();

      if ($resultado->num_rows > 0) {
          $usuario = $resultado->fetch_assoc();
          $id = $usuario['id']; // Pega o ID para usar na deleção

          echo '<section class="pag-atualizar">
                  <h3>Confirme a senha para excluir</h3>
                  <form method="post">
                      <input type="hidden" name="id" value="'.$id.'">
                      <input type="hidden" name="email" value="'.$email.'">
                      <div class="labels">
                          <label>Senha:</label>
                          <input type="password" name="senha" required>
                      </div>
                      <button class="deletar" name="deletar" type="submit">Excluir</button>
                  </form>
                </section>';
      } else {
          echo "<script>alert('Cadastro não encontrado.'); window.location='deletar.php';</script>";
      }
  // Lógica para DELETAR o cadastro (confirmação por email e senha)
  } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deletar'])) {
      $id = $_POST['id'];
      $email = $_POST['email'];
      $senha = $_POST['senha'];

      // Verifica a senha APENAS pela combinação email + senha
      $check = $conn->prepare("SELECT id FROM cadastro WHERE email=? AND senha=?");
      $check->bind_param("ss", $email, $senha);
      $check->execute();
      $result = $check->get_result();

      if ($result->num_rows > 0) {
          // Deleta o cadastro usando o ID
          $del = $conn->prepare("DELETE FROM cadastro WHERE id=?");
          $del->bind_param("i", $id);
          $del->execute();
          echo "<script>alert('Cadastro deletado com sucesso!'); window.location='index.php';</script>";
      } else {
          echo "<script>alert('Senha incorreta.'); window.location='deletar.php';</script>";
      }
  // Lógica para exibir o formulário de busca inicial (apenas email)
  } else {
  ?>
  <section class="buscar-cadastro">
    <h3>Buscar Cadastro para Deletar</h3>
    <form method="post">
      <label>E-mail:</label>
      <input type="email" name="email" required><br>
      <button class="buscar" name="buscar" type="submit">Buscar</button>
    </form>
  </section>
  <?php } ?>
</main>

<section class="opcoes">
  <button><a href="index.php">Voltar</a></button>
</section>

<footer>
  <p>&copy; MIM e BÊ 🚀</p>
</footer>

</body>
</html>