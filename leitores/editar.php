<?php
require '../config.php';

$erro = '';
$id   = isset($_GET['id']) ? (int)$_GET['id'] : (int)($_POST['leitor_id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $primeiro_nome    = trim($_POST['primeiro_nome']);
    $ultimo_nome      = trim($_POST['ultimo_nome']);
    $data_aniversario = $_POST['data_aniversario'] !== '' ? $_POST['data_aniversario'] : null;
    $morada           = trim($_POST['morada']);
    $telemovel        = trim($_POST['telemovel']);
    $email            = trim($_POST['email']);

    $stmt = $conn->prepare(
        "UPDATE Leitor SET Primeiro_nome = ?, Ultimo_nome = ?, Data_Aniversario = ?, Morada = ?, Telemovel = ?, Email = ?
         WHERE Leitor_ID = ?"
    );
    $stmt->bind_param("ssssssi", $primeiro_nome, $ultimo_nome, $data_aniversario, $morada, $telemovel, $email, $id);

    if ($stmt->execute()) {
        header('Location: listar.php?sucesso=1');
        exit;
    } else {
        $erro = ($conn->errno === 1062)
            ? 'Já existe um leitor registado com este email.'
            : 'Erro ao atualizar leitor: ' . $stmt->error;
    }
    $stmt->close();
}

$stmt = $conn->prepare("SELECT * FROM Leitor WHERE Leitor_ID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$leitor = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$leitor) {
    header('Location: listar.php');
    exit;
}

$titulo_pagina = 'Editar Leitor';
$pagina_ativa  = 'leitores';
$caminho_base  = '../';
include '../includes/header.php';
?>

<div class="cabecalho-pagina">
    <div>
        <h2>Editar Leitor</h2>
        <p>A atualizar: <?php echo htmlspecialchars($leitor['Primeiro_nome'] . ' ' . $leitor['Ultimo_nome']); ?></p>
    </div>
</div>

<?php if ($erro): ?>
    <div class="alerta alerta-erro"><?php echo htmlspecialchars($erro); ?></div>
<?php endif; ?>

<div class="cartao">
    <form method="POST" action="">
        <input type="hidden" name="leitor_id" value="<?php echo $leitor['Leitor_ID']; ?>">
        <div class="form-grelha">
            <div class="campo">
                <label for="primeiro_nome">Primeiro Nome *</label>
                <input type="text" id="primeiro_nome" name="primeiro_nome" value="<?php echo htmlspecialchars($leitor['Primeiro_nome']); ?>" required>
            </div>
            <div class="campo">
                <label for="ultimo_nome">Último Nome *</label>
                <input type="text" id="ultimo_nome" name="ultimo_nome" value="<?php echo htmlspecialchars($leitor['Ultimo_nome']); ?>" required>
            </div>
            <div class="campo">
                <label for="data_aniversario">Data de Nascimento</label>
                <input type="date" id="data_aniversario" name="data_aniversario" value="<?php echo htmlspecialchars($leitor['Data_Aniversario'] ?? ''); ?>">
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($leitor['Email'] ?? ''); ?>">
            </div>
            <div class="campo">
                <label for="telemovel">Telemóvel</label>
                <input type="text" id="telemovel" name="telemovel" maxlength="15" value="<?php echo htmlspecialchars($leitor['Telemovel'] ?? ''); ?>">
            </div>
            <div class="campo">
                <label for="morada">Morada</label>
                <input type="text" id="morada" name="morada" value="<?php echo htmlspecialchars($leitor['Morada'] ?? ''); ?>">
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
