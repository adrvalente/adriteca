<?php
require '../config.php';

<<<<<<< Updated upstream
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $primeiro_nome    = trim($_POST['primeiro_nome']);
    $ultimo_nome      = trim($_POST['ultimo_nome']);
    $data_aniversario = $_POST['data_aniversario'] !== '' ? $_POST['data_aniversario'] : null;

    $stmt = $conn->prepare("INSERT INTO Autor (Primeiro_Nome, Ultimo_Nome, Data_Aniversario) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $primeiro_nome, $ultimo_nome, $data_aniversario);

    if ($stmt->execute()) {
        header('Location: listar.php?sucesso=1');
        exit;
    } else {
        $erro = 'Erro ao adicionar autor: ' . $stmt->error;
    }
    $stmt->close();
=======
$errors = [];
$primeiro_nome = '';
$ultimo_nome = '';
$data_aniversario = '';

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
        $stmt = $conn->prepare("INSERT INTO Autor (Primeiro_Nome, Ultimo_Nome, Data_Aniversario) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $primeiro_nome, $ultimo_nome, $data_aniversario);

        if ($stmt->execute()) {
            header('Location: listar.php?sucesso=1');
            exit;
        }

        $errors[] = 'Erro ao adicionar autor: ' . $stmt->error;
        $stmt->close();
    }
>>>>>>> Stashed changes
}

$titulo_pagina = 'Adicionar Autor';
$pagina_ativa  = 'autores';
$caminho_base  = '../';
include '../includes/header.php';
?>

<div class="cabecalho-pagina">
    <div>
        <h2>Adicionar Autor</h2>
        <p>Preencha os dados do novo autor</p>
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
            <button type="submit" class="btn btn-primario">Guardar</button>
            <a href="listar.php" class="btn btn-secundario">Cancelar</a>
        </div>
    </form>
</div>

<?php
$conn->close();
include '../includes/footer.php';
?>
