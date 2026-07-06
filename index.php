<?php
require 'config.php';

$total_livros      = $conn->query("SELECT COUNT(*) AS total FROM Livro")->fetch_assoc()['total'];
$total_autores     = $conn->query("SELECT COUNT(*) AS total FROM Autor")->fetch_assoc()['total'];
$total_leitores    = $conn->query("SELECT COUNT(*) AS total FROM Leitor")->fetch_assoc()['total'];
$total_emprestimos = $conn->query("SELECT COUNT(*) AS total FROM Emprestimo")->fetch_assoc()['total'];


$total_atrasados = $conn->query(
    "SELECT COUNT(*) AS total FROM Emprestimo WHERE Date_Entrega IS NULL AND Data_Vencimento < CURDATE()"
)->fetch_assoc()['total'];

$titulo_pagina = 'Início';
$pagina_ativa  = 'inicio';
$caminho_base  = '';
include 'includes/header.php';
?>

<div class="cabecalho-pagina">
    <div>
        <h2>Painel da Biblioteca</h2>
        <p>Visão geral da base de dados</p>
    </div>
</div>

<div class="grelha-estatisticas">
    <a href="livros/listar.php" class="cartao-estatistica">
        <div class="icone">📖</div>
        <div>
            <div class="numero"><?php echo $total_livros; ?></div>
            <div class="rotulo">Livros</div>
        </div>
    </a>
    <a href="autores/listar.php" class="cartao-estatistica">
        <div class="icone">✍️</div>
        <div>
            <div class="numero"><?php echo $total_autores; ?></div>
            <div class="rotulo">Autores</div>
        </div>
    </a>
    <a href="leitores/listar.php" class="cartao-estatistica">
        <div class="icone">🙋</div>
        <div>
            <div class="numero"><?php echo $total_leitores; ?></div>
            <div class="rotulo">Leitores</div>
        </div>
    </a>
    <a href="emprestimos/listar.php" class="cartao-estatistica">
        <div class="icone">🔄</div>
        <div>
            <div class="numero"><?php echo $total_emprestimos; ?></div>
            <div class="rotulo">Empréstimos</div>
        </div>
    </a>
</div>

<?php if ($total_atrasados > 0): ?>
<div class="alerta alerta-erro">
    ⚠️ Existem <?php echo $total_atrasados; ?> empréstimo(s) em atraso por devolver.
    <a href="emprestimos/listar.php">Ver empréstimos</a>
</div>
<?php endif; ?>

<div class="cartao">
    <h3 style="margin-top:0;">Acesso rápido</h3>
    <div class="acoes-form" style="flex-wrap:wrap;">
        <a href="livros/adicionar.php" class="btn btn-primario">+ Novo Livro</a>
        <a href="autores/adicionar.php" class="btn btn-primario">+ Novo Autor</a>
        <a href="leitores/adicionar.php" class="btn btn-primario">+ Novo Leitor</a>
        <a href="emprestimos/adicionar.php" class="btn btn-primario">+ Novo Empréstimo</a>
    </div>
</div>

<?php
$conn->close();
include 'includes/footer.php';
?>
