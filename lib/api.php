<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');



if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action']==='getConfig') {
    $action = $_GET['action'] ?? null; 
    echo json_encode([
        'status' => 'success',
        'action' => $action,
        'data' => [
            'printer' => [
                'name' => 'Microsoft XPS Document Writer',
                'paper_size' => '80x60x11',
                'margin' => '4px'
            ]
        ]
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $printerName = $input['printerName'] ?? 'Unknown';

    echo json_encode([
        'status' => 'success',
        'posted' => true,
        'message' => "Printer $printerName added successfully!"
    ]);
    exit;
}

http_response_code(405); // Method Not Allowed
echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
exit;
?>
