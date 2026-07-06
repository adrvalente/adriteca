<?php
require '../config.php';

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

$stmt = $conn->prepare("SELECT * FROM Emprestimo WHERE Emprestimo_ID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$emprestimo = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$emprestimo) {
    header('Location: listar.php');
    exit;
}

$livros   = $conn->query("SELECT Livro_ID, Titulo FROM Livro ORDER BY Titulo");
$leitores = $conn->query("SELECT Leitor_ID, Primeiro_nome, Ultimo_nome FROM Leitor ORDER BY Ultimo_nome");

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

<?php if ($erro): ?>
    <div class="alerta alerta-erro"><?php echo htmlspecialchars($erro); ?></div>
<?php endif; ?>

<div class="cartao">
    <form method="POST" action="">
        <input type="hidden" name="emprestimo_id" value="<?php echo $emprestimo['Emprestimo_ID']; ?>">
        <div class="form-grelha">
            <div class="campo">
                <label for="livro_id">Livro *</label>
                <select id="livro_id" name="livro_id" required>
                    <?php while ($l = $livros->fetch_assoc()): ?>
                        <option value="<?php echo $l['Livro_ID']; ?>" <?php echo $l['Livro_ID'] == $emprestimo['Livro_ID'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($l['Titulo']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="campo">
                <label for="leitor_id">Leitor *</label>
                <select id="leitor_id" name="leitor_id" required>
                    <?php while ($r = $leitores->fetch_assoc()): ?>
                        <option value="<?php echo $r['Leitor_ID']; ?>" <?php echo $r['Leitor_ID'] == $emprestimo['Leitor_ID'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($r['Primeiro_nome'] . ' ' . $r['Ultimo_nome']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="campo">
                <label for="data_emp">Data do Empréstimo *</label>
                <input type="date" id="data_emp" name="data_emp" value="<?php echo $emprestimo['Data_Emp']; ?>" required>
            </div>
            <div class="campo">
                <label for="data_vencimento">Data de Vencimento *</label>
                <input type="date" id="data_vencimento" name="data_vencimento" value="<?php echo $emprestimo['Data_Vencimento']; ?>" required>
            </div>
            <div class="campo">
                <label for="data_entrega">Data de Entrega (devolução)</label>
                <input type="date" id="data_entrega" name="data_entrega" value="<?php echo $emprestimo['Date_Entrega'] ?? ''; ?>">
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
