<?php
// Permitir solicitudes desde cualquier origen (CORS)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Obtener el contenido JSON de la solicitud
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Verificar si se recibieron los datos correctamente
if ($data === null) {
    echo json_encode(['success' => false, 'message' => 'Error al decodificar JSON']);
    exit;
}

// Validar los datos recibidos
if (empty($data['name']) || empty($data['phone'])) {
    echo json_encode(['success' => false, 'message' => 'Nombre y teléfono son obligatorios']);
    exit;
}

// Preparar los datos para guardar
$name = filter_var($data['name'], FILTER_SANITIZE_STRING);
$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
$phone = filter_var($data['phone'], FILTER_SANITIZE_STRING);
$message = filter_var($data['message'], FILTER_SANITIZE_STRING);

// Crear una cadena con los datos
$content = "Nombre: $name\n";
$content .= "Email: $email\n";
$content .= "Teléfono: $phone\n";
$content .= "Mensaje: $message\n";
$content .= "Fecha: " . date('Y-m-d H:i:s') . "\n\n";

// Guardar los datos en un archivo
$file = 'registros.txt';
if (file_put_contents($file, $content, FILE_APPEND | LOCK_EX) !== false) {
    echo json_encode(['success' => true, 'message' => 'Datos guardados correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar los datos']);
}