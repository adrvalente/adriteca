<?php
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($titulo_pagina) ? htmlspecialchars($titulo_pagina) . ' — Biblioteca' : 'Biblioteca'; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho_base; ?>assets/style.css">
</head>
<body>

    <aside class="sidebar">
    <h1>
    <img src="<?php echo $caminho_base; ?>image/icon.png" alt="Ícone de Biblioteca" width="50" height="50">
  AdriTeca
</h1>
        <nav>
            <a href="<?php echo $caminho_base; ?>index.php" class="<?php echo $pagina_ativa === 'inicio' ? 'ativo' : ''; ?>">🏠 Início</a>
            <a href="<?php echo $caminho_base; ?>livros/listar.php" class="<?php echo $pagina_ativa === 'livros' ? 'ativo' : ''; ?>">📖 Livros</a>
            <a href="<?php echo $caminho_base; ?>autores/listar.php" class="<?php echo $pagina_ativa === 'autores' ? 'ativo' : ''; ?>">✍️ Autores</a>
            <a href="<?php echo $caminho_base; ?>leitores/listar.php" class="<?php echo $pagina_ativa === 'leitores' ? 'ativo' : ''; ?>">🙋 Leitores</a>
            <a href="<?php echo $caminho_base; ?>emprestimos/listar.php" class="<?php echo $pagina_ativa === 'emprestimos' ? 'ativo' : ''; ?>">🔄 Empréstimos</a>
            <a href="<?php echo $caminho_base; ?>livro_autor/listar.php" class="<?php echo $pagina_ativa === 'livro_autor' ? 'ativo' : ''; ?>">🔗 Livro-Autor</a>
        <br><br></nav>
        <nav><strong><p>UC00605</p></strong>
        <p>Programar para a web, na vertente backend (server-side)</p>
        <p><strong>Prof.</strong> Gustavo Jorge</p>
        <p><strong>Formando:</strong> Adriano Valente</p></nav>
    </aside>
    <main class="conteudo">
