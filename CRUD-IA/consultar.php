<?php 
include("conexao.php"); 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta http-equiv='X-UA-Compatible' content='IE=edge'>
<title>Consultar Cadastro</title>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<link rel="stylesheet" href="CSS/consultar.css">
<style>
/* Estilo adicional para o botão de visualização */
.senha-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
}

.senha-container p {
    flex-grow: 1; /* Permite que o texto da senha ocupe o espaço */
}

.toggle-senha {
    background-color: #5BC0DE; /* Azul claro */
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.8em;
    white-space: nowrap; /* Evita que o texto do botão quebre */
}

.toggle-senha:hover {
    background-color: #3b9ac8;
}
</style>
</head>
<body>

<header>
  <h1>Consultar Cadastro</h1>
</header>

<main>
  <?php
  // Lógica para BUSCAR o cadastro
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buscar'])) {
      $email = $_POST['email'];

      // Busca todas as informações pelo email
      $buscar = $conn->prepare("SELECT nome, email, senha FROM cadastro WHERE email=?");
      $buscar->bind_param("s", $email);
      $buscar->execute();
      $resultado = $buscar->get_result();

      if ($resultado->num_rows > 0) {
          $usuario = $resultado->fetch_assoc();
          
          // Versão censurada (para exibição inicial)
          $senha_censurada = str_repeat('*', strlen($usuario['senha']));
          
          // Versão real (armazenada em um atributo de dados)
          $senha_real = htmlspecialchars($usuario['senha']);
          
          echo '<section class="dados-cadastro">
                  <h3>Dados do Cadastro Encontrado</h3>
                  <div class="info-item">
                      <label>Nome:</label>
                      <p>'.$usuario['nome'].'</p>
                  </div>
                  <div class="info-item">
                      <label>E-mail:</label>
                      <p>'.$usuario['email'].'</p>
                  </div>
                  <div class="info-item">
                      <label>Senha:</label>
                      <div class="senha-container">
                          <p id="senhaExibida" data-real-senha="'.$senha_real.'">'.$senha_censurada.'</p>
                          <button class="toggle-senha" onclick="toggleSenha()">Mostrar Senha</button>
                      </div>
                  </div>
                </section>
                
                <script>
                // Função JavaScript para alternar a exibição da senha
                function toggleSenha() {
                    const elementoSenha = document.getElementById("senhaExibida");
                    const botao = document.querySelector(".toggle-senha");
                    const senhaCensurada = "'. $senha_censurada .'";
                    
                    // Pega a senha real do atributo de dados
                    const senhaReal = elementoSenha.getAttribute("data-real-senha");

                    if (elementoSenha.textContent === senhaCensurada) {
                        // Se estiver censurada, mostra a senha real
                        elementoSenha.textContent = senhaReal;
                        botao.textContent = "Esconder Senha";
                    } else {
                        // Se estiver real, volta a mostrar a senha censurada
                        elementoSenha.textContent = senhaCensurada;
                        botao.textContent = "Mostrar Senha";
                    }
                }
                </script>';
      } else {
          echo "<script>alert('Cadastro não encontrado.'); window.location='consultar.php';</script>";
      }
  } else {
  // Lógica para exibir o formulário de busca inicial
  ?>
  <section class="buscar-cadastro">
    <h3>Buscar Cadastro para Consulta</h3>
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