let map;
let markers = {};
let focusMarker = "";
let detailMarker = "";
let dataActual ={};
let codeTr="";
let infoWindows = {};
let tableLista;
let idClient = 45987;
let listDataAsset={};
let devices="";

const detailAssset = document.getElementById("detalleEquipo");

function initMap() {
    const ubicacion = { lat: 20.5005701, lng: -99.8540223 }; 

    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 8,
        center: ubicacion,
        gestureHandling: 'auto',
        disableAutoPan: true
    });

    map.addListener('zoom_changed', function() {
        var currentZoom = map.getZoom();
        if (currentZoom <= 11) {
            deleteFocus();
        }
    });
    initPoint()
}

function initPoint(){
    count=0;
    getList(1)
       var intervalo = setInterval(function() {
           if(count>5){
                getList(0)
                count=0;
           }
        getData(0)
        count++;
    }, 5000);
}

function getData(initData) {
    const datos = {
        idCliente: idClient,
        activo:0,
        devices:devices,
        metodo:"getData"
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
            let activos=data.activos;
            let deleteRow=0;
            for (let activo in activos) {
                if(listDataAsset[activo]){
                    if (activos.hasOwnProperty(activo)) {
                        if(initData==1){
                            dataActual[activo]=activos[activo];
                            addMarkers(dataActual[activo]);
                            createListData(dataActual[activo]);
                        }else{
                            /// debe verificar si hay nuevo datos o si se quitaron algunos, 
                            /// si hay nuevos se agregan a la lista.
                            if(dataActual[activo] != undefined && dataActual[activo] != ""){
                                dataActual[activo]=activos[activo];
                                movMarker(dataActual[activo]);
                            }else{
                                console.log("No esta agregado aun el activo a la lista");
                                dataActual[activo]=activos[activo];
                                addMarkers(dataActual[activo]);
                                createListData(dataActual[activo]);
                                tableLista.destroy();
                                initList();
                            }
                        }
                    }
                }else{
                    console.log("se elimina el activo");
                    deleteRow=1;
                    if(dataActual[activo]){
                        
                        delete dataActual[activo];
                        markers[activo].setMap(null);
                        delete markers[activo];
                        if(focusMarker==activo){
                            deleteFocus();
                        }
                        if(detailMarker==activo){
                            closeModal();
                        }
                        infoWindows[activo].close();
                        infoWindow[activo] = null;
                        delete infoWindows[activo];

                        /// se elimina el fyleSystem
                        deleteFileSystem(activo);

                        //// se reinica lista 
                    }
                }
            }
            if(initData==1){
                initList();
            }

            /// verificamos si se elimino almenos un activo para reinicar la lista 
            if(deleteRow==1){
                codeTr="";
                console.log("Reload lista");
                for (let activo in dataActual) {
                    console.log(activo);
                    createListData(dataActual[activo]);
                }
                tableLista.destroy();
                initList();
            }
        }else{
            console.error('Error:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function getList(initial) {
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
            listDataAsset=[];
            let listAsset=[];
            for (let activo in activos) {
                    listDataAsset[activos[activo].idgps]=activos[activo].idgps;
                    listAsset.push(activos[activo].idgps);
            }
            devices=listAsset.toString();
            if(initial==1){
                getData(initial); 
            }
        }else{
            console.error('Error:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function deleteFileSystem(activo) {
    const datos = {
        idCliente: idClient,
        activo:activo,
        metodo:"deleteFyleSystem"
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
            console.log(data);
        }else{
            console.error('Error:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function createListData(activo){ 
        codeTr+=`<tr id="row-activo-${activo.device}">
        <td>
            <div class="content-info-list">
                <div class="list-name-status">
                    <div class="list-name" onclick="openModalDetail('${activo.device}')" id="asset-name-${activo.device}">
                        ${activo.asset}
                    </div>`;
        if(activo.ignition == 1){
            codeTr+=`<div class="list-status status-on" id="state-${activo.device}">
            Encendido
            </div>`;
        }else{
            codeTr+=`<div class="list-status status-off" id="state-${activo.device}">
            Apagado
            </div>`;
        }

        codeTr+=`</div>
                <div class="list-matricula">
                    <button class="list-matricula-button"  onclick="openModalDetail('${activo.device}')" id="plate-${activo.device}"> ${activo.plate}</button>
                </div>
            </div>
        </td>

        <td>
            <div class="content-fecha" id="contend-fecha-hora-${activo.device}">
                <div class="fecha text-info-list">
                ${activo.date}
                </div>
                <div class="hora  text-info-list">
                ${activo.time}
                </div>
            </div>
        </td>
        <td>
            <div class="content-ubication">
                <div class="contend-coords">
                    <div class="lat text-info-list">Lat:</div>
                    <div class="text-info-list" id="lat-${activo.device}">${activo.latitude}</div>
                </div>
                <div class="contend-coords">
                    <div class="lat text-info-list">Lng:</div>
                    <div class="text-info-list" id="lng-${activo.device}">${activo.longitude}</div>
                </div>
            </div>
        </td>
        <td>
            <div class="conted-icon-list">
                <div class="icon-list oculto" id="activoHide-${activo.device}">
                    <i class="fa-solid fa-eye-slash marker-red"  onclick="hideActivo('${activo.device}')"></i>
                </div> 
                <div class="icon-list" id="activoShow-${activo.device}">
                    <i class="fa-solid fa-eye"  onclick="showActivo('${activo.device}')"></i>
                </div>
                <div class="icon-list">
                    <i class="fa-solid fa-location-dot focus-icon" id="activoFocus-${activo.device}" onclick="focusMap('${activo.device}')" ></i>
                </div>
               
            </div>
        </td>
    </tr>`;
}

function initList(){
      document.getElementById('bodylistActivos').innerHTML = codeTr;
      tableLista = $('#listadoActivos').DataTable({
        pageLength: -1,
        paging: false,
        lengthChange: false,
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

function addMarkers(activo){
    let ubicacion =  { lat: parseFloat(activo.latitude), lng: parseFloat(activo.longitude)}; 
    let iconCurse=getIconCurse(activo.course)
    markers[activo.device] = new google.maps.Marker({
        position: ubicacion,
        map: map,
        title: activo.asset,
        icon: {
            url: iconCurse, 
            scaledSize: new google.maps.Size(70, 70),
            rotation: activo.course
        },
        animation: null
    });
    var infoWindow = new google.maps.InfoWindow({
        content: '<div class="etiqueta-map">'+activo.plate+'</div>',
        disableAutoPan: true 
    });
    markers[activo.device].addListener('click', function() {
        openModalDetail(activo.device);
    });
    infoWindow.open(map, markers[activo.device]);
    infoWindows[activo.device] = infoWindow;
}

function cambiarParametro(id) {
    idDevice='activoFocus-'+id;
    document.getElementById(idDevice).setAttribute('onclick', "deleteFocus()");
}

function focusMap(id,updateZoom=true){
    let marker = dataActual[id];
    var punto = { lat: parseFloat(marker.latitude), lng: parseFloat(marker.longitude) };
    map.setCenter(punto);
    if(updateZoom){
        $('#activoFocus-'+id).addClass('marker-red');
        cambiarParametro(id);
        map.setZoom(15);
        $('.focus-icon').each(function() {
            if(this.id != "activoFocus-"+id){
                $(this).removeClass('marker-red'); // Cambiar el color del texto a azul
                let splitId=this.id.split('-');
                document.getElementById(this.id).setAttribute('onclick', "focusMap('"+splitId[1]+"')");
            }
        });
    }
    focusMarker=id;
}

function showActivo(id){
    markers[id].setMap(null);
    $('#activoHide-'+id).removeClass('oculto');
    $('#activoShow-'+id).addClass('oculto');

}

function hideActivo(id){
    markers[id].setMap(map);
    $('#activoShow-'+id).removeClass('oculto');
    $('#activoHide-'+id).addClass('oculto');
    var lat = parseFloat($('#lat-'+id).text());
    var lng = parseFloat($('#lng-'+id).text());
    var punto = { lat: lat, lng: lng };
    // map.setCenter(punto);
    // map.setZoom(8);
}

function movMarker(activo) {
    var newCoordinates = { lat:  parseFloat(activo.latitude), lng:  parseFloat(activo.longitude) };
    markers[activo.device].setPosition(newCoordinates);
    updateNewPointMap(activo)
    updateIconRoration(activo.device,activo.course)
    if(focusMarker!=""){
        focusMap(focusMarker,false);
    }
}

function updateNewPointMap(activo){
    $('#lat-'+activo.device).html(activo.latitude);
    $('#lng-'+activo.device).html(activo.longitude);
    $('#contend-fecha-hora-'+activo.device+' .fecha').html(activo.date);
    $('#contend-fecha-hora-'+activo.device+' .hora').html(activo.time);
    let placaActual=$('#plate-'+activo.device).text();
    /// verificamos si la matricula cambio
    if(placaActual != activo.plate){
        changeInfoWindow(activo.device,activo.plate);
    }

    let assetActual=$('#asset-name-'+activo.device).text();
    /// verificamos si la matricula cambio
    if(assetActual != activo.asset){
        chageAsset(activo.device,activo.asset);
    }
    if ($('#state-'+activo.device).hasClass('status-on') && activo.ignition != 1) {
        // se cambia a Off
        chageState(activo.device,"Apagado","status-off")
    }
    if ($('#state-'+activo.device).hasClass('status-off') && activo.ignition != 0) {
        // se cambia a On
        chageState(activo.device,"Encendido","status-on")
    }
    //// se actualiza el detalle si esta abierto
    if ($("#detalleEquipo").css("display") === "block" && detailMarker==activo.device) {
        loadDataDetail(activo.device)
    }
}

function changeInfoWindow(device,plate){
    const newContent = '<p class="etiqueta-map">'+plate+'</p>';
    const infoWindow = infoWindows[device];
        if (infoWindow) {
            infoWindow.setContent(newContent);
        }
        $('#plate-'+device).html(plate);
    
}
function chageState(device,state,classtoAdd){
    if(classtoAdd=="status-off"){
        $('#state-'+device).removeClass("status-on");    
        $('#state-'+device).addClass("status-off");
    }else{
        $('#state-'+device).addClass("status-on");    
        $('#state-'+device).removeClass("status-off");
    }
    $('#state-'+device).html(state);
}

function chageAsset(device,asset){
    $('#asset-name-'+device).html(asset);
}
function deleteFocus(){
    if(focusMarker!=""){
        idDevice='activoFocus-'+focusMarker;
        document.getElementById(idDevice).setAttribute('onclick', "focusMap('"+focusMarker+"')");
        $('#activoFocus-'+focusMarker).removeClass('marker-red');
        focusMarker="";
    }
}

function openModalDetail(device){
    document.getElementById('detalleEquipo').style.display = 'block';
    detailMarker=device;
    loadDataDetail(device);
    focusMapDetail(device);

}

function closeModal() {
    document.getElementById('detalleEquipo').style.display = 'none';
    detailMarker="";
}

function loadDataDetail(device){
   
    $("#modalLabelheader").html(dataActual[device].asset+" ("+dataActual[device].device+")");
    
    $("#detail-name").html(dataActual[device].asset);
    $("#detail-plate").html(dataActual[device].plate);
    $("#detail-device").html(dataActual[device].device);

    let status=`<div class="status-on">Encendido</div>`;
    if(dataActual[device].ignition!=1){
        status=`<div class="status-on">Apagado</div>`;
    }
    $("#detail-state").html(status);
    $("#detail-last-date").html(dataActual[device].date+" "+dataActual[device].time);
    $("#detail-last-point").html(dataActual[device].latitude+" "+dataActual[device].longitude);
    $("#detail-address").html(dataActual[device].address);
    $("#detail-odometer").html(dataActual[device].odometer);
    $("#detail-client").html(dataActual[device].client);
}


function focusMapDetail(id){
    let marker = dataActual[id];
    var punto = { lat: parseFloat(marker.latitude), lng: parseFloat(marker.longitude) };
    map.setCenter(punto);
    map.setZoom(9);
}

function updateIconRoration(id,course){
    let marker = markers[id];
    let iconCurse=getIconCurse(course)
    var icon = {
        url: iconCurse,
        scaledSize: new google.maps.Size(70, 70),
        rotation: course
    };
    markers[id].setIcon(icon);
}


function getIconCurse(course){
    if((course >= 0 && course <= 10) || (course >= 350 && course <= 360)){
        return  "views/images/activos/activo_azul_der_0.png";
    }
    if(course >= 11 && course <= 25){
        return  "views/images/activos/activo_azul_der_25.png";
    }
    if(course >= 26 && course <= 55){
        return  "views/images/activos/activo_azul_der_45.png";
    }
    if(course >= 56 && course <= 79){
        return  "views/images/activos/activo_azul_der_75.png";
    }

    if((course >= 80 && course <= 90) || (course >= 91 && course <= 100)){
        return  "views/images/activos/activo_generico.png";
    }
    if(course >= 101 && course <= 125){
        return  "views/images/activos/activo_azul_izq_75.png";
    }
    if(course >= 126 && course <= 150){
        return  "views/images/activos/activo_azul_izq_45.png";
    }
    if(course >= 151 && course <= 169){
        return  "views/images/activos/activo_azul_izq_25.png";
    }

    if((course >= 170 && course <= 180) || (course >= 181 && course <= 190)){
        return  "views/images/activos/activo_azul_izq_0.png";
    }
    if(course >= 191 && course <= 205){
        return  "views/images/activos/activo_azul_bajo_izq_25.png";
    }
    if(course >= 206 && course <= 245){
        return  "views/images/activos/activo_azul_bajo_izq_45.png";
    }
    if(course >= 246  && course <= 259){
        return  "views/images/activos/activo_azul_bajo_izq_75.png";
    }

    if((course >= 260 && course <= 270) || (course >= 271 && course <= 280)){
        return  "views/images/activos/activo_azul_bajo_270.png";
    }
    if(course >= 281 && course <= 296){
        return  "views/images/activos/activo_azul_bajo_der_75.png";
    }
    if(course >= 297 && course <= 325){
        return  "views/images/activos/activo_azul_bajo_der_45.png";
    }
    if(course >= 326 && course <= 349){
        return  "views/images/activos/activo_azul_bajo_der_25.png";
    }
}

let offsetX = 0, offsetY = 0, isDragging = false;
detailAssset.addEventListener("mousedown", function(e) {
    isDragging = true;
    offsetX = e.clientX - detailAssset.offsetLeft;
    offsetY = e.clientY - detailAssset.offsetTop;
});
document.addEventListener("mousemove", function(e) {
    if (isDragging) {
        detailAssset.style.left = (e.clientX - offsetX) + "px";
        detailAssset.style.top = (e.clientY - offsetY) + "px";
    }
});
document.addEventListener("mouseup", function() {
    isDragging = false; 
});