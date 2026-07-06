<?php
require '../config.php';

$id  = (int)($_GET['id'] ?? 0);
$msg = 'sucesso=1';

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM Leitor WHERE Leitor_ID = ?");
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        // Provavelmente existem empréstimos associados a este leitor
        $msg = 'erro=' . urlencode('Não é possível apagar: este leitor tem empréstimos associados.');
    }
    $stmt->close();
}

$conn->close();
header('Location: listar.php?' . $msg);
exit;
?>
