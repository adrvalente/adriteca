<?php
require '../config.php';

<<<<<<< Updated upstream
=======
$search = trim($_GET['search'] ?? '');
$page   = max(1, (int)($_GET['page'] ?? 1));
$limit  = 10;
$offset = ($page - 1) * $limit;

$where = '';
$params = [];
$types = '';

if ($search !== '') {
    $where = "WHERE l.Titulo LIKE ? OR CONCAT(a.Primeiro_Nome, ' ', a.Ultimo_Nome) LIKE ?";
    $term = "%{$search}%";
    $params = [$term, $term];
    $types = 'ss';
}

$countSql = "SELECT COUNT(*) AS total
             FROM Livro_Autor la
             JOIN Livro l ON l.Livro_ID = la.Livro_ID
             JOIN Autor a ON a.Autor_ID = la.Autor_ID
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
$sql = "SELECT la.ID_LA, l.Titulo, a.Primeiro_Nome, a.Ultimo_Nome
        FROM Livro_Autor la
        JOIN Livro l ON l.Livro_ID = la.Livro_ID
        JOIN Autor a ON a.Autor_ID = la.Autor_ID
<<<<<<< Updated upstream
        ORDER BY l.Titulo";
$resultado = $conn->query($sql);
=======
        $where
        ORDER BY l.Titulo, a.Ultimo_Nome, a.Primeiro_Nome
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

<<<<<<< Updated upstream
=======
<div class="cartao pesquisa-cartao">
    <form method="GET" action="" class="form-pesquisa">
        <input type="text" name="search" placeholder="Pesquisar por livro ou autor..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primario">Pesquisar</button>
        <?php if ($search !== ''): ?>
            <a href="listar.php" class="btn btn-secundario">Limpar</a>
        <?php endif; ?>
    </form>
    <p class="resultado-contador">
        <?php echo $total; ?> associação(ões) encontrada(s)
        <?php if ($search !== ''): ?> para "<?php echo htmlspecialchars($search); ?>"<?php endif; ?>.
    </p>
</div>

>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
                <tr><td colspan="3" class="vazio">Ainda não existem associações registadas.</td></tr>
=======
                <tr><td colspan="3" class="vazio">Não foram encontradas associações.</td></tr>
>>>>>>> Stashed changes
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
