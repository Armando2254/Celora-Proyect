<?php
require 'database.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $quantity = $_POST['quantity'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];


    try {
        // Iniciar una transacción para garantizar consistencia
        $pdo->beginTransaction();

        // Calcular el siguiente código para la tabla 'stock'
        $stmt = $pdo->query("SELECT MAX(code) AS max_code FROM stock");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $nextCode = ($row['max_code'] ?? 0) + 1; // Manejo de tabla vacía

       // Insertar en la tabla 'stock' sin especificar 'code' ya que es AUTO_INCREMENT
$stmt = $pdo->prepare("INSERT INTO stock (quantity) VALUES (:quantity)");
$stmt->execute([
    ':quantity' => $quantity
]);

// Insertar en la tabla 'product'
$stmt = $pdo->prepare("INSERT INTO product (name, price, description, stockCode) 
                       VALUES (:name, :price, :description, LAST_INSERT_ID())");
$stmt->execute([
    ':name' => $name,
    ':price' => $price,
    ':description' => $description
]);



        // Confirmar la transacción
        $pdo->commit();
        header('Location: products.php'); // Redirigir a la página principal
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $pdo->rollBack();
        echo "Error al insertar el producto: " . $e->getMessage();
    }
}
?>
