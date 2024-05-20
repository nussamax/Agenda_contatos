<?php
include 'db.php';

if (isset($_GET['contato_id'])) {
    $contato_id = $_GET['contato_id'];
    $sql = "SELECT * FROM telefones WHERE contato_id = X";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $contato_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $phone = $result->fetch_assoc();
    echo json_encode($phone);
}
?>