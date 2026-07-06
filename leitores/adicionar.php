<?php
require '../config.php';

<<<<<<< Updated upstream
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
=======
$errors = [];
$primeiro_nome = '';
$ultimo_nome = '';
$data_aniversario = '';
$morada = '';
$telemovel = '';
$email = '';

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
        $stmt = $conn->prepare("SELECT Leitor_ID FROM Leitor WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            $errors[] = 'Já existe um leitor registado com este email.';
        }
        $stmt->close();
    }

    if (empty($errors)) {
        $email_db = $email !== '' ? $email : null;

        $stmt = $conn->prepare(
            "INSERT INTO Leitor (Primeiro_nome, Ultimo_nome, Data_Aniversario, Morada, Telemovel, Email)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("ssssss", $primeiro_nome, $ultimo_nome, $data_aniversario, $morada, $telemovel, $email_db);

        if ($stmt->execute()) {
            header('Location: listar.php?sucesso=1');
            exit;
        }

        $errors[] = 'Erro ao adicionar leitor: ' . $stmt->error;
        $stmt->close();
    }
>>>>>>> Stashed changes
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
        <div class="form-grelha">
            <div class="campo">
                <label for="primeiro_nome">Primeiro Nome *</label>
<<<<<<< Updated upstream
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
            <button type="submit" class="btn btn-primario">Guardar</button>
            <a href="listar.php" class="btn btn-secundario">Cancelar</a>
        </div>
    </form>
</div>

<?php
$conn->close();
include '../includes/footer.php';
?>
