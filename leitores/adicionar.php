<?php
require '../config.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $primeiro_nome    = trim($_POST['primeiro_nome']);
    $ultimo_nome      = trim($_POST['ultimo_nome']);
    $data_aniversario = $_POST['data_aniversario'] !== '' ? $_POST['data_aniversario'] : null;
    $morada           = trim($_POST['morada']);
    $telemovel        = trim($_POST['telemovel']);
    $email            = trim($_POST['email']);

    $stmt = $conn->prepare(
        "INSERT INTO Leitor (Primeiro_nome, Ultimo_nome, Data_Aniversario, Morada, Telemovel, Email)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssssss", $primeiro_nome, $ultimo_nome, $data_aniversario, $morada, $telemovel, $email);

    if ($stmt->execute()) {
        header('Location: listar.php?sucesso=1');
        exit;
    } else {
        $erro = ($conn->errno === 1062)
            ? 'Já existe um leitor registado com este email.'
            : 'Erro ao adicionar leitor: ' . $stmt->error;
    }
    $stmt->close();
}

$titulo_pagina = 'Adicionar Leitor';
$pagina_ativa  = 'leitores';
$caminho_base  = '../';
include '../includes/header.php';
?>

<div class="cabecalho-pagina">
    <div>
        <h2>Adicionar Leitor</h2>
        <p>Preencha os dados do novo leitor</p>
    </div>
</div>

<?php if ($erro): ?>
    <div class="alerta alerta-erro"><?php echo htmlspecialchars($erro); ?></div>
<?php endif; ?>

<div class="cartao">
    <form method="POST" action="">
        <div class="form-grelha">
            <div class="campo">
                <label for="primeiro_nome">Primeiro Nome *</label>
                <input type="text" id="primeiro_nome" name="primeiro_nome" required>
            </div>
            <div class="campo">
                <label for="ultimo_nome">Último Nome *</label>
                <input type="text" id="ultimo_nome" name="ultimo_nome" required>
            </div>
            <div class="campo">
                <label for="data_aniversario">Data de Nascimento</label>
                <input type="date" id="data_aniversario" name="data_aniversario">
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="campo">
                <label for="telemovel">Telemóvel</label>
                <input type="text" id="telemovel" name="telemovel" maxlength="15">
            </div>
            <div class="campo">
                <label for="morada">Morada</label>
                <input type="text" id="morada" name="morada">
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
