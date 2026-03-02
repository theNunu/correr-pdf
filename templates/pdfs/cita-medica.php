<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body { font-family: DejaVu Sans; font-size: 12px; }
.contenedor {
    padding:20px;
    border:1px solid #0dcaf0;
    background:#d1ecf1;
}
.titulo {
    text-align:center;
    font-size:16px;
    font-weight:bold;
}
.bloque {
    margin-top:15px;
    padding:15px;
    border:1px solid #0c5460;
    background:#bee5eb;
}
</style>
</head>
<body>

<div class="contenedor">
    <div class="titulo">PERMISO POR CITA MÉDICA</div>

    <p><strong>Nombre:</strong> <?php echo $nombre; ?></p>
    <p><strong>Apellido:</strong> <?php echo $apellido; ?></p>
    <p><strong>Cédula:</strong> <?php echo $cedula; ?></p>

    <div class="bloque">
        El trabajador 
        <strong><?php echo $nombre . ' ' . $apellido; ?></strong> 
        con número de cédula 
        <strong><?php echo $cedula; ?></strong> 
        solicita permiso por cita médica y deberá recuperar las horas correspondientes.
    </div>
</div>

</body>
</html>