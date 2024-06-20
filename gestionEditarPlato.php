<?php
function reArrayFiles(&$file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}
require 'conexion.php';
$id = $_POST['id'];
$nombre = $_POST['nombre'];
$precio = $_POST['precio'];
$peso = $_POST['peso'];
$calorias = $_POST['calorias'];
$category = $_POST['Category'];
$descripcion = $_POST['descripcion'];
if(is_uploaded_file($_FILES['imagenes']['tmp_name'])){
        $imgname = $_FILES['imagenes']['tmp_name'];
        $tamanio = $_FILES['imagenes']['size'];
        $altoimg = getimagesize($imgname)[1];
        $anchoimg = getimagesize($imgname)[0];
        $fp = fopen($imgname, 'r+b');
        $data = fread($fp, filesize($imgname));
        fclose($fp);
        $imgContent = addslashes(file_get_contents($imgname));
        $stmt2 = $conexion->prepare("DELETE FROM imagenes WHERE IDComida=?");
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $stmt2->close();
        $sql3 = "INSERT INTO imagenes (IDComida, imagen) VALUES ('$id', '$imgContent')";
        $conexion->query($sql3);
}
$stock = $_POST['stock'];
$stmt = $conexion->prepare("UPDATE comidas SET Nombre=?, Precio=?, Peso=?, Calorías=?, Tipo=?, Ingredientes=?, Tipo=?, Stock=? WHERE id=?");
$stmt->bind_param("ssssssssi", $nombre, $precio, $peso, $calorias, $category, $descripcion, $category, $stock, $id);
$stmt->execute();
$stmt->close();

header('Location: adminstracion_platos.php');


