<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$nombre = $ciudad = $telefono = "";
$nombre_err = $ciudad_err = $telefono_err = "";

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
  // Get hidden input value
  $id = $_POST["id"];

  // Validar nombre
  $input_nombre = trim($_POST["nombre"]);
  if(empty($input_nombre)){
    $nombre_err = "Ingrese nombre.";
  } elseif(!filter_var($input_nombre, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
    $nombre_err = "Ingrese un nombre valido.";
  } else{
    $nombre = $input_nombre;
  }

  // Validar ciudad
  $input_ciudad = trim($_POST["ciudad"]);
  if(empty($input_ciudad)){
    $ciudad_err = "Ingrese ciudad.";
  } else{
    $ciudad = $input_ciudad;
  }

  // Validar telefono
  $input_telefono = trim($_POST["telefono"]);
  if(empty($input_telefono)){
    $telefono_err = "Ingrese telefono.";
  } elseif(!ctype_digit($input_telefono)){
    $telefono_err = "Ingrese telefono valido.";
  } else{
    $telefono = $input_telefono;
  }

  // Check input errors before inserting in database
  if(empty($nombre_err) && empty($ciudad_err) && empty($telefono_err)){
    // Prepare an update statement
    $sql = "UPDATE alumno SET nombre=?, ciudad=?, telefono=? WHERE id=?";

    if($stmt = mysqli_prepare($link, $sql)){
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "sssi", $param_nombre, $param_ciudad, $param_telefono, $param_id);

      // Set parameters
      $param_nombre = $nombre;
      $param_ciudad = $ciudad;
      $param_telefono = $telefono;;
      $param_id = $id;

      // Attempt to execute the prepared statement
      if(mysqli_stmt_execute($stmt)){
        // Records updated successfully. Redirect to landing page
        header("location: index.php");
        exit();
      } else{
        echo "Something went wrong. Please try again later.";
      }
    }

    // Close statement
    mysqli_stmt_close($stmt);
  }

  // Close connection
  mysqli_close($link);
} else{
  // Check existence of id parameter before processing further
  if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Get URL parameter
    $id =  trim($_GET["id"]);

    // Prepare a select statement
    $sql = "SELECT * FROM alumno WHERE id = ?";
    if($stmt = mysqli_prepare($link, $sql)){
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "i", $param_id);

      // Set parameters
      $param_id = $id;

      // Attempt to execute the prepared statement
      if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) == 1){
          /* Fetch result row as an associative array. Since the result set
          contains only one row, we don't need to use while loop */
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

          // Retrieve individual field value
          $nombre = $row["nombre"];
          $ciudad = $row["ciudad"];
          $telefono = $row["telefono"];
        } else{
          // URL doesn't contain valid id. Redirect to error page
          header("location: error.php");
          exit();
        }

      } else{
        echo "Oops! Something went wrong. Please try again later.";
      }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
  }  else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Record</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
  <style type="text/css">
  .wrapper{
    width: 500px;
    margin: 0 auto;
  }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="page-header">
            <h2>Update Record</h2>
          </div>
          <p>Please edit the input values and submit to update the record.</p>
          <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
            <div class="form-group <?php echo (!empty($nombre_err)) ? 'has-error' : ''; ?>">
              <label>Nombre</label>
              <input type="text" name="nombre" class="form-control" value="<?php echo $nombre; ?>">
              <span class="help-block"><?php echo $nombre_err;?></span>
            </div>
            <div class="form-group <?php echo (!empty($ciudad_err)) ? 'has-error' : ''; ?>">
              <label>Ciudad</label>
              <textarea name="ciudad" class="form-control"><?php echo $ciudad; ?></textarea>
              <span class="help-block"><?php echo $ciudad_err;?></span>
            </div>
            <div class="form-group <?php echo (!empty($telefono_err)) ? 'has-error' : ''; ?>">
              <label>Telefono</label>
              <input type="text" name="telefono" class="form-control" value="<?php echo $telefono; ?>">
              <span class="help-block"><?php echo $telefono_err;?></span>
            </div>
            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="index.php" class="btn btn-default">Cancelar</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
