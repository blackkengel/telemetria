<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Connect Gps</title>
    <link rel="shortcut icon" href="views/images/pageimages/file_favicon.png">
    <link href="https://cdn.datatables.net/1.13.11/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.0.0/mdb.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="views/css/main.css" rel="stylesheet">
    <?php 
        if($_SERVER["SCRIPT_NAME"]=="/index.php"){
            echo '<link href="views/css/mapa.css" rel="stylesheet">
                  <link href="views/css/listado-activos.css" rel="stylesheet">
                ';

        }else{
            echo '<link href="views/css/activos.css" rel="stylesheet">';
            
        }
    ?>
    
</head>
<body>
<?php include_once("header.php");?>
