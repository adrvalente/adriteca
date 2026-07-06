<?php
require '../config.php';

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

$stmt = $conn->prepare("SELECT * FROM Autor WHERE Autor_ID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$autor = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$autor) {
    header('Location: listar.php');
    exit;
}

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

<?php if ($erro): ?>
    <div class="alerta alerta-erro"><?php echo htmlspecialchars($erro); ?></div>
<?php endif; ?>

<div class="cartao">
    <form method="POST" action="">
        <input type="hidden" name="autor_id" value="<?php echo $autor['Autor_ID']; ?>">
        <div class="form-grelha">
            <div class="campo">
                <label for="primeiro_nome">Primeiro Nome *</label>
                <input type="text" id="primeiro_nome" name="primeiro_nome" value="<?php echo htmlspecialchars($autor['Primeiro_Nome']); ?>" required>
            </div>
            <div class="campo">
                <label for="ultimo_nome">Último Nome *</label>
                <input type="text" id="ultimo_nome" name="ultimo_nome" value="<?php echo htmlspecialchars($autor['Ultimo_Nome']); ?>" required>
            </div>
            <div class="campo">
                <label for="data_aniversario">Data de Nascimento</label>
                <input type="date" id="data_aniversario" name="data_aniversario" value="<?php echo htmlspecialchars($autor['Data_Aniversario'] ?? ''); ?>">
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
