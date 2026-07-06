<?php
require '../config.php';

$sql       = "SELECT * FROM Leitor ORDER BY Ultimo_nome, Primeiro_nome";
$resultado = $conn->query($sql);

$titulo_pagina = 'Leitores';
$pagina_ativa  = 'leitores';
$caminho_base  = '../';
include '../includes/header.php';
?>

<div class="cabecalho-pagina">
    <div>
        <h2>Leitores</h2>
        <p>Membros inscritos na biblioteca</p>
    </div>
    <a href="adicionar.php" class="btn btn-primario">+ Adicionar Leitor</a>
</div>

<?php if (isset($_GET['sucesso'])): ?>
    <div class="alerta alerta-sucesso">Operação realizada com sucesso.</div>
<?php elseif (isset($_GET['erro'])): ?>
    <div class="alerta alerta-erro"><?php echo htmlspecialchars($_GET['erro']); ?></div>
<?php endif; ?>

<div class="tabela-wrapper">
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Telemóvel</th>
                <th>Morada</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado->num_rows === 0): ?>
                <tr><td colspan="5" class="vazio">Ainda não existem leitores registados.</td></tr>
            <?php else: ?>
                <?php while ($leitor = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($leitor['Primeiro_nome'] . ' ' . $leitor['Ultimo_nome']); ?></strong></td>
                    <td><?php echo htmlspecialchars($leitor['Email'] ?? '—'); ?></td>
                    <td><?php echo htmlspecialchars($leitor['Telemovel'] ?? '—'); ?></td>
                    <td><?php echo htmlspecialchars($leitor['Morada'] ?? '—'); ?></td>
                    <td class="acoes">
                        <a href="editar.php?id=<?php echo $leitor['Leitor_ID']; ?>" class="btn btn-secundario btn-pequeno">Editar</a>
                        <a href="apagar.php?id=<?php echo $leitor['Leitor_ID']; ?>" class="btn btn-perigo btn-pequeno" onclick="return confirm('Tem a certeza que deseja apagar este leitor?');">Apagar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$conn->close();
include '../includes/footer.php';
?>
