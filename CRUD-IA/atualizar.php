<?php 
include("conexao.php"); 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta http-equiv='X-UA-Compatible' content='IE=edge'>
<title>Atualizar Cadastro</title>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<link rel="stylesheet" href="CSS/atualizar.css">
</head>
<body>

<header>
  <h1>Atualizar Cadastro</h1>
</header>

<main>
  <?php
  // Lógica para BUSCAR o cadastro (apenas por email)
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buscar'])) {
      $email = $_POST['email'];

      // Busca o ID e nome APENAS pelo email
      $buscar = $conn->prepare("SELECT id, nome FROM cadastro WHERE email=?");
      $buscar->bind_param("s", $email);
      $buscar->execute();
      $resultado = $buscar->get_result();

      if ($resultado->num_rows > 0) {
          $usuario = $resultado->fetch_assoc();
          $id = $usuario['id']; 
          $nome_atual = $usuario['nome'];
          
          echo '<section class="pag-atualizar">
                  <h3>Confirme a senha e altere os dados</h3>
                  <form method="post">
                      <input type="hidden" name="id" value="'.$id.'">
                      <input type="hidden" name="email" value="'.$email.'">

                      <div class="labels">
                          <label>Senha atual (Obrigatório para confirmar):</label>
                          <input type="password" name="senha_atual" required> <label>Novo nome (Atual: '.$nome_atual.'):</label>
                          <input type="text" name="novo_nome" value="'.$nome_atual.'" required>
                          
                          <label>Nova Senha (Deixe em branco para manter a senha atual):</label>
                          <input type="password" name="nova_senha">
                      </div>
                      <button class="salvar" name="salvar" type="submit">Salvar Alterações</button>
                  </form>
                </section>';
      } else {
          echo "<script>alert('Cadastro não encontrado.'); window.location='atualizar.php';</script>";
      }
  // Lógica para SALVAR as alterações (confirmação por email e senha)
  } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['salvar'])) {
      $id = $_POST['id'];
      $email = $_POST['email'];
      $senha_atual = $_POST['senha_atual']; // Senha atual para confirmação
      $novo_nome = $_POST['novo_nome'];
      $nova_senha = $_POST['nova_senha']; // Nova senha (pode estar vazia)

      // 1. Verifica se a senha atual está correta
      $check = $conn->prepare("SELECT id FROM cadastro WHERE email=? AND senha=?");
      $check->bind_param("ss", $email, $senha_atual);
      $check->execute();
      $result = $check->get_result();

      if ($result->num_rows > 0) {
          
          // 2. Monta a instrução SQL de UPDATE
          $sql_update = "UPDATE cadastro SET nome=?";
          $tipos = "s";
          $parametros = [$novo_nome];

          if (!empty($nova_senha)) {
              // Se o campo nova_senha foi preenchido, adiciona a atualização da senha
              $sql_update .= ", senha=?";
              $tipos .= "s";
              $parametros[] = $nova_senha;
          }

          $sql_update .= " WHERE id=?";
          $tipos .= "i";
          $parametros[] = $id;

          // 3. Executa a atualização
          $update = $conn->prepare($sql_update);
          
          // Prepara os parâmetros para bind_param
          // O array $parametros é passado por referência
          array_unshift($parametros, $tipos);
          $refs = [];
          foreach($parametros as $key => $value) {
              $refs[$key] = &$parametros[$key];
          }

          call_user_func_array([$update, 'bind_param'], $refs);
          $update->execute();
          
          echo "<script>alert('Cadastro atualizado com sucesso!'); window.location='index.php';</script>";
      } else {
          echo "<script>alert('Senha atual incorreta.'); window.location='atualizar.php';</script>";
      }
  // Lógica para exibir o formulário de busca inicial (apenas email)
  } else {
  ?>
  <section class="buscar-cadastro">
    <h3>Buscar Cadastro para Atualizar</h3>
    <form method="post">
      <label>E-mail</label>
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