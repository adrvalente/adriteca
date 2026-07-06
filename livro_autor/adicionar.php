<?php
require '../config.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $livro_id = (int)$_POST['livro_id'];
    $autor_id = (int)$_POST['autor_id'];

    // Verifica se a associação já existe, para não duplicar
    $stmt = $conn->prepare("SELECT ID_LA FROM Livro_Autor WHERE Livro_ID = ? AND Autor_ID = ?");
    $stmt->bind_param("ii", $livro_id, $autor_id);
    $stmt->execute();
    $existe = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($existe) {
        $erro = 'Esta associação já existe.';
    } else {
        $stmt = $conn->prepare("INSERT INTO Livro_Autor (Livro_ID, Autor_ID) VALUES (?, ?)");
        $stmt->bind_param("ii", $livro_id, $autor_id);

        if ($stmt->execute()) {
            header('Location: listar.php?sucesso=1');
            exit;
        } else {
            $erro = 'Erro ao criar associação: ' . $stmt->error;
        }
        $stmt->close();
    }
}

$livros  = $conn->query("SELECT Livro_ID, Titulo FROM Livro ORDER BY Titulo");
$autores = $conn->query("SELECT Autor_ID, Primeiro_Nome, Ultimo_Nome FROM Autor ORDER BY Ultimo_Nome");

$titulo_pagina = 'Nova Associação';
$pagina_ativa  = 'livro_autor';
$caminho_base  = '../';
include '../includes/header.php';
?>

<div class="cabecalho-pagina">
    <div>
        <h2>Nova Associação Livro-Autor</h2>
        <p>Associe um autor a um livro</p>
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
                <label for="autor_id">Autor *</label>
                <select id="autor_id" name="autor_id" required>
                    <option value="">-- Selecione um autor --</option>
                    <?php while ($a = $autores->fetch_assoc()): ?>
                        <option value="<?php echo $a['Autor_ID']; ?>"><?php echo htmlspecialchars($a['Primeiro_Nome'] . ' ' . $a['Ultimo_Nome']); ?></option>
                    <?php endwhile; ?>
                </select>
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
