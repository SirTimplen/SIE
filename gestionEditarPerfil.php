<?php
require 'conexion.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $domicilio = $_POST['domicilio'];
    $num_tarjeta = $_POST['num_tarjeta'];
    $telefono = $_POST['telefono'];
    $cvv = $_POST['cvv'];
    $fecha_caducidad = $_POST['fecha_caducidad'];

    // Obtener el ID del usuario desde la sesión
    $id = $_SESSION['id'];

    // Actualizar los datos en la base de datos
    // Preparar la consulta para actualizar los datos en las tablas correspondientes
    $sql_update_usuario = "UPDATE usuario SET email = ? WHERE id = ?";
    $sql_update_cliente = "UPDATE cliente SET nombre = ? WHERE id_usuario = ?";
    $sql_update_datos_pago = "UPDATE datospago SET Domicilio = ?, Número_tarjeta = ?, Teléfono = ?, CVV = ?, Fecha_caducidad = ? WHERE id = (SELECT id_datosPago FROM cliente WHERE id_usuario = ?)";

    // Preparar y ejecutar las consultas usando sentencias preparadas para evitar SQL injection
    $stmt_update_usuario = $conexion->prepare($sql_update_usuario);
    $stmt_update_usuario->bind_param("si", $correo, $id);
    $stmt_update_usuario->execute();

    $stmt_update_cliente = $conexion->prepare($sql_update_cliente);
    $stmt_update_cliente->bind_param("si", $nombre, $id);
    $stmt_update_cliente->execute();

    $stmt_update_datos_pago = $conexion->prepare($sql_update_datos_pago);
    $stmt_update_datos_pago->bind_param("sssisi", $domicilio, $num_tarjeta, $telefono, $cvv, $fecha_caducidad, $id);
    $stmt_update_datos_pago->execute();

    // Verificar si alguna consulta falló
    if ($stmt_update_usuario && $stmt_update_cliente && $stmt_update_datos_pago) {
        // Redireccionar a editarperfil.php con un mensaje de éxito
        $_SESSION['mensaje'] = "Los cambios se han guardado correctamente.";
        header("Location: perfil.php");
        exit();
    } else {
        // En caso de error, mostrar un mensaje de error o manejar la situación adecuadamente
        $_SESSION['error'] = "Ha ocurrido un error al intentar guardar los cambios.";
        header("Location: editarperfil.php");
        exit();
    }

    // Cerrar las sentencias preparadas y la conexión
    $stmt_update_usuario->close();
    $stmt_update_cliente->close();
    $stmt_update_datos_pago->close();
    $conexion->close();
} else {
    // Si se intenta acceder a este script de manera incorrecta (sin POST), redirigir a editarperfil.php
    header("Location: editarperfil.php");
    exit();
}
?>
