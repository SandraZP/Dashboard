--consulta real                                  
DECLARE @Inicio DATETIME = '2024-01-01'; 
DECLARE @Fin DATETIME = '2024-12-31';   

SELECT 
    vte_fechdocto,
    c.par_descrip1 AS carline,
    d.par_descrip1 AS venta,
    pen_idpedi,
    veh_anmodelo,
    (SELECT col_descripcion 
     FROM uni_catacolor 
     WHERE col_catalogo = veh_catalogo 
       AND col_modelo = veh_anmodelo 
       AND col_clave = veh_colointe 
       AND col_tipo = 'interior') AS veh_colointe,
    veh_numserie,
    vte_docto,
    LTRIM(a.per_paterno + ' ' + a.per_materno + ' ' + a.per_nomrazon) AS cliente,
    LTRIM(b.per_paterno + ' ' + b.per_materno + ' ' + b.per_nomrazon) AS ejecutivo,
    (SELECT pet_VinToma 
     FROM uni_PediTomaUni 
     WHERE PET_IDPEDI = pen_idpedi) AS pet_VinToma,
    ISNULL(com.PAR_DESCRIP1, '') AS tipoCompra,
    1 AS Cuenta
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