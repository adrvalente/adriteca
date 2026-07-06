<?php
require '../config.php';

<<<<<<< Updated upstream
// Vai buscar todos os livros, incluindo os autores associados (via Livro_Autor)
=======
$search = trim($_GET['search'] ?? '');
$page   = max(1, (int)($_GET['page'] ?? 1));
$limit  = 10;
$offset = ($page - 1) * $limit;

$where = '';
$params = [];
$types = '';

if ($search !== '') {
    $where = "WHERE l.Titulo LIKE ? OR l.Genero LIKE ? OR l.ISBN LIKE ? OR CONCAT(a.Primeiro_Nome, ' ', a.Ultimo_Nome) LIKE ?";
    $term = "%{$search}%";
    $params = [$term, $term, $term, $term];
    $types = 'ssss';
}

$countSql = "SELECT COUNT(DISTINCT l.Livro_ID) AS total
             FROM Livro l
             LEFT JOIN Livro_Autor la ON la.Livro_ID = l.Livro_ID
             LEFT JOIN Autor a ON a.Autor_ID = la.Autor_ID
             $where";
$countStmt = $conn->prepare($countSql);
if ($types !== '') {
    $countStmt->bind_param($types, ...$params);
}
$countStmt->execute();
$total = (int)$countStmt->get_result()->fetch_assoc()['total'];
$countStmt->close();
$totalPages = max(1, (int)ceil($total / $limit));
if ($page > $totalPages) {
    $page = $totalPages;
    $offset = ($page - 1) * $limit;
}

>>>>>>> Stashed changes
$sql = "SELECT l.*,
               GROUP_CONCAT(CONCAT(a.Primeiro_Nome, ' ', a.Ultimo_Nome) SEPARATOR ', ') AS autores
        FROM Livro l
        LEFT JOIN Livro_Autor la ON la.Livro_ID = l.Livro_ID
        LEFT JOIN Autor a ON a.Autor_ID = la.Autor_ID
<<<<<<< Updated upstream
        GROUP BY l.Livro_ID
        ORDER BY l.Titulo";
$resultado = $conn->query($sql);
=======
        $where
        GROUP BY l.Livro_ID
        ORDER BY l.Titulo
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
if ($types !== '') {
    $stmt->bind_param($types . 'ii', ...array_merge($params, [$limit, $offset]));
} else {
    $stmt->bind_param('ii', $limit, $offset);
}
$stmt->execute();
$resultado = $stmt->get_result();
>>>>>>> Stashed changes

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

<<<<<<< Updated upstream
=======
<div class="cartao pesquisa-cartao">
    <form method="GET" action="" class="form-pesquisa">
        <input type="text" name="search" placeholder="Pesquisar por título, género, ISBN ou autor..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primario">Pesquisar</button>
        <?php if ($search !== ''): ?>
            <a href="listar.php" class="btn btn-secundario">Limpar</a>
        <?php endif; ?>
    </form>
    <p class="resultado-contador">
        <?php echo $total; ?> livro(s) encontrado(s)
        <?php if ($search !== ''): ?> para "<?php echo htmlspecialchars($search); ?>"<?php endif; ?>.
    </p>
</div>

>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
                <tr><td colspan="6" class="vazio">Ainda não existem livros registados.</td></tr>
=======
                <tr><td colspan="6" class="vazio">Não foram encontrados livros.</td></tr>
>>>>>>> Stashed changes
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

<<<<<<< Updated upstream
<?php
=======
<?php if ($totalPages > 1): ?>
    <div class="paginacao">
        <?php if ($page > 1): ?>
            <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page - 1; ?>" class="btn btn-secundario btn-pequeno">Anterior</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>" class="pagina-link <?php echo $i === $page ? 'ativo' : ''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page + 1; ?>" class="btn btn-secundario btn-pequeno">Seguinte</a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php
$stmt->close();
>>>>>>> Stashed changes
$conn->close();
include '../includes/footer.php';
?>
