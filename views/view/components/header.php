<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="mapa">
        <img src="views/images/pageimages/logo.jpeg" width="135" height="80" alt="logotopo">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a href="/" class="nav-link nav-header 
           <?php 
            if($_SERVER["SCRIPT_NAME"]=="/index.php"){
              echo "active";
            }
          ?>
          " id="nav-0">Mapa</a>
        </li>
        <li class="nav-item">
          <a  href="activos.php" class="nav-link nav-header
            <?php 
            if($_SERVER["SCRIPT_NAME"]=="/activos.php"){
              echo "active";
            }
          ?>
          " id="nav-1">Activos</a>
        </li>
      </ul>
    </div>
  </div>
</nav>