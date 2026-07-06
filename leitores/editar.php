<?php
require '../config.php';

<<<<<<< Updated upstream
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
=======
$errors = [];
$id = isset($_GET['id']) ? (int)$_GET['id'] : (int)($_POST['leitor_id'] ?? 0);
>>>>>>> Stashed changes

$stmt = $conn->prepare("SELECT * FROM Leitor WHERE Leitor_ID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$leitor = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$leitor) {
    header('Location: listar.php');
    exit;
}

<<<<<<< Updated upstream
=======
$primeiro_nome = $leitor['Primeiro_nome'];
$ultimo_nome = $leitor['Ultimo_nome'];
$data_aniversario = $leitor['Data_Aniversario'] ?? '';
$morada = $leitor['Morada'] ?? '';
$telemovel = $leitor['Telemovel'] ?? '';
$email = $leitor['Email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $primeiro_nome    = trim($_POST['primeiro_nome'] ?? '');
    $ultimo_nome      = trim($_POST['ultimo_nome'] ?? '');
    $data_aniversario = trim($_POST['data_aniversario'] ?? '');
    $morada           = trim($_POST['morada'] ?? '');
    $telemovel        = trim($_POST['telemovel'] ?? '');
    $email            = trim($_POST['email'] ?? '');

    if ($primeiro_nome === '') {
        $errors[] = 'O primeiro nome é obrigatório.';
    }

    if ($ultimo_nome === '') {
        $errors[] = 'O último nome é obrigatório.';
    }

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'O email não é válido.';
    }

    if ($telemovel !== '' && !preg_match('/^[0-9+\s-]{7,15}$/', $telemovel)) {
        $errors[] = 'O telemóvel deve ter apenas números, espaços, + ou - e entre 7 e 15 caracteres.';
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

    if ($email !== '') {
        $stmt = $conn->prepare("SELECT Leitor_ID FROM Leitor WHERE Email = ? AND Leitor_ID <> ?");
        $stmt->bind_param("si", $email, $id);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            $errors[] = 'Já existe outro leitor registado com este email.';
        }
        $stmt->close();
    }

    if (empty($errors)) {
        $email_db = $email !== '' ? $email : null;

        $stmt = $conn->prepare(
            "UPDATE Leitor SET Primeiro_nome = ?, Ultimo_nome = ?, Data_Aniversario = ?, Morada = ?, Telemovel = ?, Email = ?
             WHERE Leitor_ID = ?"
        );
        $stmt->bind_param("ssssssi", $primeiro_nome, $ultimo_nome, $data_aniversario, $morada, $telemovel, $email_db, $id);

        if ($stmt->execute()) {
            header('Location: listar.php?sucesso=1');
            exit;
        }

        $errors[] = 'Erro ao atualizar leitor: ' . $stmt->error;
        $stmt->close();
    }
}

>>>>>>> Stashed changes
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
        <input type="hidden" name="leitor_id" value="<?php echo $leitor['Leitor_ID']; ?>">
        <div class="form-grelha">
            <div class="campo">
                <label for="primeiro_nome">Primeiro Nome *</label>
<<<<<<< Updated upstream
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
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            </div>
            <div class="campo">
                <label for="telemovel">Telemóvel</label>
                <input type="text" id="telemovel" name="telemovel" maxlength="15" value="<?php echo htmlspecialchars($telemovel); ?>">
            </div>
            <div class="campo">
                <label for="morada">Morada</label>
                <input type="text" id="morada" name="morada" value="<?php echo htmlspecialchars($morada); ?>">
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
