<?php
require '../config.php';

$erro = '';
$id   = isset($_GET['id']) ? (int)$_GET['id'] : (int)($_POST['livro_id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo         = trim($_POST['titulo']);
    $genero         = trim($_POST['genero']);
    $ano_publicacao = $_POST['ano_publicacao'] !== '' ? (int)$_POST['ano_publicacao'] : null;
    $isbn           = trim($_POST['isbn']);

    $stmt = $conn->prepare("UPDATE Livro SET Titulo = ?, Genero = ?, Ano_Publicacao = ?, ISBN = ? WHERE Livro_ID = ?");
    $stmt->bind_param("ssisi", $titulo, $genero, $ano_publicacao, $isbn, $id);

    if ($stmt->execute()) {
        header('Location: listar.php?sucesso=1');
        exit;
    } else {
        $erro = 'Erro ao atualizar livro: ' . $stmt->error;
    }
    $stmt->close();
}

// Vai buscar os dados atuais do livro para preencher o formulário
$stmt = $conn->prepare("SELECT * FROM Livro WHERE Livro_ID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$livro = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$livro) {
    header('Location: listar.php');
    exit;
}

$titulo_pagina = 'Editar Livro';
$pagina_ativa  = 'livros';
$caminho_base  = '../';
include '../includes/header.php';
?>

<div class="cabecalho-pagina">
    <div>
        <h2>Editar Livro</h2>
        <p>A atualizar: <?php echo htmlspecialchars($livro['Titulo']); ?></p>
    </div>
</div>

<?php if ($erro): ?>
    <div class="alerta alerta-erro"><?php echo htmlspecialchars($erro); ?></div>
<?php endif; ?>

<div class="cartao">
    <form method="POST" action="">
        <input type="hidden" name="livro_id" value="<?php echo $livro['Livro_ID']; ?>">
        <div class="form-grelha">
            <div class="campo">
                <label for="titulo">Título *</label>
                <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($livro['Titulo']); ?>" required>
            </div>
            <div class="campo">
                <label for="genero">Género</label>
                <input type="text" id="genero" name="genero" value="<?php echo htmlspecialchars($livro['Genero'] ?? ''); ?>">
            </div>
            <div class="campo">
                <label for="ano_publicacao">Ano de Publicação</label>
                <input type="number" id="ano_publicacao" name="ano_publicacao" min="0" max="2100" value="<?php echo htmlspecialchars($livro['Ano_Publicacao'] ?? ''); ?>">
            </div>
            <div class="campo">
                <label for="isbn">ISBN *</label>
                <input type="text" id="isbn" name="isbn" maxlength="13" value="<?php echo htmlspecialchars($livro['ISBN']); ?>" required>
            </div>
        </div>
        <div class="acoes-form">
            <button type="submit" class="btn btn-primario">Guardar Alterações</button>
            <a href="listar.php" class="btn btn-secundario">Cancelar</a>
        </div>
    </form>
</div>

<?php
$conn->close();
include '../includes/footer.php';
?>
