<?php
require '../config.php';

<<<<<<< Updated upstream
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $livro_id        = (int)$_POST['livro_id'];
    $leitor_id       = (int)$_POST['leitor_id'];
    $data_emp        = $_POST['data_emp'];
    $data_vencimento = $_POST['data_vencimento'];

    $stmt = $conn->prepare(
        "INSERT INTO Emprestimo (Livro_ID, Leitor_ID, Data_Emp, Data_Vencimento) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param("iiss", $livro_id, $leitor_id, $data_emp, $data_vencimento);

    if ($stmt->execute()) {
        header('Location: listar.php?sucesso=1');
        exit;
    } else {
        $erro = 'Erro ao registar empréstimo: ' . $stmt->error;
    }
    $stmt->close();
}


$livros   = $conn->query("SELECT Livro_ID, Titulo FROM Livro ORDER BY Titulo");
$leitores = $conn->query("SELECT Leitor_ID, Primeiro_nome, Ultimo_nome FROM Leitor ORDER BY Ultimo_nome");
=======
$errors = [];
$livro_id = '';
$leitor_id = '';
$data_emp = date('Y-m-d');
$data_vencimento = date('Y-m-d', strtotime('+15 days'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $livro_id        = (int)($_POST['livro_id'] ?? 0);
    $leitor_id       = (int)($_POST['leitor_id'] ?? 0);
    $data_emp        = trim($_POST['data_emp'] ?? '');
    $data_vencimento = trim($_POST['data_vencimento'] ?? '');

    if ($livro_id <= 0) {
        $errors[] = 'Selecione um livro.';
    }

    if ($leitor_id <= 0) {
        $errors[] = 'Selecione um leitor.';
    }

    $dataEmpObj = DateTime::createFromFormat('Y-m-d', $data_emp);
    $dataVencObj = DateTime::createFromFormat('Y-m-d', $data_vencimento);

    if (!$dataEmpObj || $dataEmpObj->format('Y-m-d') !== $data_emp) {
        $errors[] = 'A data do empréstimo não é válida.';
    }

    if (!$dataVencObj || $dataVencObj->format('Y-m-d') !== $data_vencimento) {
        $errors[] = 'A data de vencimento não é válida.';
    }

    if ($dataEmpObj && $dataVencObj && $dataVencObj < $dataEmpObj) {
        $errors[] = 'A data de vencimento tem de ser igual ou posterior à data do empréstimo.';
    }

    if ($livro_id > 0) {
        $stmt = $conn->prepare("SELECT Livro_ID FROM Livro WHERE Livro_ID = ?");
        $stmt->bind_param("i", $livro_id);
        $stmt->execute();
        if (!$stmt->get_result()->fetch_assoc()) {
            $errors[] = 'O livro selecionado não existe.';
        }
        $stmt->close();

        $stmt = $conn->prepare("SELECT Emprestimo_ID FROM Emprestimo WHERE Livro_ID = ? AND Date_Entrega IS NULL");
        $stmt->bind_param("i", $livro_id);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            $errors[] = 'Este livro já se encontra emprestado e ainda não foi devolvido.';
        }
        $stmt->close();
    }

    if ($leitor_id > 0) {
        $stmt = $conn->prepare("SELECT Leitor_ID FROM Leitor WHERE Leitor_ID = ?");
        $stmt->bind_param("i", $leitor_id);
        $stmt->execute();
        if (!$stmt->get_result()->fetch_assoc()) {
            $errors[] = 'O leitor selecionado não existe.';
        }
        $stmt->close();
    }

    if (empty($errors)) {
        $stmt = $conn->prepare(
            "INSERT INTO Emprestimo (Livro_ID, Leitor_ID, Data_Emp, Data_Vencimento) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("iiss", $livro_id, $leitor_id, $data_emp, $data_vencimento);

        if ($stmt->execute()) {
            header('Location: listar.php?sucesso=1');
            exit;
        }

        $errors[] = 'Erro ao registar empréstimo: ' . $stmt->error;
        $stmt->close();
    }
}

$livros   = $conn->query("SELECT Livro_ID, Titulo FROM Livro ORDER BY Titulo");
$leitores = $conn->query("SELECT Leitor_ID, Primeiro_nome, Ultimo_nome FROM Leitor ORDER BY Ultimo_nome, Primeiro_nome");
>>>>>>> Stashed changes

$titulo_pagina = 'Novo Empréstimo';
$pagina_ativa  = 'emprestimos';
$caminho_base  = '../';
include '../includes/header.php';
?>

<div class="cabecalho-pagina">
    <div>
        <h2>Novo Empréstimo</h2>
        <p>Registar o empréstimo de um livro a um leitor</p>
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
                <label for="livro_id">Livro *</label>
                <select id="livro_id" name="livro_id" required>
                    <option value="">-- Selecione um livro --</option>
                    <?php while ($l = $livros->fetch_assoc()): ?>
<<<<<<< Updated upstream
                        <option value="<?php echo $l['Livro_ID']; ?>"><?php echo htmlspecialchars($l['Titulo']); ?></option>
=======
                        <option value="<?php echo $l['Livro_ID']; ?>" <?php echo (int)$livro_id === (int)$l['Livro_ID'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($l['Titulo']); ?></option>
>>>>>>> Stashed changes
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="campo">
                <label for="leitor_id">Leitor *</label>
                <select id="leitor_id" name="leitor_id" required>
                    <option value="">-- Selecione um leitor --</option>
                    <?php while ($r = $leitores->fetch_assoc()): ?>
<<<<<<< Updated upstream
                        <option value="<?php echo $r['Leitor_ID']; ?>"><?php echo htmlspecialchars($r['Primeiro_nome'] . ' ' . $r['Ultimo_nome']); ?></option>
=======
                        <option value="<?php echo $r['Leitor_ID']; ?>" <?php echo (int)$leitor_id === (int)$r['Leitor_ID'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($r['Primeiro_nome'] . ' ' . $r['Ultimo_nome']); ?></option>
>>>>>>> Stashed changes
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="campo">
                <label for="data_emp">Data do Empréstimo *</label>
<<<<<<< Updated upstream
                <input type="date" id="data_emp" name="data_emp" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="campo">
                <label for="data_vencimento">Data de Vencimento *</label>
                <input type="date" id="data_vencimento" name="data_vencimento" value="<?php echo date('Y-m-d', strtotime('+15 days')); ?>" required>
=======
                <input type="date" id="data_emp" name="data_emp" value="<?php echo htmlspecialchars($data_emp); ?>" required>
            </div>
            <div class="campo">
                <label for="data_vencimento">Data de Vencimento *</label>
                <input type="date" id="data_vencimento" name="data_vencimento" value="<?php echo htmlspecialchars($data_vencimento); ?>" required>
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
