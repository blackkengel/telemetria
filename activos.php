<?php
    include_once("views/view/components/structureTop.php");
    include_once('config/config.php');
?>
<div class="contend">
    <div class="contend-title">
        <h3 class="title-activos">Adminstración de activos</h3>
    </div>
    <hr>
    <div class="content-list-asset">
        <div class="content-grid-asset">
            <table id="listadoActivos" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Activo</th>
                    <th>Matricula</th>
                    <th>No Serie</th>
                    <th>IMEI</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="bodylistActivos">
            </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="detailVehicle" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalLabelheader"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
            <table class="table-detail">
                <tbody  class="body-table-detail">
                    <tr class="tr-grid-detail">
                        <td class="title-grid-detail">Activo:</td>
                        <td class="data-grid-detail" id="detail-name"></td>
                    </tr>
                    <tr class="tr-grid-detail">
                        <td class="title-grid-detail">Matricula:</td>
                        <td class="data-grid-detail" id="detail-plate"></td>
                    </tr>
                    <tr class="tr-grid-detail">
                        <td class="title-grid-detail">Dispositivo:</td>
                        <td class="data-grid-detail" id="detail-device"></td>
                    </tr>
                    <tr class="tr-grid-detail">
                        <td class="title-grid-detail">Numero Serie:</td>
                        <td class="data-grid-detail" id="detail-serie"></td>
                    </tr>
                    <tr class="tr-grid-detail">
                        <td class="title-grid-detail">Marca:</td>
                        <td class="data-grid-detail" id="detail-marca"></td>
                    </tr>
                    <tr class="tr-grid-detail">
                        <td class="title-grid-detail">Modelo:</td>
                        <td class="data-grid-detail" id="detail-modelo"></td>
                    </tr>
                    <tr class="tr-grid-detail">
                        <td class="title-grid-detail">Estatus:</td>
                        <td class="data-grid-detail" id="detail-state"></td>
                    </tr>
                    <tr class="tr-grid-detail">
                        <td class="title-grid-detail">Fecha último reporte:</td>
                        <td class="data-grid-detail" id="detail-last-date"></td>
                    </tr>
                    <tr class="tr-grid-detail">
                        <td class="title-grid-detail">Ultimas coordenadas:</td>
                        <td class="data-grid-detail" id="detail-last-point"></td>
                    </tr>
                    <tr class="tr-grid-detail">
                        <td class="title-grid-detail">Dirección:</td>
                        <td class="data-grid-detail" id="detail-address"></td>

                    </tr>
                    <tr class="tr-grid-detail">
                        <td class="title-grid-detail">Odómetro:</td>
                        <td class="data-grid-detail" id="detail-odometer"></td>
                    </tr>

                    <tr class="tr-grid-detail">
                        <td class="title-grid-detail">Cliente:</td>
                        <td class="data-grid-detail" id="detail-client"></td>
                    </tr>
                </tbody>
            </table>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    let hostData="<?php echo $hostData; ?>";
</script>
<?php
    include_once("views/view/components/jsBottom.php");
?>
<script type="text/javascript" src="views/js/listado.js"></script>
<?php
    include_once("views/view/components/structureBottom.php");
?>