<?php
require '../config.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo         = trim($_POST['titulo']);
    $genero         = trim($_POST['genero']);
    $ano_publicacao = $_POST['ano_publicacao'] !== '' ? (int)$_POST['ano_publicacao'] : null;
    $isbn           = trim($_POST['isbn']);

    // Usa prepared statement para evitar SQL Injection
    $stmt = $conn->prepare("INSERT INTO Livro (Titulo, Genero, Ano_Publicacao, ISBN) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $titulo, $genero, $ano_publicacao, $isbn);

    if ($stmt->execute()) {
        header('Location: listar.php?sucesso=1');
        exit;
    } else {
        $erro = 'Erro ao adicionar livro: ' . $stmt->error;
    }
    $stmt->close();
}

$titulo_pagina = 'Adicionar Livro';
$pagina_ativa  = 'livros';
$caminho_base  = '../';
include '../includes/header.php';
?>

<div class="cabecalho-pagina">
    <div>
        <h2>Adicionar Livro</h2>
        <p>Preencha os dados do novo livro</p>
    </div>
</div>

<?php if ($erro): ?>
    <div class="alerta alerta-erro"><?php echo htmlspecialchars($erro); ?></div>
<?php endif; ?>

<div class="cartao">
    <form method="POST" action="">
        <div class="form-grelha">
            <div class="campo">
                <label for="titulo">Título *</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>
            <div class="campo">
                <label for="genero">Género</label>
                <input type="text" id="genero" name="genero">
            </div>
            <div class="campo">
                <label for="ano_publicacao">Ano de Publicação</label>
                <input type="number" id="ano_publicacao" name="ano_publicacao" min="0" max="2100">
            </div>
            <div class="campo">
                <label for="isbn">ISBN *</label>
                <input type="text" id="isbn" name="isbn" maxlength="13" required>
            </div>
        </div>
        <div class="acoes-form">
            <button type="submit" class="btn btn-primario">Guardar</button>
            <a href="listar.php" class="btn btn-secundario">Cancelar</a>
        </div>
    </form>
</div>

<?php
$conn->close();
include '../includes/footer.php';
?>
