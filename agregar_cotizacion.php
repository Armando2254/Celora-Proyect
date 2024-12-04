<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $issueDate = $_POST['issueDate'];
    $total = $_POST['total'];
    $subtotal = $_POST['subtotal'];
    $supplierId = $_POST['supplierId'];
    $requisitionId = $_POST['requisitionId'];

    // Insertar la nueva cotización
    $query = $pdo->prepare("INSERT INTO quotation (id, issueDate, total, subtotal, supplierId) VALUES (?, ?, ?, ?, ?)");
    $query->execute([$id, $issueDate, $total, $subtotal, $supplierId]);

    // Actualizar el reporte
    $query = $pdo->prepare("UPDATE report SET quotationId = ? WHERE requisitionId = ?");
    $query->execute([$id, $requisitionId]);

    header("Location: index.php");
    exit;
}

// Obtener proveedores
$proveedores = $pdo->query("SELECT id, legalName FROM supplier")->fetchAll(PDO::FETCH_ASSOC);

// Obtener requisiciones
$requisiciones = $pdo->query("SELECT id FROM requisition")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Cotización</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="images/logoCAB.png" type="images/png">
</head>
<body>
    <div class="container mt-5">
        <h1>Agregar Cotización</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="id" class="form-label">ID de Cotización</label>
                <input type="text" class="form-control" id="id" name="id" required>
            </div>
            <div class="mb-3">
                <label for="issueDate" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="issueDate" name="issueDate" required>
            </div>
            <div class="mb-3">
                <label for="total" class="form-label">Total</label>
                <input type="number" step="0.01" class="form-control" id="total" name="total" required>
            </div>
            <div class="mb-3">
                <label for="subtotal" class="form-label">Subtotal</label>
                <input type="number" step="0.01" class="form-control" id="subtotal" name="subtotal" required>
            </div>
            <div class="mb-3">
                <label for="supplierId" class="form-label">Proveedor</label>
                <select class="form-select" id="supplierId" name="supplierId" required>
                    <?php foreach ($proveedores as $proveedor): ?>
                        <option value="<?php echo $proveedor['id']; ?>"><?php echo $proveedor['legalName']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="requisitionId" class="form-label">Requisición</label>
                <select class="form-select" id="requisitionId" name="requisitionId" required>
                    <?php foreach ($requisiciones as $requisicion): ?>
                        <option value="<?php echo $requisicion['id']; ?>"><?php echo $requisicion['id']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
        <a href="index.php" class="btn btn-secondary mt-3">Cancelar</a>
    </div>
</body>
</html>
