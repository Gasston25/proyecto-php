<?php
// Conexión a la base de datos.
include('conexion.php');

// Agregar un nuevo producto
if (isset($_POST['agregar'])) {
    $nombre_producto = $_POST['nombre_producto'];
    $stock_producto = $_POST['stock_producto'];
    $precio_producto = $_POST['precio_producto'];
    $marca_producto = $_POST['marca_producto'];

    if (!empty($nombre_producto) && !empty($stock_producto) && !empty($precio_producto) && !empty($marca_producto)) {
        $sql = "INSERT INTO productos (nombre_producto, stock_producto, precio_producto, marca_producto) 
                VALUES ('$nombre_producto', $stock_producto, $precio_producto, '$marca_producto')";
        
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Nuevo producto agregado exitosamente');</script>";
        } else {
            echo "<script>alert('Error al agregar producto: " . $conn->error . "');</script>";
        }
    }
}

// Eliminar un producto
if (isset($_GET['eliminar'])) {
    $id_producto = $_GET['eliminar'];
    $sql = "DELETE FROM productos WHERE id_producto = $id_producto";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Producto eliminado exitosamente');</script>";
    } else {
        echo "<script>alert('Error al eliminar el producto: " . $conn->error . "');</script>";
    }
}

// Editar un producto.
if (isset($_POST['editar'])) {
    $id_producto = $_POST['id_producto'];
    $nombre_producto = $_POST['nombre_producto'];
    $stock_producto = $_POST['stock_producto'];
    $precio_producto = $_POST['precio_producto'];
    $marca_producto = $_POST['marca_producto'];

    $sql = "UPDATE productos SET nombre_producto = '$nombre_producto', stock_producto = $stock_producto, 
            precio_producto = $precio_producto, marca_producto = '$marca_producto' WHERE id_producto = $id_producto";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Producto actualizado exitosamente');</script>";
    } else {
        echo "<script>alert('Error al actualizar producto: " . $conn->error . "');</script>";
    }
}

// Obtener todos los productos.
$sql = "SELECT * FROM productos";
$result = $conn->query($sql);

// Edición de un producto.
$productToEdit = null;
if (isset($_GET['editar'])) {
    $id_producto = $_GET['editar'];
    $sqlEdit = "SELECT * FROM productos WHERE id_producto = $id_producto";
    $resultEdit = $conn->query($sqlEdit);
    if ($resultEdit->num_rows > 0) {
        $productToEdit = $resultEdit->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Gestionar Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styleditor.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="index.html">Gestión de Productos</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Extras</div>
                        <a class="nav-link" href="index.html">
                            <div class="sb-nav-link-icon"><i class="alert-success"></i></div>
                            Volver
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Gestionar Productos</h1>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Productos
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <label for="nombre_producto">Nombre del Producto:</label>
                                <input type="text" name="nombre_producto" id="nombre_producto" value="<?php echo isset($productToEdit) ? $productToEdit['nombre_producto'] : ''; ?>" required>
                                <br>
                                <label for="stock_producto">Stock:</label>
                                <input type="number" name="stock_producto" id="stock_producto" value="<?php echo isset($productToEdit) ? $productToEdit['stock_producto'] : ''; ?>" required>
                                <br>
                                <label for="precio_producto">Precio:</label>
                                <input type="number" step="0.01" name="precio_producto" id="precio_producto" value="<?php echo isset($productToEdit) ? $productToEdit['precio_producto'] : ''; ?>" required>
                                <br>
                                <label for="marca_producto">Marca:</label>
                                <input type="text" name="marca_producto" id="marca_producto" value="<?php echo isset($productToEdit) ? $productToEdit['marca_producto'] : ''; ?>" required>
                                <br>
                                <?php if ($productToEdit) { ?>
                                    <input type="hidden" name="id_producto" value="<?php echo $productToEdit['id_producto']; ?>">
                                    <button type="submit" name="editar">Actualizar Producto - Agregar Producto</button>
                                <?php } else { ?>
                                    <button type="submit" name="agregar">Agregar Producto</button>
                                <?php } ?>
                            </form>
                            <h2>Lista de Productos</h2>
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Stock</th>
                                        <th>Precio</th>
                                        <th>Marca</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row['id_producto'] . "</td>";
                                            echo "<td>" . $row['nombre_producto'] . "</td>";
                                            echo "<td>" . $row['stock_producto'] . "</td>";
                                            echo "<td>" . $row['precio_producto'] . "</td>";
                                            echo "<td>" . $row['marca_producto'] . "</td>";
                                            echo "<td>
                                                    <a href='?editar=" . $row['id_producto'] . "'>Editar</a> | 
                                                    <a href='?eliminar=" . $row['id_producto'] . "'>Eliminar</a>
                                                  </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>No hay productos disponibles.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
	<script src="../js/scripts.js"></script>
</body>
</html>