<?php
require '../config.php';

$id  = (int)($_GET['id'] ?? 0);
$msg = 'sucesso=1';

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM Livro_Autor WHERE Autor_ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM Autor WHERE Autor_ID = ?");
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        $msg = 'erro=' . urlencode('Não foi possível apagar o autor.');
    }
    $stmt->close();
}

$conn->close();
header('Location: listar.php?' . $msg);
exit;
?>
