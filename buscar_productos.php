<?php
header('Content-Type: application/json');

$host = "complist.mysql.database.azure.com";
$user = "complist";
$db_password = "ISI2023-2024";
$db = "sabercomer";

$conexion = new mysqli($host, $user, $db_password, $db);

if ($conexion->connect_error) {
    die("Connection failed: " . $conexion->connect_error);
}

$query = isset($_GET['query']) ? $_GET['query'] : '';

if ($query) {
    $stmt = $conexion->prepare("SELECT * FROM comidas WHERE Nombre LIKE ?");
    $search = "%{$query}%";
    $stmt->bind_param("s", $search);
} else {
    $stmt = $conexion->prepare("SELECT * FROM comidas");
}

$stmt->execute();
$result = $stmt->get_result();

$productos = [];

while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

$stmt->close();
$conexion->close();

echo json_encode($productos);
?>
<?php
header('Content-Type: application/json');

$host = "complist.mysql.database.azure.com";
$user = "complist";
$db_password = "ISI2023-2024";
$db = "sabercomer";

$conexion = new mysqli($host, $user, $db_password, $db);

if ($conexion->connect_error) {
    die("Connection failed: " . $conexion->connect_error);
}

$query = isset($_GET['query']) ? $_GET['query'] : '';

if ($query) {
    $stmt = $conexion->prepare("SELECT * FROM comidas WHERE Nombre LIKE ?");
    $search = "%{$query}%";
    $stmt->bind_param("s", $search);
} else {
    $stmt = $conexion->prepare("SELECT * FROM comidas");
}

$stmt->execute();
$result = $stmt->get_result();

$productos = [];

while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

$stmt->close();
$conexion->close();

echo json_encode($productos);
?>
