<?php
require '../config.php';

// Vai buscar todos os livros, incluindo os autores associados (via Livro_Autor)
$sql = "SELECT l.*,
               GROUP_CONCAT(CONCAT(a.Primeiro_Nome, ' ', a.Ultimo_Nome) SEPARATOR ', ') AS autores
        FROM Livro l
        LEFT JOIN Livro_Autor la ON la.Livro_ID = l.Livro_ID
        LEFT JOIN Autor a ON a.Autor_ID = la.Autor_ID
        GROUP BY l.Livro_ID
        ORDER BY l.Titulo";
$resultado = $conn->query($sql);

$titulo_pagina = 'Livros';
$pagina_ativa  = 'livros';
$caminho_base  = '../';
include '../includes/header.php';
?>

<div class="cabecalho-pagina">
    <div>
        <h2>Livros</h2>
        <p>Catálogo de livros da biblioteca</p>
    </div>
    <a href="adicionar.php" class="btn btn-primario">+ Adicionar Livro</a>
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
                <th>Título</th>
                <th>Género</th>
                <th>Ano</th>
                <th>ISBN</th>
                <th>Autor(es)</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado->num_rows === 0): ?>
                <tr><td colspan="6" class="vazio">Ainda não existem livros registados.</td></tr>
            <?php else: ?>
                <?php while ($livro = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($livro['Titulo']); ?></strong></td>
                    <td><?php echo htmlspecialchars($livro['Genero'] ?? '—'); ?></td>
                    <td><?php echo htmlspecialchars($livro['Ano_Publicacao'] ?? '—'); ?></td>
                    <td><?php echo htmlspecialchars($livro['ISBN']); ?></td>
                    <td><?php echo htmlspecialchars($livro['autores'] ?? '—'); ?></td>
                    <td class="acoes">
                        <a href="editar.php?id=<?php echo $livro['Livro_ID']; ?>" class="btn btn-secundario btn-pequeno">Editar</a>
                        <a href="apagar.php?id=<?php echo $livro['Livro_ID']; ?>" class="btn btn-perigo btn-pequeno" onclick="return confirm('Tem a certeza que deseja apagar este livro?');">Apagar</a>
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
