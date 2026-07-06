<?php
require '../config.php';

$sql       = "SELECT * FROM Autor ORDER BY Ultimo_Nome, Primeiro_Nome";
$resultado = $conn->query($sql);

$titulo_pagina = 'Autores';
$pagina_ativa  = 'autores';
$caminho_base  = '../';
include '../includes/header.php';
?>

<div class="cabecalho-pagina">
    <div>
        <h2>Autores</h2>
        <p>Autores registados na biblioteca</p>
    </div>
    <a href="adicionar.php" class="btn btn-primario">+ Adicionar Autor</a>
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
                <th>Data de Nascimento</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado->num_rows === 0): ?>
                <tr><td colspan="3" class="vazio">Ainda não existem autores registados.</td></tr>
            <?php else: ?>
                <?php while ($autor = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($autor['Primeiro_Nome'] . ' ' . $autor['Ultimo_Nome']); ?></strong></td>
                    <td><?php echo htmlspecialchars($autor['Data_Aniversario'] ?? '—'); ?></td>
                    <td class="acoes">
                        <a href="editar.php?id=<?php echo $autor['Autor_ID']; ?>" class="btn btn-secundario btn-pequeno">Editar</a>
                        <a href="apagar.php?id=<?php echo $autor['Autor_ID']; ?>" class="btn btn-perigo btn-pequeno" onclick="return confirm('Tem a certeza que deseja apagar este autor?');">Apagar</a>
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
