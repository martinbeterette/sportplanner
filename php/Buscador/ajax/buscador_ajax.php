<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "proyecto_pp2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$search = $_GET['search'] ?? '';
$page = $_GET['page'] ?? 1;
$limit = 10; // número de resultados por página
$offset = ($page - 1) * $limit;

// Consulta a la base de datos
$sql = "SELECT * FROM items WHERE name LIKE ? OR description LIKE ? LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$search_param = "%$search%";
$stmt->bind_param("ssii", $search_param, $search_param, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

$data = $result->fetch_all(MYSQLI_ASSOC);

// Contar total de registros para la paginación
$sql_count = "SELECT COUNT(*) as total FROM items WHERE name LIKE ? OR description LIKE ?";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param("ss", $search_param, $search_param);
$stmt_count->execute();
$count_result = $stmt_count->get_result();
$total_rows = $count_result->fetch_assoc()['total'];

$response = [
    'data' => $data,
    'total_rows' => $total_rows,
    'total_pages' => ceil($total_rows / $limit),
    'current_page' => (int)$page
];

echo json_encode($response);

$conn->close();
?>
