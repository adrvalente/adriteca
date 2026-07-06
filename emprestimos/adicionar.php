<?php
require '../config.php';

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

<?php if ($erro): ?>
    <div class="alerta alerta-erro"><?php echo htmlspecialchars($erro); ?></div>
<?php endif; ?>

<div class="cartao">
    <form method="POST" action="">
        <div class="form-grelha">
            <div class="campo">
                <label for="livro_id">Livro *</label>
                <select id="livro_id" name="livro_id" required>
                    <option value="">-- Selecione um livro --</option>
                    <?php while ($l = $livros->fetch_assoc()): ?>
                        <option value="<?php echo $l['Livro_ID']; ?>"><?php echo htmlspecialchars($l['Titulo']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="campo">
                <label for="leitor_id">Leitor *</label>
                <select id="leitor_id" name="leitor_id" required>
                    <option value="">-- Selecione um leitor --</option>
                    <?php while ($r = $leitores->fetch_assoc()): ?>
                        <option value="<?php echo $r['Leitor_ID']; ?>"><?php echo htmlspecialchars($r['Primeiro_nome'] . ' ' . $r['Ultimo_nome']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="campo">
                <label for="data_emp">Data do Empréstimo *</label>
                <input type="date" id="data_emp" name="data_emp" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="campo">
                <label for="data_vencimento">Data de Vencimento *</label>
                <input type="date" id="data_vencimento" name="data_vencimento" value="<?php echo date('Y-m-d', strtotime('+15 days')); ?>" required>
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
