<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CRUD PHP</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
  <style type="text/css">
  .wrapper{
    width: 650px;
    margin: 0 auto;
  }
  .page-header h2{
    margin-top: 0;
  }
  table tr td:last-child a{
    margin-right: 15px;
  }
  </style>
  <script type="text/javascript">
  $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
  });
  </script>
</head>
<body>
  <div class="wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="page-header clearfix">
            <h2 class="pull-left">Detalle de Alumnos</h2>
            <a href="create.php" class="btn btn-success pull-right">Agregar Nuevo Alumno</a>
          </div>
          <?php
          // Include config file
          require_once "config.php";

          // Attempt select query execution
          $sql = "SELECT * FROM alumno";
          if($result = mysqli_query($link, $sql)){
            if(mysqli_num_rows($result) > 0){
              echo "<table class='table table-bordered table-striped'>";
              echo "<thead>";
              echo "<tr>";
              echo "<th>#</th>";
              echo "<th>Nombre</th>";
              echo "<th>Ciudad</th>";
              echo "<th>Acciones</th>";
              echo "</tr>";
              echo "</thead>";
              echo "<tbody>";
              while($row = mysqli_fetch_array($result)){
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['nombre'] . "</td>";
                echo "<td>" . $row['ciudad'] . "</td>";
                echo "<td>";
                echo "<a href='read.php?id=". $row['id'] ."' title='Ver Registro' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                echo "<a href='update.php?id=". $row['id'] ."' title='Actualizar Registro' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                echo "<a href='delete.php?id=". $row['id'] ."' title='Eliminar Registro' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                echo "</td>";
                echo "</tr>";
              }
              echo "</tbody>";
              echo "</table>";
              // Free result set
              mysqli_free_result($result);
            } else{
              echo "<p class='lead'><em>No records were found.</em></p>";
            }
          } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
          }

          // Close connection
          mysqli_close($link);
          ?>

          <p>Ingrese información en los campos para filtrar</p>
          <form action="filter.php" method="post">
            <div class="form-group">
              <label>Nombre</label>
              <input type="text" name="nombre" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group">
              <label>Seleccionar</label>
              <select class="form-control" name="orden">
                <option value="1">Ascendente</option>
                <option value="2">Descendente</option>
              </select>
            </div>
            <input type="submit" class="btn btn-primary" value="Submit">
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
