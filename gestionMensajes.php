<?php
require 'conexion.php';
session_start();
$receptor = $_POST['receptor'];
$mensaje = $_POST['mensaje'];
$fecha = date("Y-m-d H:i:s");
if($receptor == 0){
    $sql = "SELECT id FROM usuario";
    $result = $conexion->query($sql);
    while($row = $result->fetch_assoc()){
        $id_receptor = $row['id'];
        $sql = "INSERT INTO mensajes (id_remitente, id_receptor, mensaje, fecha) VALUES (?,?,?,?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iiss", $_SESSION['id'], $id_receptor, $mensaje, $fecha);
        $stmt->execute();
    }
}
else if($receptor=='Jefe'){
    $sql = "SELECT usuario.id FROM usuario INNER JOIN duenio ON usuario.id=duenio.id_usuario;";
    $result = $conexion->query($sql);
    $row = $result->fetch_assoc();
    $id_receptor = $row['id'];
    $sql = "INSERT INTO mensajes (id_remitente, id_receptor, mensaje, fecha) VALUES (?,?,?,?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("iiss", $_SESSION['id'], $id_receptor, $mensaje, $fecha);
    $stmt->execute();
}
else{
    $id_receptor = $receptor;
    $sql = "INSERT INTO mensajes (id_remitente, id_receptor, mensaje, fecha) VALUES (?,?,?,?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("iiss", $_SESSION['id'], $id_receptor, $mensaje, $fecha);
    $stmt->execute();
}
header("Location: mensajesAdmin.php");
?>
