<?php
header('Content-type: application/json; charset=utf-8');
$host = "complist.mysql.database.azure.com";
$user = "complist";
$db_password = "ISI2023-2024";
$db = "sabercomer";
$conexion = new mysqli($host, $user, $db_password, $db);
if (!$conexion) {
    echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
    echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
    echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
/*function editar() {
    var nombre = document.getElementById("nombre").value;
    var Category = document.getElementById("Category").value;
    var precio = document.getElementById("precio").value;
    var decripcion = document.getElementById("decripcion").value;
    //obten la id en la url
    id = window.location.search.split('=')[1];
    var data = {
        id: id,
        nombre: nombre,
        Category: Category,
        precio: precio,
        decripcion: decripcion
    };

    console.log(data); // Log the data to the console

    $.ajax({
        type: "POST",
        url: "apis.php?editar-plato",
        data: JSON.stringify(data), // Stringify the data object
        contentType: "application/json", // Set the content type to application/json
        success: function(data) {
            console.log(data);
        }
    });
}*/
header('Content-type: application/json; charset=utf-8');
session_start();
if ($conexion->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conexion->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' and isset($_GET['editarperfil'])) {
    // Obtener los datos del cuerpo de la solicitud
    $input = json_decode(file_get_contents('php://input'), true);
    $nombre = $input['nombre'];
    $correo = $input['correo'];
    // Preparar la consulta SQL
    $sql = "UPDATE usuarios SET Nombre=?, Correo=? WHERE id=?";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssi", $nombre, $correo, $_SESSION['id']);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Update successful"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conexion->error]);
    }
}

//login
if ($_SERVER['REQUEST_METHOD'] === 'POST' and isset($_GET['login'])) {
    // Obtener los datos del cuerpo de la solicitud
    $input = json_decode(file_get_contents('php://input'), true);
    $username = $input['username'];
    $password = $input['password'];

    // Preparar la consulta SQL
    $sql = "SELECT * FROM usuario WHERE email=? AND contraseña=?";
    $stmt = $conexion->prepare($sql);


    if ($stmt) {
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['correo'] = $username;
                echo json_encode(array("success" => true, "data" => $user));
            } else {
                echo json_encode(["status" => "error", "message" => "Invalid username or password"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conexion->error]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['registro'])) {
    // Get the data from the request body
    $input = json_decode(file_get_contents('php://input'), true);
    $username = $input['username'];
    $email = $input['email'];
    $password = $input['password'];

    // Get the last id from the usuario table
    $result = $conexion->query("SELECT MAX(id) AS max_id FROM usuario");
    $row = $result->fetch_assoc();
    $id = $row['max_id'] + 1;

    // Prepare the SQL query
    $sql = "INSERT INTO usuario (id, email, contraseña, fecha_alta) VALUES (?, ?, ?, NOW())";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("iss", $id, $email, $password);

        if ($stmt->execute()) {
            echo json_encode(array("success" => true));
        } else {
            echo json_encode(array("success" => false, "message" => "Error al registrar el usuario". $stmt->error));
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conexion->error]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' and isset($_GET['editarplatos'])) {
    // Obtener los datos del cuerpo de la solicitud
    $input = json_decode(file_get_contents('php://input'), true);
    $stock = $input['stock'];
    $nombre = $input['nombre'];
    $category = $input['category'];
    $precio = $input['precio'];
    $descripcion = $input['descripcion'];
    $id = $input['id'];
    $imagen = $input['imagen'];
    // Preparar la consulta SQL
    $sql = "UPDATE comidas SET Nombre=?, Precio=?, Ingredientes=? WHERE id=?";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sdsi", $nombre, $precio, $descripcion, $id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Update successful"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conexion->error]);
    }
    $sql2 = "DELETE FROM imagenes WHERE IDComida=?";
    $stmt2 = $conexion->prepare($sql2);
    if ($stmt2) {
        $stmt2->bind_param("si", $imagen, $id);
    }
    $sql3 = "INSERT INTO imagenes (IDComida, imagen) VALUES (?, ?)";
    $stmt3 = $conexion->prepare($sql3);
    if ($stmt3) {
        $stmt3->bind_param("is", $id, $imagen);
    }

}

if ($_SERVER['REQUEST_METHOD'] === 'POST' and isset($_GET['añadirplatos'])) {
    // Obtener los datos del cuerpo de la solicitud
    $input = json_decode(file_get_contents('php://input'), true);
    $stock = $input['stock'];
    $nombre = $input['nombre'];
    $category = $input['category'];
    $precio = $input['precio'];
    $descripcion = $input['descripcion'];

    // Preparar la consulta SQL
    $sql = "INSERT INTO comidas (Nombre, Precio, Ingredientes) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sds", $nombre, $precio, $descripcion);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Insert successful"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conexion->error]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' and isset($_GET['eliminarplatos'])) {
    // Obtener los datos del cuerpo de la solicitud
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'];
    $sql = "DELETE FROM comidas WHERE id=?";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Delete successful"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conexion->error]);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' and isset($_GET['cerrar'])) {
    session_start();
    session_destroy();
    echo json_encode(["status" => "success", "message" => "Session closed"]);
}

/*function aniadirCarrito(id){
    var cantidad = 1;
    var data = {
        id: id,
        cantidad: cantidad
    };

    console.log(data);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "apis.php?añadirCarrito", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText);
            var response = JSON.parse(xhr.responseText);
            if (response.status === "success") {
                alert("Operation successful");
            } else {
                console.error("Error: ", response.message);
            }
        } else {
            console.error("Error: ", xhr.status);
        }
    };
    xhr.send(JSON.stringify(data));
}*/
if($_SERVER['REQUEST_METHOD'] === 'POST' and isset($_GET['añadirCarrito'])){
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data, true);
    $id = $data['id'];
    $cantidad = $data['cantidad'];
    $id_usuario = $_SESSION['id'];
    // Verificar si la comida ya está en el carrito
    $sql = "SELECT * FROM carrito WHERE id_comida = ? AND id_usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $id, $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    if ($item) {
        // Si la comida ya está en el carrito, incrementar la cantidad
        $sql = "UPDATE carrito SET cantidad = cantidad + ? WHERE id_comida = ? AND id_usuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iii", $cantidad, $id, $id_usuario);
    } else {
        // Si la comida no está en el carrito, insertarla
        $sql = "INSERT INTO carrito (id_comida, cantidad, id_usuario) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iii", $id, $cantidad, $id_usuario);
    }

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Operation successful","data"=>[
            "cantidad"=>$cantidad
        ]]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
}
//decrementar cantidad
if ($_SERVER['REQUEST_METHOD'] === 'POST' and isset($_GET['decrementarCantidad'])) {
    // Obtener los datos del cuerpo de la solicitud
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'];
    $id_usuario = $_SESSION['id'];

    // Preparar la consulta SQL
    $sql = "UPDATE carrito SET cantidad = cantidad - 1 WHERE id_comida = ? AND id_usuario = ?";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ii", $id, $id_usuario);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Update successful"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conexion->error]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' and isset($_GET['eliminarCarrito'])) {
    // Obtener los datos del cuerpo de la solicitud
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'];
    $id_usuario = $_SESSION['id'];

    // Preparar la consulta SQL
    $sql = "DELETE FROM carrito WHERE id_comida=? AND id_usuario=?";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ii", $id, $id_usuario);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Delete successful"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conexion->error]);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' and isset($_GET['eliminarPedido'])) {
    // Obtener los datos del cuerpo de la solicitud
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'];
    $id_usuario = $_SESSION['id'];

    // Preparar la consulta SQL
    $sql = "DELETE FROM comidas_de_pedidos WHERE id_pedido=?";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    $sql = "DELETE FROM pedidos WHERE id=?";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Delete successful"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conexion->error]);
    }
}

$conexion->close();
?>