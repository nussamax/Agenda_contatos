<?php
include 'db.php';

if (isset($_GET['contato_id'])) {
    $contato_id = $_GET['contato_id'];
    $sql = "SELECT * FROM telefones WHERE contato_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $contato_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $phone = $result->fetch_assoc();
    echo json_encode($phone);
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contato_id = $_POST['contato_id'];
    $telefone_comercial = $_POST['telefone_comercial'];
    $telefone_residencial = $_POST['telefone_residencial'];
    $telefone_celular = $_POST['telefone_celular'];

    $sql = "SELECT * FROM telefones WHERE contato_id = X";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $contato_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $sql = "UPDATE telefones SET telefone_comercial = X, telefone_residencial = X, telefone_celular = X WHERE contato_id = X";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $telefone_comercial, $telefone_residencial, $telefone_celular, $contato_id);
    } else {
        $sql = "INSERT INTO telefones (contato_id, telefone_comercial, telefone_residencial, telefone_celular) VALUES (X, X, X, X)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $contato_id, $telefone_comercial, $telefone_residencial, $telefone_celular);
    }
    $stmt->execute();
    header("Location: index.php");
}
?>
