let codeTr="";
let idClient=45987;
let dataAsset={};
getList();
function initList(){
    document.getElementById('bodylistActivos').innerHTML = codeTr;
    tableLista = $('#listadoActivos').DataTable({
      language: {
          "decimal": "",
          "emptyTable": "No hay información",
          "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
          "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
          "infoFiltered": "(Filtrado de _MAX_ total entradas)",
          "infoPostFix": "",
          "thousands": ",",
          "lengthMenu": "Mostrar _MENU_ Entradas",
          "loadingRecords": "Cargando...",
          "processing": "Procesando...",
          "search": "Buscar:",
          "zeroRecords": "Sin resultados encontrados",
          "paginate": {
              "first": "Primero",
              "last": "Ultimo",
              "next": "Siguiente",
              "previous": "Anterior"
          }
      },
  });
}

function getList() {
    const datos = {
        idCliente: idClient,
    };
    fetch(hostData+'api/getListApi.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if(data.success){
            let activos=data.activos;
            for (let activo in activos) {
                createListData(activos[activo]);
                dataAsset[activos[activo].idgps]=activos[activo];

            }
            initList();
        }else{
            console.error('Error:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function createListData(activo){ 
    codeTr+=`<tr id="row-activo-${activo.id}">
        <td>
            <div class="content-info-list">
                ${activo.nombre}
            </div>
        </td>
        <td>
            <div class="content-info-list">
                ${activo.patente}
            </div>
        </td>
        <td>
            <div class="content-info-list">
                ${activo.numero_serie}
            </div>
        </td>
        <td>
            <div class="content-info-list">
                ${activo.idgps}
            </div>
        </td>
        <td>
            <div class="content-info-list">
                ${activo.marca}
            </div>
        </td>
        <td>
            <div class="content-info-list">
                ${activo.modelo}
            </div>
        </td>
       
       
        <td>
            <div class="conted-icon-list">
                <div class="icon-list">
                    <i class="fa fa-info-circle" onclick="detailVehicle('${activo.idgps}')" ></i>
                </div>
            </div>
        </td>
    </tr>`;
}


function detailVehicle(activo){
    var modal = new bootstrap.Modal(document.getElementById('detailVehicle'));
    modal.show();
    $("#modalLabelheader").html(dataAsset[activo].nombre+" ("+dataAsset[activo].idgps+")");
    
    $("#detail-name").html(dataAsset[activo].nombre);
    $("#detail-plate").html(dataAsset[activo].patente);
    $("#detail-device").html(dataAsset[activo].idgps);
    $("#detail-serie").html(dataAsset[activo].numero_serie);
    $("#detail-marca").html(dataAsset[activo].marca);
    $("#detail-modelo").html(dataAsset[activo].modelo);

    const datos = {
        idCliente: idClient,
        activo:activo,
        metodo:"getDetailVehicle"
    };
    fetch(hostData+'api/getData.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if(data.success){
         let status=`<div class="status-on">Encendido</div>`;
         if(data.activo.ignition!=1){
             status=`<div class="status-on">Apagado</div>`;
         }
         $("#detail-state").html(status);
         $("#detail-last-date").html(data.activo.date+" "+data.activo.time);
         $("#detail-last-point").html(data.activo.latitude+" "+data.activo.longitude);
         $("#detail-address").html(data.activo.address);
         $("#detail-odometer").html(data.activo.odometer);
         $("#detail-client").html(data.activo.client);
        }else{
            console.error('Error:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });


}