<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contato_id = $_POST['contato_id'];
    $cep = $_POST['cep'];
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];

    $sql = "SELECT * FROM enderecos WHERE contato_id = X";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $contato_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $sql = "UPDATE enderecos SET cep = X, rua = X, numero = X, bairro = X, cidade = X, estado = X WHERE contato_id = X";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $cep, $rua, $numero, $bairro, $cidade, $estado, $contato_id);
    } else {
        $sql = "INSERT INTO enderecos (contato_id, cep, rua, numero, bairro, cidade, estado) VALUES (X, X, X, X, X, X, X)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssss", $contato_id, $cep, $rua, $numero, $bairro, $cidade, $estado);
    }
    $stmt->execute();
    header("Location: index.php");
}
?>