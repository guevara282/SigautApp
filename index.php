<?php
require_once './lib/auth_roles.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <title>SigautApp</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
  
  <!-- Estilos principales -->
  <link rel="stylesheet" href="assets/css/main.css" />
  <link rel="stylesheet" href="./public/css/checbox.css">
  <link rel="stylesheet" href="./public/css/input.css">
  <link rel="stylesheet" href="./public/css/button.css">
  <link rel="stylesheet" href="./public/css/select.css">

</head>

<body class="is-preload">
  <!-- Wrapper -->
  <div id="wrapper">
    <!-- Main -->
    <div id="main">
      <div class="inner">
        <!-- Header -->
        <header id="header">
          <a class="logo"><strong>SigautApp</strong></a>
        </header>
        
        <!-- Campo oculto para el ID del usuario -->
        <input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>">
        
        <!-- Banner -->
        <section id="banner">
          <div class="content" id="dynamicContent">
            <!-- Aquí se cargará el contenido dinámico -->
             
          </div>
        </section>
      </div>
    </div>

    <!-- Sidebar -->
    <div id="sidebar">
      <div class="inner">
        <!-- Menú -->
        <nav id="menu">
          <header class="major">
            <h2>Menú</h2>
          </header>
          <ul>
            <?php include('./views/layouts/menu.php'); ?>
          </ul>
        </nav>

        <!-- Footer -->
        <footer id="footer">
          <p class="copyright">
            &copy; Untitled. Todos los derechos reservados. Imágenes de demostración:
            <a href="https://unsplash.com">Unsplash</a>. Diseño:
            <a href="https://html5up.net">HTML5 UP</a>.
          </p>
        </footer>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/browser.min.js"></script>
  <script src="assets/js/breakpoints.min.js"></script>
  <script src="assets/js/util.js"></script>
  <script src="assets/js/main.js"></script>

  
  <!-- Scripts adicionales -->
  <script>
    $(document).ready(function() {
      // Capturar el clic en el enlace de agregar pregunta
      $('a[href="./views/form/addquestion.php"]').click(function(event) {
        event.preventDefault();
        $("#dynamicContent").load("./views/form/addquestion.php");
      });

      // Capturar el clic en el enlace de ver todas las preguntas
      $('a[href="./views/layouts/allquestion.php"]').click(function(event) {
        event.preventDefault();
        $("#dynamicContent").load("./views/layouts/allquestion.php");
      });
    });
  </script>
  
  <!-- Scripts adicionales para validaciones y otros procesos -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="./public/js/get_semesters.js"></script>
   <script src="./public/js/alerts.js"></script>
  </body>

</html>
