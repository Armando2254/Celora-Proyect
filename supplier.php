<?php




session_start();

if (empty($_SESSION["employeeNumber"])) {
    header("Location: login.php");
}





include 'config.php';

// Manejar filtro
$searchQuery = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $searchQuery = "WHERE legalName LIKE :search OR firstName LIKE :search OR id = :exactSearch";
}

// Consulta
$query = "SELECT id, firstName, lastName, secondLastName, legalName FROM supplier $searchQuery";
$stmt = $pdo->prepare($query);

if ($searchQuery) {
    $searchTerm = "%$search%";
    $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':exactSearch', $search, PDO::PARAM_INT);
}

$stmt->execute();
$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Manejar nuevo proveedor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_supplier'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $secondLastName = $_POST['secondLastName'] ?: null;
    $legalName = $_POST['legalName'];

    $addQuery = "INSERT INTO supplier (firstName, lastName, secondLastName, legalName) 
                 VALUES (:firstName, :lastName, :secondLastName, :legalName)";
    $addStmt = $pdo->prepare($addQuery);
    $addStmt->bindParam(':firstName', $firstName);
    $addStmt->bindParam(':lastName', $lastName);
    $addStmt->bindParam(':secondLastName', $secondLastName);
    $addStmt->bindParam(':legalName', $legalName);

    $addStmt->execute();

    // Redireccionar para evitar reenvío de formulario
    header("Location: supplier.php");
    exit;
}
// Manejar edición de proveedor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_supplier'])) {
    $supplierId = $_POST['supplierId'];
    $firstName = $_POST['editFirstName'];
    $lastName = $_POST['editLastName'];
    $secondLastName = $_POST['editSecondLastName'] ?: null;
    $legalName = $_POST['editLegalName'];

    $editQuery = "UPDATE supplier 
                  SET firstName = :firstName, lastName = :lastName, 
                      secondLastName = :secondLastName, legalName = :legalName 
                  WHERE id = :supplierId";
    $editStmt = $pdo->prepare($editQuery);
    $editStmt->bindParam(':firstName', $firstName);
    $editStmt->bindParam(':lastName', $lastName);
    $editStmt->bindParam(':secondLastName', $secondLastName);
    $editStmt->bindParam(':legalName', $legalName);
    $editStmt->bindParam(':supplierId', $supplierId);

    $editStmt->execute();

    // Redireccionar para evitar reenvío de formulario
    header("Location: supplier.php");
    exit;
}
// Manejar eliminación de proveedor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_supplier'])) {
    $supplierId = $_POST['supplierId'];

    $deleteQuery = "DELETE FROM supplier WHERE id = :supplierId";
    $deleteStmt = $pdo->prepare($deleteQuery);
    $deleteStmt->bindParam(':supplierId', $supplierId);

    $deleteStmt->execute();

    // Redireccionar para evitar reenvío de formulario
    header("Location: supplier.php");
    exit;
}

?>








<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier</title>
    

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../images/logoCAB.png" type="images/png">
    <link href="css/estilosInicio.css" rel="stylesheet">

    <style>
        body {
            background-color: #ffffff;
        }

        .navbar{
    background-color: #50C878;
}

        .card {
            
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.05);
        }
    </style>
     <style>
        /* Estilo personalizado para el botón */
        .btn-custom {
            background-color: #50C878;
            color: white; /* Texto blanco para contraste */
        }

        .btn-custom:hover {
            background-color: #45b568; /* Color ligeramente más oscuro al pasar el mouse */
        }

        /* Asegúrate de que el modal se apile sobre otros elementos */
.modal-backdrop {
    z-index: 1040 !important; /* Esto asegura que el fondo del modal tenga un alto z-index */
}

.modal {
    z-index: 1050 !important; /* Esto asegura que el modal tenga el mayor z-index */
}

    </style>
</head>
<body>


<nav class="navbar navbar-expand-lg">
  <div class="container-fluid d-flex align-items-center justify-content-between">
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="logoNoL.png" alt="Bootstrap" width="90" class="me-2 mt-n1 mb-n1"></a>

    <!-- Texto centrado -->
    <ul class="navbar-nav mx-auto">
      <li class="nav-item">
      <span class="navbar-text" style="font-size: 20px;">
    <?php
    echo "Welcome " . $_SESSION["firstName"] . " " . $_SESSION["lastName"];
    ?>
</span>
      </li>
    </ul>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>



    <!-- Botón "Salir" -->
    <form class="d-flex" role="search">
  <a href="controladorCS.php" class="btn btn-outline-danger">Log out</a>
</form>
  </div>
</nav>
<div class="container mt-5">
        <!-- Botón Inicio alineado a la izquierda -->
        <a href="inicio.php" class="btn btn-custom">Home</a>
      
    </div>














<div class="container mt-4">
    <h1 class="mb-4">Supplier</h1>

    <!-- Botón para agregar nuevo proveedor -->
    <div class="mb-4">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSupplierModal">Add Supplier</button>
    </div>

    <!-- Formulario de búsqueda -->
    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by ID or Name" value="<?= isset($search) ? htmlspecialchars($search) : '' ?>">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>
    <table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Last Name</th>
            <th>Second Last Name</th>
            <th>Legal Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($suppliers): ?>
        <?php foreach ($suppliers as $supplier): ?>
            <tr>
                <td><?= htmlspecialchars($supplier['id']) ?></td>
                <td><?= htmlspecialchars($supplier['firstName']) ?></td>
                <td><?= htmlspecialchars($supplier['lastName']) ?></td>
                <td><?= htmlspecialchars($supplier['secondLastName'] ?: '-') ?></td>
                <td><?= htmlspecialchars($supplier['legalName']) ?></td>
                <td>
                    <!-- Botón para editar -->
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" 
                            data-bs-target="#editSupplierModal" 
                            data-id="<?= $supplier['id'] ?>"
                            data-firstname="<?= htmlspecialchars($supplier['firstName']) ?>"
                            data-lastname="<?= htmlspecialchars($supplier['lastName']) ?>"
                            data-secondlastname="<?= htmlspecialchars($supplier['secondLastName'] ?: '') ?>"
                            data-legalname="<?= htmlspecialchars($supplier['legalName']) ?>">
                        ✏️
                    </button>
                    <!-- Botón para eliminar -->
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" 
                            data-bs-target="#deleteSupplierModal" 
                            data-id="<?= $supplier['id'] ?>">
                        ❌
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" class="text-center">No results found</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
</div>

<!-- Modal para agregar proveedor -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" onsubmit="return validateForm()">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSupplierModalLabel">Add Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="firstName" class="form-label">Name</label>
                        <input type="text" name="firstName" id="firstName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" name="lastName" id="lastName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="secondLastName" class="form-label">Second Last Name</label>
                        <input type="text" name="secondLastName" id="secondLastName" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="legalName" class="form-label">Legal Name</label>
                        <input type="text" name="legalName" id="legalName" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_supplier" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal para editar proveedor -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSupplierModalLabel">Edit Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="supplierId" id="editSupplierId">
                    <div class="mb-3">
                        <label for="editFirstName" class="form-label">Name</label>
                        <input type="text" name="editFirstName" id="editFirstName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editLastName" class="form-label">Last Name</label>
                        <input type="text" name="editLastName" id="editLastName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editSecondLastName" class="form-label">Second Last Name</label>
                        <input type="text" name="editSecondLastName" id="editSecondLastName" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="editLegalName" class="form-label">Legal Name</label>
                        <input type="text" name="editLegalName" id="editLegalName" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="edit_supplier" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal para confirmar eliminación -->
<div class="modal fade" id="deleteSupplierModal" tabindex="-1" aria-labelledby="deleteSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSupplierModalLabel">Delete Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="supplierId" id="deleteSupplierId">
                    Do you really want to delete this supplier?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="delete_supplier" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Llenar datos en el modal de edición
    const editSupplierModal = document.getElementById('editSupplierModal');
    editSupplierModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const firstName = button.getAttribute('data-firstname');
        const lastName = button.getAttribute('data-lastname');
        const secondLastName = button.getAttribute('data-secondlastname');
        const legalName = button.getAttribute('data-legalname');

        document.getElementById('editSupplierId').value = id;
        document.getElementById('editFirstName').value = firstName;
        document.getElementById('editLastName').value = lastName;
        document.getElementById('editSecondLastName').value = secondLastName;
        document.getElementById('editLegalName').value = legalName;
    });

    // Llenar datos en el modal de eliminación
    const deleteSupplierModal = document.getElementById('deleteSupplierModal');
    deleteSupplierModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        document.getElementById('deleteSupplierId').value = id;
    });
});
</script>








<footer class="text-center py-3 border-top">
        <small>&copy; 2024 Empresa. Todos los derechos reservados.</small>
    </footer>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body> 
</html>