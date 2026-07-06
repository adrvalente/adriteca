<?php
require '../config.php';

<<<<<<< Updated upstream
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
=======
$erros = [];
$id = isset($_GET['id']) ? (int)$_GET['id'] : (int)($_POST['livro_id'] ?? 0);

>>>>>>> Stashed changes
$stmt = $conn->prepare("SELECT * FROM Livro WHERE Livro_ID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$livro = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$livro) {
    header('Location: listar.php');
    exit;
}

<<<<<<< Updated upstream
=======
$dados = [
    'titulo' => $livro['Titulo'],
    'genero' => $livro['Genero'] ?? '',
    'ano_publicacao' => $livro['Ano_Publicacao'] ?? '',
    'isbn' => $livro['ISBN']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados['titulo'] = trim($_POST['titulo'] ?? '');
    $dados['genero'] = trim($_POST['genero'] ?? '');
    $dados['ano_publicacao'] = trim($_POST['ano_publicacao'] ?? '');
    $dados['isbn'] = trim($_POST['isbn'] ?? '');

    if ($dados['titulo'] === '') {
        $erros[] = 'O título é obrigatório.';
    }

    if ($dados['isbn'] === '') {
        $erros[] = 'O ISBN é obrigatório.';
    } elseif (!preg_match('/^[0-9\-]{10,17}$/', $dados['isbn'])) {
        $erros[] = 'O ISBN deve ter entre 10 e 17 caracteres e conter apenas números ou hífenes.';
    } else {
        $stmt = $conn->prepare("SELECT Livro_ID FROM Livro WHERE ISBN = ? AND Livro_ID <> ?");
        $stmt->bind_param("si", $dados['isbn'], $id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $erros[] = 'Já existe outro livro registado com este ISBN.';
        }
        $stmt->close();
    }

    $ano_publicacao = null;
    if ($dados['ano_publicacao'] !== '') {
        if (!ctype_digit($dados['ano_publicacao'])) {
            $erros[] = 'O ano de publicação deve ser numérico.';
        } else {
            $ano_publicacao = (int)$dados['ano_publicacao'];
            $ano_atual = (int)date('Y');
            if ($ano_publicacao < 1000 || $ano_publicacao > $ano_atual) {
                $erros[] = "O ano de publicação deve estar entre 1000 e {$ano_atual}.";
            }
        }
    }

    if (empty($erros)) {
        $stmt = $conn->prepare("UPDATE Livro SET Titulo = ?, Genero = ?, Ano_Publicacao = ?, ISBN = ? WHERE Livro_ID = ?");
        $stmt->bind_param("ssisi", $dados['titulo'], $dados['genero'], $ano_publicacao, $dados['isbn'], $id);

        if ($stmt->execute()) {
            header('Location: listar.php?sucesso=1');
            exit;
        }

        $erros[] = 'Erro ao atualizar livro: ' . $stmt->error;
        $stmt->close();
    }
}

>>>>>>> Stashed changes
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

<<<<<<< Updated upstream
<?php if ($erro): ?>
    <div class="alerta alerta-erro"><?php echo htmlspecialchars($erro); ?></div>
=======
<?php if (!empty($erros)): ?>
    <div class="alerta alerta-erro">
        <?php foreach ($erros as $erro): ?>
            <p><?php echo htmlspecialchars($erro); ?></p>
        <?php endforeach; ?>
    </div>
>>>>>>> Stashed changes
<?php endif; ?>

<div class="cartao">
    <form method="POST" action="">
        <input type="hidden" name="livro_id" value="<?php echo $livro['Livro_ID']; ?>">
        <div class="form-grelha">
            <div class="campo">
                <label for="titulo">Título *</label>
<<<<<<< Updated upstream
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
=======
                <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($dados['titulo']); ?>" required>
            </div>
            <div class="campo">
                <label for="genero">Género</label>
                <input type="text" id="genero" name="genero" value="<?php echo htmlspecialchars($dados['genero']); ?>">
            </div>
            <div class="campo">
                <label for="ano_publicacao">Ano de Publicação</label>
                <input type="number" id="ano_publicacao" name="ano_publicacao" min="1000" max="<?php echo date('Y'); ?>" value="<?php echo htmlspecialchars($dados['ano_publicacao']); ?>">
            </div>
            <div class="campo">
                <label for="isbn">ISBN *</label>
                <input type="text" id="isbn" name="isbn" maxlength="17" value="<?php echo htmlspecialchars($dados['isbn']); ?>" required>
>>>>>>> Stashed changes
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
