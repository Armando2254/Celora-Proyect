<?php
include 'config.php';

$id = $_GET['id'];

// Obtener la cotización
$query = $pdo->prepare("SELECT * FROM quotation WHERE id = ?");
$query->execute([$id]);
$cotizacion = $query->fetch(PDO::FETCH_ASSOC);

// Obtener productos asociados a la requisición
$query = $pdo->prepare("
    SELECT p.name, pr.quantity
    FROM product_requisition pr
    JOIN product p ON pr.productCode = p.code
    WHERE pr.requisitionId = (
        SELECT requisitionId FROM report WHERE quotationId = ?
    )
");
$query->execute([$id]);
$productos = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Cotización</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="images/logoCAB.png" type="images/png">
</head>
<body>
    <div class="container mt-5">
        <h1>Detalles de la Cotización #<?php echo $cotizacion['id']; ?></h1>
        <p><strong>Fecha:</strong> <?php echo $cotizacion['issueDate']; ?></p>
        <p><strong>Total:</strong> $<?php echo number_format($cotizacion['total'], 2); ?></p>
        <h3>Productos Asociados</h3>
        <ul>
            <?php foreach ($productos as $producto): ?>
                <li><?php echo $producto['name']; ?> (Cantidad: <?php echo $producto['quantity']; ?>)</li>
            <?php endforeach; ?>
        </ul>
        <a href="index.php" class="btn btn-primary">Volver</a>
    </div>
</body>
</html>
