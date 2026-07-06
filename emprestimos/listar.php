<?php
require '../config.php';

$sql = "SELECT e.*, l.Titulo AS titulo_livro, r.Primeiro_nome, r.Ultimo_nome
        FROM Emprestimo e
        JOIN Livro l ON l.Livro_ID = e.Livro_ID
        JOIN Leitor r ON r.Leitor_ID = e.Leitor_ID
        ORDER BY e.Data_Emp DESC";
$resultado = $conn->query($sql);

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
                <tr><td colspan="6" class="vazio">Ainda não existem empréstimos registados.</td></tr>
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

<?php
$conn->close();
include '../includes/footer.php';
?>
