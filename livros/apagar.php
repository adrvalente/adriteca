<?php
require '../config.php';

$id  = (int)($_GET['id'] ?? 0);
$msg = 'sucesso=1';

if ($id > 0) {
    // Apaga primeiro as associações na tabela Livro_Autor para não violar a chave estrangeira
    $stmt = $conn->prepare("DELETE FROM Livro_Autor WHERE Livro_ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM Livro WHERE Livro_ID = ?");
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        // Provavelmente existem empréstimos associados a este livro
        $msg = 'erro=' . urlencode('Não é possível apagar: este livro tem empréstimos associados.');
    }
    $stmt->close();
}

$conn->close();
header('Location: listar.php?' . $msg);
exit;
?>
