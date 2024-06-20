<?php
require 'conexion.php';
$nombre = $_POST['nombre'];
$precio = $_POST['precio'];
$peso = $_POST['peso'];
$calorias = $_POST['calorias'];
$category = $_POST['Category'];
$descripcion = $_POST['descripcion'];
$stock = $_POST['stock'];
$stmt = $conexion->prepare("INSERT INTO comidas (Nombre, Precio, Peso, Calorías, Ingredientes, Tipo, Stock) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssi", $nombre, $precio, $peso, $calorias, $descripcion, $category, $stock);
$stmt->execute();
$stmt->close();
if(is_uploaded_file($_FILES['imagenes']['tmp_name'])){
    $imgname = $_FILES['imagenes']['tmp_name'];
    $tamanio = $_FILES['imagenes']['size'];
    $altoimg = getimagesize($imgname)[1];
    $anchoimg = getimagesize($imgname)[0];
    $fp = fopen($imgname, 'r+b');
    $data = fread($fp, filesize($imgname));
    fclose($fp);
    $imgContent = addslashes(file_get_contents($imgname));
    $stmt2 = $conexion->prepare("SELECT id FROM comidas WHERE Nombre=?");
    $stmt2->bind_param("s", $nombre);
    $stmt2->execute();
    $result = $stmt2->get_result();
    $id = $result->fetch_assoc()['id'];
    $stmt2->close();
    $sql3 = "INSERT INTO imagenes (IDComida, imagen) VALUES ('$id', '$imgContent')";
    $conexion->query($sql3);
}
$conexion->close();
header('Location: adminstracion_platos.php');

