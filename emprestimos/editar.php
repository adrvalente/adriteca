<?php
require '../config.php';

<<<<<<< Updated upstream
$erro = '';
$id   = isset($_GET['id']) ? (int)$_GET['id'] : (int)($_POST['emprestimo_id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $livro_id        = (int)$_POST['livro_id'];
    $leitor_id       = (int)$_POST['leitor_id'];
    $data_emp        = $_POST['data_emp'];
    $data_vencimento = $_POST['data_vencimento'];
    $data_entrega    = $_POST['data_entrega'] !== '' ? $_POST['data_entrega'] : null;

    $stmt = $conn->prepare(
        "UPDATE Emprestimo SET Livro_ID = ?, Leitor_ID = ?, Data_Emp = ?, Data_Vencimento = ?, Date_Entrega = ?
         WHERE Emprestimo_ID = ?"
    );
    $stmt->bind_param("iisssi", $livro_id, $leitor_id, $data_emp, $data_vencimento, $data_entrega, $id);

    if ($stmt->execute()) {
        header('Location: listar.php?sucesso=1');
        exit;
    } else {
        $erro = 'Erro ao atualizar empréstimo: ' . $stmt->error;
    }
    $stmt->close();
}
=======
$errors = [];
$id = isset($_GET['id']) ? (int)$_GET['id'] : (int)($_POST['emprestimo_id'] ?? 0);
>>>>>>> Stashed changes

$stmt = $conn->prepare("SELECT * FROM Emprestimo WHERE Emprestimo_ID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$emprestimo = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$emprestimo) {
    header('Location: listar.php');
    exit;
}

<<<<<<< Updated upstream
$livros   = $conn->query("SELECT Livro_ID, Titulo FROM Livro ORDER BY Titulo");
$leitores = $conn->query("SELECT Leitor_ID, Primeiro_nome, Ultimo_nome FROM Leitor ORDER BY Ultimo_nome");
=======
$livro_id = (int)$emprestimo['Livro_ID'];
$leitor_id = (int)$emprestimo['Leitor_ID'];
$data_emp = $emprestimo['Data_Emp'];
$data_vencimento = $emprestimo['Data_Vencimento'];
$data_entrega = $emprestimo['Date_Entrega'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $livro_id        = (int)($_POST['livro_id'] ?? 0);
    $leitor_id       = (int)($_POST['leitor_id'] ?? 0);
    $data_emp        = trim($_POST['data_emp'] ?? '');
    $data_vencimento = trim($_POST['data_vencimento'] ?? '');
    $data_entrega    = trim($_POST['data_entrega'] ?? '');

    if ($livro_id <= 0) {
        $errors[] = 'Selecione um livro.';
    }

    if ($leitor_id <= 0) {
        $errors[] = 'Selecione um leitor.';
    }

    $dataEmpObj = DateTime::createFromFormat('Y-m-d', $data_emp);
    $dataVencObj = DateTime::createFromFormat('Y-m-d', $data_vencimento);
    $dataEntObj = null;

    if (!$dataEmpObj || $dataEmpObj->format('Y-m-d') !== $data_emp) {
        $errors[] = 'A data do empréstimo não é válida.';
    }

    if (!$dataVencObj || $dataVencObj->format('Y-m-d') !== $data_vencimento) {
        $errors[] = 'A data de vencimento não é válida.';
    }

    if ($dataEmpObj && $dataVencObj && $dataVencObj < $dataEmpObj) {
        $errors[] = 'A data de vencimento tem de ser igual ou posterior à data do empréstimo.';
    }

    if ($data_entrega !== '') {
        $dataEntObj = DateTime::createFromFormat('Y-m-d', $data_entrega);
        if (!$dataEntObj || $dataEntObj->format('Y-m-d') !== $data_entrega) {
            $errors[] = 'A data de entrega não é válida.';
        } elseif ($dataEmpObj && $dataEntObj < $dataEmpObj) {
            $errors[] = 'A data de entrega não pode ser anterior à data do empréstimo.';
        }
    } else {
        $data_entrega = null;
    }

    if ($livro_id > 0) {
        $stmt = $conn->prepare("SELECT Livro_ID FROM Livro WHERE Livro_ID = ?");
        $stmt->bind_param("i", $livro_id);
        $stmt->execute();
        if (!$stmt->get_result()->fetch_assoc()) {
            $errors[] = 'O livro selecionado não existe.';
        }
        $stmt->close();

        $stmt = $conn->prepare("SELECT Emprestimo_ID FROM Emprestimo WHERE Livro_ID = ? AND Date_Entrega IS NULL AND Emprestimo_ID <> ?");
        $stmt->bind_param("ii", $livro_id, $id);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            $errors[] = 'Este livro já se encontra emprestado noutro empréstimo ativo.';
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
            "UPDATE Emprestimo SET Livro_ID = ?, Leitor_ID = ?, Data_Emp = ?, Data_Vencimento = ?, Date_Entrega = ?
             WHERE Emprestimo_ID = ?"
        );
        $stmt->bind_param("iisssi", $livro_id, $leitor_id, $data_emp, $data_vencimento, $data_entrega, $id);

        if ($stmt->execute()) {
            header('Location: listar.php?sucesso=1');
            exit;
        }

        $errors[] = 'Erro ao atualizar empréstimo: ' . $stmt->error;
        $stmt->close();
    }
}

$livros   = $conn->query("SELECT Livro_ID, Titulo FROM Livro ORDER BY Titulo");
$leitores = $conn->query("SELECT Leitor_ID, Primeiro_nome, Ultimo_nome FROM Leitor ORDER BY Ultimo_nome, Primeiro_nome");
>>>>>>> Stashed changes

$titulo_pagina = 'Editar Empréstimo';
$pagina_ativa  = 'emprestimos';
$caminho_base  = '../';
include '../includes/header.php';
?>

<div class="cabecalho-pagina">
    <div>
        <h2>Editar Empréstimo</h2>
        <p>Atualizar dados ou registar devolução</p>
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
        <input type="hidden" name="emprestimo_id" value="<?php echo $emprestimo['Emprestimo_ID']; ?>">
        <div class="form-grelha">
            <div class="campo">
                <label for="livro_id">Livro *</label>
                <select id="livro_id" name="livro_id" required>
                    <?php while ($l = $livros->fetch_assoc()): ?>
<<<<<<< Updated upstream
                        <option value="<?php echo $l['Livro_ID']; ?>" <?php echo $l['Livro_ID'] == $emprestimo['Livro_ID'] ? 'selected' : ''; ?>>
=======
                        <option value="<?php echo $l['Livro_ID']; ?>" <?php echo (int)$l['Livro_ID'] === (int)$livro_id ? 'selected' : ''; ?>>
>>>>>>> Stashed changes
                            <?php echo htmlspecialchars($l['Titulo']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="campo">
                <label for="leitor_id">Leitor *</label>
                <select id="leitor_id" name="leitor_id" required>
                    <?php while ($r = $leitores->fetch_assoc()): ?>
<<<<<<< Updated upstream
                        <option value="<?php echo $r['Leitor_ID']; ?>" <?php echo $r['Leitor_ID'] == $emprestimo['Leitor_ID'] ? 'selected' : ''; ?>>
=======
                        <option value="<?php echo $r['Leitor_ID']; ?>" <?php echo (int)$r['Leitor_ID'] === (int)$leitor_id ? 'selected' : ''; ?>>
>>>>>>> Stashed changes
                            <?php echo htmlspecialchars($r['Primeiro_nome'] . ' ' . $r['Ultimo_nome']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="campo">
                <label for="data_emp">Data do Empréstimo *</label>
<<<<<<< Updated upstream
                <input type="date" id="data_emp" name="data_emp" value="<?php echo $emprestimo['Data_Emp']; ?>" required>
            </div>
            <div class="campo">
                <label for="data_vencimento">Data de Vencimento *</label>
                <input type="date" id="data_vencimento" name="data_vencimento" value="<?php echo $emprestimo['Data_Vencimento']; ?>" required>
            </div>
            <div class="campo">
                <label for="data_entrega">Data de Entrega (devolução)</label>
                <input type="date" id="data_entrega" name="data_entrega" value="<?php echo $emprestimo['Date_Entrega'] ?? ''; ?>">
=======
                <input type="date" id="data_emp" name="data_emp" value="<?php echo htmlspecialchars($data_emp); ?>" required>
            </div>
            <div class="campo">
                <label for="data_vencimento">Data de Vencimento *</label>
                <input type="date" id="data_vencimento" name="data_vencimento" value="<?php echo htmlspecialchars($data_vencimento); ?>" required>
            </div>
            <div class="campo">
                <label for="data_entrega">Data de Entrega (devolução)</label>
                <input type="date" id="data_entrega" name="data_entrega" value="<?php echo htmlspecialchars($data_entrega ?? ''); ?>">
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
