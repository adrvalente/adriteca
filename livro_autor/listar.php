<?php
require '../config.php';

$sql = "SELECT la.ID_LA, l.Titulo, a.Primeiro_Nome, a.Ultimo_Nome
        FROM Livro_Autor la
        JOIN Livro l ON l.Livro_ID = la.Livro_ID
        JOIN Autor a ON a.Autor_ID = la.Autor_ID
        ORDER BY l.Titulo";
$resultado = $conn->query($sql);

$titulo_pagina = 'Livro-Autor';
$pagina_ativa  = 'livro_autor';
$caminho_base  = '../';
include '../includes/header.php';
?>

<div class="cabecalho-pagina">
    <div>
        <h2>Associações Livro-Autor</h2>
        <p>Relaciona livros com os respetivos autores (relação muitos-para-muitos)</p>
    </div>
    <a href="adicionar.php" class="btn btn-primario">+ Nova Associação</a>
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
                <th>Livro</th>
                <th>Autor</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado->num_rows === 0): ?>
                <tr><td colspan="3" class="vazio">Ainda não existem associações registadas.</td></tr>
            <?php else: ?>
                <?php while ($la = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($la['Titulo']); ?></strong></td>
                    <td><?php echo htmlspecialchars($la['Primeiro_Nome'] . ' ' . $la['Ultimo_Nome']); ?></td>
                    <td class="acoes">
                        <a href="apagar.php?id=<?php echo $la['ID_LA']; ?>" class="btn btn-perigo btn-pequeno" onclick="return confirm('Remover esta associação?');">Remover</a>
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
