<?php
$page = isset($_GET['page']) ? $_GET['page'] : '';
$contend="";
$serverData= $_SERVER["SERVER_NAME"].$_SERVER["REDIRECT_URL"];
if ($page != 'mapa' && $page != 'activos') {
    $contend="
    <div style='width: 100%;text-align: center;display: flex;justify-content: center;height: 100vh;align-items: center;'>
        <div style='width: 50%;'>
            <h1 style='color:#fff'>¡Lo sentimos!</h1> 
            <h1 style='color:#fff'>Página no encontrada</h1>
            <hr style='color:#fff'>
            <h3 style='color:#fff'>Será redirigido al inicio</h3>
        </div>
    </div>
    ";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body style="background: #1d3154;">
    <?php 
    echo $contend;
    ?>
<script type="text/javascript">
     var inicio = "<?php echo $serverData; ?>";
     setTimeout(function() {
            window.location.href = "mapa";
        }, 3000);
</script>
</body>
</html>