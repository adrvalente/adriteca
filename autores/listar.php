<?php
require '../config.php';

<<<<<<< Updated upstream
$sql       = "SELECT * FROM Autor ORDER BY Ultimo_Nome, Primeiro_Nome";
$resultado = $conn->query($sql);
=======
$search = trim($_GET['search'] ?? '');
$page   = max(1, (int)($_GET['page'] ?? 1));
$limit  = 10;
$offset = ($page - 1) * $limit;

$where = '';
$params = [];
$types = '';

if ($search !== '') {
    $where = "WHERE Primeiro_Nome LIKE ? OR Ultimo_Nome LIKE ? OR CONCAT(Primeiro_Nome, ' ', Ultimo_Nome) LIKE ?";
    $term = "%{$search}%";
    $params = [$term, $term, $term];
    $types = 'sss';
}

$countSql = "SELECT COUNT(*) AS total FROM Autor $where";
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

$sql = "SELECT * FROM Autor
        $where
        ORDER BY Ultimo_Nome, Primeiro_Nome
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

<<<<<<< Updated upstream
=======
<div class="cartao pesquisa-cartao">
    <form method="GET" action="" class="form-pesquisa">
        <input type="text" name="search" placeholder="Pesquisar por nome do autor..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primario">Pesquisar</button>
        <?php if ($search !== ''): ?>
            <a href="listar.php" class="btn btn-secundario">Limpar</a>
        <?php endif; ?>
    </form>
    <p class="resultado-contador">
        <?php echo $total; ?> autor(es) encontrado(s)
        <?php if ($search !== ''): ?> para "<?php echo htmlspecialchars($search); ?>"<?php endif; ?>.
    </p>
</div>

>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
                <tr><td colspan="3" class="vazio">Ainda não existem autores registados.</td></tr>
=======
                <tr><td colspan="3" class="vazio">Não foram encontrados autores.</td></tr>
>>>>>>> Stashed changes
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
