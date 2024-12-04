<?php


session_start();

if (empty($_SESSION["employeeNumber"])) {
    header("Location: login.php");
}



include 'conexion_bd.php'; // Incluye la conexión a la base de datos ($conexion)

// Eliminar departamento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_code'])) {
    $delete_code = $_POST['delete_code'];
    if ($delete_code !== 'COMPR') {
        // Actualizar los empleados a NULL en caso de eliminar un departamento
        $query = "UPDATE employee SET departmentCode = NULL WHERE departmentCode = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("s", $delete_code);
        $stmt->execute();

        // Eliminar el departamento
        $query = "DELETE FROM department WHERE code = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("s", $delete_code);
        $stmt->execute();
    } else {
        echo "<script>alert('No se puede eliminar el departamento COMPR.');</script>";
    }
}

// Agregar o actualizar departamento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['code'], $_POST['name'])) {
    $code = $_POST['code'];
    $name = $_POST['name'];

    if ($code === 'COMPR') {
        echo "<script>alert('No se puede modificar el departamento COMPR.');</script>";
    } elseif (strlen($code) > 5) {
        echo "<script>alert('El código debe tener un máximo de 5 caracteres.');</script>";
    } else {
        $query = "REPLACE INTO department (code, name) VALUES (?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ss", $code, $name);
        $stmt->execute();
    }
}

// Obtener departamentos
$query = "SELECT * FROM department";
$result = $conexion->query($query);
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





<div class="container mt-4">
    <h1 class="text-center">Gestión de Departamentos</h1>
    <button class="btn btn-success my-3" data-bs-toggle="modal" data-bs-target="#addModal">Agregar Departamento</button>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['code']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td>
                    <?php if ($row['code'] !== 'COMPR'): ?>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editModal" data-code="<?= htmlspecialchars($row['code']) ?>"
                                data-name="<?= htmlspecialchars($row['name']) ?>">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form action="" method="POST" style="display:inline-block;">
                            <input type="hidden" name="delete_code" value="<?= htmlspecialchars($row['code']) ?>">
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este departamento?');">
                                <i class="bi bi-x"></i>
                            </button>
                        </form>
                    <?php else: ?>
                        <span class="text-muted">Acción no permitida</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal Agregar -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Agregar Departamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="code" class="form-label">Code</label>
                    <input type="text" name="code" id="code" class="form-control" maxlength="5" required>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Agregar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Departamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit_code" class="form-label">Code</label>
                    <input type="text" name="code" id="edit_code" class="form-control" maxlength="5" readonly>
                </div>
                <div class="mb-3">
                    <label for="edit_name" class="form-label">Name</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const code = button.getAttribute('data-code');
        const name = button.getAttribute('data-name');

        document.getElementById('edit_code').value = code;
        document.getElementById('edit_name').value = name;
    });
</script>
<footer class="text-center py-3 border-top">
        <small>&copy; 2024 Empresa. Todos los derechos reservados.</small>
    </footer>

</body>
</html>
