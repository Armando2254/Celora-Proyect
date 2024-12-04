<?php

session_start();

if (empty($_SESSION["employeeNumber"])) {
    header("Location: login.php");
}




require 'conexion_bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'add') {
            $code = $_POST['code'];
            $type = $_POST['type'];
            $query = "INSERT INTO paymentType (code, type) VALUES ('$code', '$type')";
            mysqli_query($conexion, $query);
        } elseif ($action === 'edit') {
            $code = $_POST['code'];
            $type = $_POST['type'];
            $query = "UPDATE paymentType SET type='$type' WHERE code='$code'";
            mysqli_query($conexion, $query);
        } elseif ($action === 'delete') {
            $code = $_POST['code'];
            $query1 = "UPDATE payment SET paymentTypeCode = NULL WHERE paymentTypeCode = '$code'";
            $query2 = "DELETE FROM paymentType WHERE code = '$code'";
            mysqli_query($conexion, $query1);
            mysqli_query($conexion, $query2);
        }
    }
}

$result = mysqli_query($conexion, "SELECT * FROM paymentType");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Título de tu página</title>
    

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   
    <link href="css/estilosInicio.css" rel="stylesheet" >

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

        .jj{
            background-color: #50C878;
            border-color: none;
            border-style: none;
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
        <span class="navbar-text">
        <?php
        echo "Bienvenido " . $_SESSION["firstName"] . " " . $_SESSION["lastName"];
        ?>

        </span>
      </li>
    </ul>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>



    <!-- Botón "Salir" -->
    <form class="d-flex" role="search">
  <a href="controladorCS.php" class="btn btn-outline-danger">Salir</a>
</form>
  </div>
</nav>

<div class="container mt-5">
        <!-- Botón Inicio alineado a la izquierda -->
        <a href="configuracion.php" class="btn btn-custom">Inicio</a>
      
    </div>







<div class="container mt-5">
    <h1 class="text-center">Payment Types</h1>
    <button class="btn btn-success my-3" data-bs-toggle="modal" data-bs-target="#addModal">Add New Payment Type</button>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Code</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= $row['code'] ?></td>
                    <td><?= $row['type'] ?></td>
                    <td>
                        <button class="btn btn-primary btn-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editModal" 
                                data-code="<?= $row['code'] ?>" 
                                data-type="<?= $row['type'] ?>">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal" 
                                data-code="<?= $row['code'] ?>">
                            <i class="bi bi-x"></i>
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Payment Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label for="addCode" class="form-label">Code</label>
                        <input type="text" id="addCode" name="code" class="form-control" maxlength="4" pattern=".{4}" required title="Code must be exactly 4 characters">
                    </div>
                    <div class="mb-3">
                        <label for="addType" class="form-label">Type</label>
                        <input type="text" id="addType" name="type" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Payment Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <div class="mb-3">
                        <label for="editCode" class="form-label">Code</label>
                        <input type="text" id="editCode" name="code" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editType" class="form-label">Type</label>
                        <input type="text" id="editType" name="type" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Payment Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" id="deleteCode" name="code">
                    <p>Are you sure you want to delete this payment type?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget;
        const code = button.getAttribute('data-code');
        const type = button.getAttribute('data-type');
        document.getElementById('editCode').value = code;
        document.getElementById('editType').value = type;
    });

    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget;
        const code = button.getAttribute('data-code');
        document.getElementById('deleteCode').value = code;
    });
</script>

<footer class="text-center py-3 border-top">
        <small>&copy; 2024 Empresa. Todos los derechos reservados.</small>
    </footer>

</body>
</html>
