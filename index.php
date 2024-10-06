<?php
    include_once("views/view/components/structureTop.php");
    include_once("views/view/components/modalDetail.php");
    include_once('config/config.php');
    // echo"<pre>";
    // var_dump($_SERVER);
    // die();
?>
<div class="content">
    <div class="content-elements">
        <div class="content-listado">
            <table id="listadoActivos" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th class="header-data header-text">Activos</th>
                    <th class="header-data header-text">Fecha</th>
                    <th class="header-data header-text">Ubicación</th>
                    <th class="header-data header-text actions">Acciones</th>
                </tr>
            </thead>
            <tbody id="bodylistActivos">
            </tbody>
            </table>
        </div>
        <div class="content-mapa">
            <div id="map"></div>
        </div>
    </div>
</div>


<?php
    include_once("views/view/components/jsBottom.php");
?>
<script type="text/javascript">
    let hostData="<?php echo $hostData; ?>";
</script>

<script type="text/javascript" src="views/js/mapa.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $variableTest; ?>&callback=initMap"></script>
<?php
    include_once("views/view/components/structureBottom.php");
?>