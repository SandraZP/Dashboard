<?php
include'conexion.php';
// Fechas de inicio y fin del mes actual
$inicio = '2024-01-01';
$fin = '2024-12-31';

// Consulta SQL
$sql = "
    DECLARE @Inicio DATETIME = ?;
    DECLARE @Fin DATETIME = ?;

    SELECT 
        COUNT(1) AS totalVentas
    FROM ser_vehiculo
    INNER JOIN ade_vtafi ON veh_numserie = vte_serie
    INNER JOIN per_personas a ON vte_idcliente = a.per_idpersona
    INNER JOIN uni_ltpedido ON upe_tipodocto = vte_tipodocto 
                            AND vte_docto = upe_docto
    INNER JOIN per_personas b ON upe_idagte = CAST(b.per_idpersona AS VARCHAR(10))
    INNER JOIN uni_catalogo ON veh_catalogo = unc_idcatalogo 
                            AND veh_anmodelo = unc_modelo
    INNER JOIN uni_ltpediuni ON pen_tipodocto = vte_tipodocto 
                              AND veh_numserie = pen_numserie 
                              AND CAST(vte_referencia1 AS NUMERIC) = pen_idpedi 
                              AND vte_docto = pen_docto
    LEFT JOIN pnc_parametr c ON unc_familia = c.par_idenpara 
                              AND c.par_tipopara = 'cli'
    INNER JOIN pnc_parametr d ON pen_venta = d.par_idenpara 
                              AND d.par_tipopara = 'vnt'
    LEFT JOIN pnc_parametr com ON com.PAR_TIPOPARA = 'TIPOCOMPRA' 
                                AND com.PAR_IDENPARA = VEH_TIPOCOMPRA 
                                AND com.PAR_STATUS = 'A'
    WHERE vte_tipodocto = 'a'
      AND PEN_VENTA IN ('AFME', 'BAAZ', 'BAJI', 'BAN', 'BCMR', 'BMX', 'BNTE', 'BTL', 'CATO', 'COLISION', 'CON', 'CRE', 'FIMA', 'GP', 'IBSA', 'INCRE', 'IVLT', 'MALEA', 'PERDIDA', 'PLNCON', 'SANT', 'TRA')
      AND CONVERT(DATETIME, vte_fechdocto, 103) BETWEEN @Inicio AND @Fin;
";

// Parámetros para la consulta
$params = array($inicio, $fin);

// Ejecutar la consulta
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Obtener el resultado
$totalVentas = 0; // Inicializar en 0
if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $totalVentas = $row['totalVentas'];
}
?>
