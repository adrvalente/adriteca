<?php
require '../config.php';

<<<<<<< Updated upstream
$erro = '';
$id   = isset($_GET['id']) ? (int)$_GET['id'] : (int)($_POST['autor_id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $primeiro_nome    = trim($_POST['primeiro_nome']);
    $ultimo_nome      = trim($_POST['ultimo_nome']);
    $data_aniversario = $_POST['data_aniversario'] !== '' ? $_POST['data_aniversario'] : null;

    $stmt = $conn->prepare("UPDATE Autor SET Primeiro_Nome = ?, Ultimo_Nome = ?, Data_Aniversario = ? WHERE Autor_ID = ?");
    $stmt->bind_param("sssi", $primeiro_nome, $ultimo_nome, $data_aniversario, $id);

    if ($stmt->execute()) {
        header('Location: listar.php?sucesso=1');
        exit;
    } else {
        $erro = 'Erro ao atualizar autor: ' . $stmt->error;
    }
    $stmt->close();
}
=======
$errors = [];
$id = isset($_GET['id']) ? (int)$_GET['id'] : (int)($_POST['autor_id'] ?? 0);
>>>>>>> Stashed changes

$stmt = $conn->prepare("SELECT * FROM Autor WHERE Autor_ID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$autor = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$autor) {
    header('Location: listar.php');
    exit;
}

<<<<<<< Updated upstream
=======
$primeiro_nome = $autor['Primeiro_Nome'];
$ultimo_nome = $autor['Ultimo_Nome'];
$data_aniversario = $autor['Data_Aniversario'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $primeiro_nome    = trim($_POST['primeiro_nome'] ?? '');
    $ultimo_nome      = trim($_POST['ultimo_nome'] ?? '');
    $data_aniversario = trim($_POST['data_aniversario'] ?? '');

    if ($primeiro_nome === '') {
        $errors[] = 'O primeiro nome é obrigatório.';
    }

    if ($ultimo_nome === '') {
        $errors[] = 'O último nome é obrigatório.';
    }

    if ($data_aniversario !== '') {
        $data = DateTime::createFromFormat('Y-m-d', $data_aniversario);
        if (!$data || $data->format('Y-m-d') !== $data_aniversario) {
            $errors[] = 'A data de nascimento não é válida.';
        } elseif ($data > new DateTime()) {
            $errors[] = 'A data de nascimento não pode ser futura.';
        }
    } else {
        $data_aniversario = null;
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE Autor SET Primeiro_Nome = ?, Ultimo_Nome = ?, Data_Aniversario = ? WHERE Autor_ID = ?");
        $stmt->bind_param("sssi", $primeiro_nome, $ultimo_nome, $data_aniversario, $id);

        if ($stmt->execute()) {
            header('Location: listar.php?sucesso=1');
            exit;
        }

        $errors[] = 'Erro ao atualizar autor: ' . $stmt->error;
        $stmt->close();
    }
}

>>>>>>> Stashed changes
$titulo_pagina = 'Editar Autor';
$pagina_ativa  = 'autores';
$caminho_base  = '../';
include '../includes/header.php';
?>

<div class="cabecalho-pagina">
    <div>
        <h2>Editar Autor</h2>
        <p>A atualizar: <?php echo htmlspecialchars($autor['Primeiro_Nome'] . ' ' . $autor['Ultimo_Nome']); ?></p>
    </div>
</div>

<<<<<<< Updated upstream
<?php if ($erro): ?>
    <div class="alerta alerta-erro"><?php echo htmlspecialchars($erro); ?></div>
=======
<?php if (!empty($errors)): ?>
    <div class="alerta alerta-erro">
        <?php foreach ($errors as $error): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
    </div>
>>>>>>> Stashed changes
<?php endif; ?>

<div class="cartao">
    <form method="POST" action="">
        <input type="hidden" name="autor_id" value="<?php echo $autor['Autor_ID']; ?>">
        <div class="form-grelha">
            <div class="campo">
                <label for="primeiro_nome">Primeiro Nome *</label>
<<<<<<< Updated upstream
                <input type="text" id="primeiro_nome" name="primeiro_nome" value="<?php echo htmlspecialchars($autor['Primeiro_Nome']); ?>" required>
            </div>
            <div class="campo">
                <label for="ultimo_nome">Último Nome *</label>
                <input type="text" id="ultimo_nome" name="ultimo_nome" value="<?php echo htmlspecialchars($autor['Ultimo_Nome']); ?>" required>
            </div>
            <div class="campo">
                <label for="data_aniversario">Data de Nascimento</label>
                <input type="date" id="data_aniversario" name="data_aniversario" value="<?php echo htmlspecialchars($autor['Data_Aniversario'] ?? ''); ?>">
=======
                <input type="text" id="primeiro_nome" name="primeiro_nome" value="<?php echo htmlspecialchars($primeiro_nome); ?>" required>
            </div>
            <div class="campo">
                <label for="ultimo_nome">Último Nome *</label>
                <input type="text" id="ultimo_nome" name="ultimo_nome" value="<?php echo htmlspecialchars($ultimo_nome); ?>" required>
            </div>
            <div class="campo">
                <label for="data_aniversario">Data de Nascimento</label>
                <input type="date" id="data_aniversario" name="data_aniversario" value="<?php echo htmlspecialchars($data_aniversario ?? ''); ?>">
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
