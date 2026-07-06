<?php
require '../config.php';

<<<<<<< Updated upstream
$sql = "SELECT e.*, l.Titulo AS titulo_livro, r.Primeiro_nome, r.Ultimo_nome
        FROM Emprestimo e
        JOIN Livro l ON l.Livro_ID = e.Livro_ID
        JOIN Leitor r ON r.Leitor_ID = e.Leitor_ID
        ORDER BY e.Data_Emp DESC";
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
    $where = "WHERE l.Titulo LIKE ? OR CONCAT(r.Primeiro_nome, ' ', r.Ultimo_nome) LIKE ? OR r.Email LIKE ?";
    $term = "%{$search}%";
    $params = [$term, $term, $term];
    $types = 'sss';
}

$countSql = "SELECT COUNT(*) AS total
             FROM Emprestimo e
             JOIN Livro l ON l.Livro_ID = e.Livro_ID
             JOIN Leitor r ON r.Leitor_ID = e.Leitor_ID
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

$sql = "SELECT e.*, l.Titulo AS titulo_livro, r.Primeiro_nome, r.Ultimo_nome, r.Email
        FROM Emprestimo e
        JOIN Livro l ON l.Livro_ID = e.Livro_ID
        JOIN Leitor r ON r.Leitor_ID = e.Leitor_ID
        $where
        ORDER BY e.Data_Emp DESC
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

$titulo_pagina = 'Empréstimos';
$pagina_ativa  = 'emprestimos';
$caminho_base  = '../';
include '../includes/header.php';
?>

<div class="cabecalho-pagina">
    <div>
        <h2>Empréstimos</h2>
        <p>Registo de empréstimos de livros</p>
    </div>
    <a href="adicionar.php" class="btn btn-primario">+ Novo Empréstimo</a>
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
        <input type="text" name="search" placeholder="Pesquisar por livro, leitor ou email..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primario">Pesquisar</button>
        <?php if ($search !== ''): ?>
            <a href="listar.php" class="btn btn-secundario">Limpar</a>
        <?php endif; ?>
    </form>
    <p class="resultado-contador">
        <?php echo $total; ?> empréstimo(s) encontrado(s)
        <?php if ($search !== ''): ?> para "<?php echo htmlspecialchars($search); ?>"<?php endif; ?>.
    </p>
</div>

>>>>>>> Stashed changes
<div class="tabela-wrapper">
    <table>
        <thead>
            <tr>
                <th>Livro</th>
                <th>Leitor</th>
                <th>Data Empréstimo</th>
                <th>Data Vencimento</th>
                <th>Estado</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado->num_rows === 0): ?>
<<<<<<< Updated upstream
                <tr><td colspan="6" class="vazio">Ainda não existem empréstimos registados.</td></tr>
=======
                <tr><td colspan="6" class="vazio">Não foram encontrados empréstimos.</td></tr>
>>>>>>> Stashed changes
            <?php else: ?>
                <?php while ($emp = $resultado->fetch_assoc()): ?>
                <?php
                    if ($emp['Date_Entrega']) {
                        $estado = ['texto' => 'Devolvido', 'classe' => 'etiqueta-neutro'];
                    } elseif (strtotime($emp['Data_Vencimento']) < strtotime(date('Y-m-d'))) {
                        $estado = ['texto' => 'Em atraso', 'classe' => 'etiqueta-atraso'];
                    } else {
                        $estado = ['texto' => 'Ativo', 'classe' => 'etiqueta-ativo'];
                    }
                ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($emp['titulo_livro']); ?></strong></td>
                    <td><?php echo htmlspecialchars($emp['Primeiro_nome'] . ' ' . $emp['Ultimo_nome']); ?></td>
                    <td><?php echo htmlspecialchars($emp['Data_Emp']); ?></td>
                    <td><?php echo htmlspecialchars($emp['Data_Vencimento']); ?></td>
                    <td><span class="etiqueta <?php echo $estado['classe']; ?>"><?php echo $estado['texto']; ?></span></td>
                    <td class="acoes">
                        <a href="editar.php?id=<?php echo $emp['Emprestimo_ID']; ?>" class="btn btn-secundario btn-pequeno">Editar</a>
                        <a href="apagar.php?id=<?php echo $emp['Emprestimo_ID']; ?>" class="btn btn-perigo btn-pequeno" onclick="return confirm('Tem a certeza que deseja apagar este empréstimo?');">Apagar</a>
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
