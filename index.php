<?php
require_once './lib/auth_roles.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Editorial by HTML5 UP</title>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, user-scalable=no"
    />
    <link rel="stylesheet" href="assets/css/main.css" />
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

          <!-- Banner -->
          <section id="banner">
            <div class="content" id="dynamicContent">
              <!-- Añadir un ID para el contenido dinámico -->
            </div>
          </section>
        </div>
      </div>

      <!-- Sidebar -->
      <div id="sidebar">
        <div class="inner">
          <!-- Menu -->
          <nav id="menu">
            <header class="major">
              <h2>Menu</h2>
            </header>
            
            <ul>
              <li>
                <a href="./views/form/addquestion.php">Agregar Pregunta</a>
              </li>
              <li>
                <a href="./views/layouts/allquestion.php">Ver Preguntas</a>
              </li>
              <li>
                <span class="opener">Submenu</span>
                <ul>
                  <li><a href="#">Lorem Dolor</a></li>
                  <li><a href="#">Ipsum Adipiscing</a></li>
                  <li><a href="#">Tempus Magna</a></li>
                  <li><a href="#">Feugiat Veroeros</a></li>
                </ul>
              </li>
            </ul>
          </nav>

          <!-- Footer -->
          <footer id="footer">
            <p class="copyright">
              &copy; Untitled. All rights reserved. Demo Images:
              <a href="https://unsplash.com">Unsplash</a>. Design:
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
    <script>
      $(document).ready(function () {
        // Capturar el clic en el enlace de agregar pregunta
        $('a[href="./views/form/addquestion.php"]').click(function (event) {
          event.preventDefault(); // Prevenir el comportamiento por defecto del enlace

          // Usar AJAX para cargar el contenido de la vista en #dynamicContent
          $("#dynamicContent").load("./views/form/addquestion.php");
        });
        $('a[href="./views/layouts/allquestion.php"]').click(function (event) {
          event.preventDefault(); // Prevenir el comportamiento por defecto del enlace

          // Usar AJAX para cargar el contenido de la vista en #dynamicContent
          $("#dynamicContent").load("./views/layouts/allquestion.php");
        });
      });
    </script>

  </body>
</html>
