<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <title>Document</title>
    <style>
        body{
            font-family: 'Roboto', sans-serif;
            padding: 30px 30px 0px 30px
        }
        .titulo_pdf{
            font-size: 10px
        }
        .descripcion{
            font-size: 10px;
            text-align: justify;
            
        }
        .l1_listado{
            font-size: 10px;
            text-align: justify;
        }
        .l2_listado{
            font-size: 10px;
            text-align: justify;
        }
        .l3_listado{
            font-size: 10px;
            text-align: justify;
        }
        .l4_listado{
            font-size: 10px;
            text-align: justify;
        }
        
        .l1_texto{
            font-size: 10px;
            padding: 0px 0px 0px 20px;
            text-align: justify;
        }
        .l2_texto{
            font-size: 10px;
            padding: 0px 0px 0px 20px;
            text-align: justify;
        }
        .l3_texto{
            font-size: 10px;
            padding: 0px 0px 0px 20px;
            text-align: justify;
        }
        .l4_texto{
            font-size: 10px;
            padding: 0px 0px 0px 20px;
            text-align: justify;
        }
        .tabla_contenedor{
            text-align: center;
        }

        .tabla_contenedor table,.tabla_contenedor2 table{
            border-collapse: collapse;
            margin: 0 auto;
            font-size: 10px;
        }

        .tabla_contenedor table th,.tabla_contenedor2 table th{
            padding: 4px 20px;
            background-color: #A6FAFF;
        }

        .tabla_contenedor3 table{
            border-collapse: collapse;
            font-size: 10px;
        }

        .tabla_contenedor3 table th{
            padding: 4px 20px;
            background-color: #A6FAFF;
        }
        .tabla_contenedor3 table td{
            text-align: center
        }
       
        .tabla_contenedor4 table{
            border-collapse: collapse;
            font-size: 10px;
        }

        .tabla_contenedor4 table th{
            padding: 4px 40px;
            background-color: red;
            color: white;
        }
        .tabla_contenedor4 table tr{
            padding: 4px 40px;
            //color: white;
        }
        .tabla_contenedor4 table td{
            text-align: center
        }
       

        .pagebreak {
            clear: both;
            page-break-after: always;
        }

        .tabla_contenedor3 table{
            float: left;
            //display: inline;
        }
        .table_contenedor3_final table{

            //display: inline-block;
            float: right;
            //border: .5px black solid;

            font-size: 8px;
        }

        .table_contenedor3_final table th{
            margin: 0;
            padding: 4px 20px;
            background-color: #A6FAFF;
            
        }
        .table_contenedor3_final table td{
            text-align: center;
            border: .5px black solid;
            margin: 0;
            padding: 4px 20px;
        }


        .table_contenedor4_final table{

            top: -680px;
            position: absolute;
            //display: inline-block;
            float: right;
            //border: .5px black solid;

            font-size: 8px;
        }

        .table_contenedor4_final table th{
            margin: 0;
            padding: 4px 20px;
            background-color: #A6FAFF;
            
        }
        .table_contenedor4_final table td{
            text-align: center;
            border: .5px black solid;
            margin: 0;
            padding: 4px 20px;
        }
        .table_contenedor5_final table{

            top: -820px;
            position: absolute;
            //display: inline-block;
            float: right;
            //border: .5px black solid;

            font-size: 8px;
        }

        .table_contenedor5_final table th{
            margin: 0;
            padding: 4px 20px;
            background-color: #A6FAFF;
            
        }
        .table_contenedor5_final table td{
            text-align: center;
            border: .5px black solid;
            margin: 0;
            padding: 4px 20px;
        }
        .table_contenedor6_final table{

            top: -820px;
            position: absolute;
            //display: inline-block;
            float: right;
            //border: .5px black solid;

            font-size: 8px;
        }

        .table_contenedor6_final table th{
            margin: 0;
            padding: 4px 20px;
            background-color: #A6FAFF;
            
        }
        .table_contenedor6_final table td{
            text-align: center;
            border: .5px black solid;
            margin: 0;
            padding: 4px 20px;
        }
        .table_contenedor7_final table{

            top: -820px;
            position: absolute;
            //display: inline-block;
            float: right;
            //border: .5px black solid;

            font-size: 8px;
        }

        .table_contenedor7_final table th{
            margin: 0;
            padding: 4px 20px;
            background-color: #A6FAFF;
            
        }
        .table_contenedor7_final table td{
            text-align: center;
            border: .5px black solid;
            margin: 0;
            padding: 4px 20px;
        }
        .table_contenedor8_final table{

            top: -820px;
            position: absolute;
            //display: inline-block;
            float: right;
            //border: .5px black solid;

            font-size: 8px;
        }

        .table_contenedor8_final table th{
            margin: 0;
            padding: 4px 20px;
            background-color: #A6FAFF;
            
        }
        .table_contenedor8_final table td{
            text-align: center;
            border: .5px black solid;
            margin: 0;
            padding: 4px 20px;
        }
        .table_contenedor9_final table{

            top: -820px;
            position: absolute;
            //display: inline-block;
            float: right;
            //border: .5px black solid;

            font-size: 8px;
        }

        .table_contenedor9_final table th{
            margin: 0;
            padding: 4px 20px;
            background-color: #A6FAFF;
            
        }
        .table_contenedor9_final table td{
            text-align: center;
            border: .5px black solid;
            margin: 0;
            padding: 4px 20px;
        }
        .table_contenedor10_final table{

            top: -820px;
            position: absolute;
            //display: inline-block;
            float: right;
            //border: .5px black solid;

            font-size: 8px;
        }

        .table_contenedor10_final table th{
            margin: 0;
            padding: 4px 20px;
            background-color: #A6FAFF;
            
        }
        .table_contenedor10_final table td{
            text-align: center;
            border: .5px black solid;
            margin: 0;
            padding: 4px 20px;
        }
        .table_contenedor11_final{
            top: 0px;
            position: absolute;
            float: right;
        }
        .table_contenedor11_final table{

            //top: 0px;
            //margin-top:40px;
            position: absolute;
            //display: inline-block;
            float: right;
            //border: .5px black solid;

            font-size: 8px;
        }

        .table_contenedor11_final table th{
            margin: 0;
            padding: 4px 20px;
            background-color: #A6FAFF;
            
        }
        .table_contenedor11_final table td{
            text-align: center;
            border: .5px black solid;
            margin: 0;
            padding: 4px 20px;
        }

        
    </style>
</head>
<body>
    <center> <strong class="titulo_pdf"> -ANEXO-   </strong> </center>
    <p class='descripcion'>Consideraciones respecto a la información relacionada con las estaciones base de nuestra representada dentro del marco del Procedimiento 
        de Supervisión de los Indicadores de Calidad del Servicio Móvil TINE y TLLI aprobado por Resolución de Consejo Directivo N° 29-2009-
        CD/OSIPTEL.</p>
    <strong class='l1_listado'>1. LISTADO DE ESTACIONES BASE EXCLUIDAS DEL CALCULO DE LOS INDICADORES DE CALIDAD PARA EL MES DE {{ $fecha }} DE <p> </p>
        {{ $anio }} SEGÚN NUEVO REGLAMENTO GENERAL DE CALIDAD DE LOS SERVICIOS PUBLICOS DE TELECOMUNICACIONES</strong>
    <p class='l1_texto'>Al respecto, le expresamos que a efectos de la exclusión de las Estaciones Base en los cálculos de los valores TINE y TLLI para el mes
        de {{ $fecha }} han sido tomados en cuenta los criterios y consideraciones establecidas para determinar las localidades ubicadas en el ámbito
        rural y/o de interés social (Según Reglamento para la Supervisión de la Cobertura de los Servicios Públicos de Telecomunicaciones
        Móviles y fijos con Acceso Inalámbrico), de tal modo, dichas Estaciones Base podrán ser encontradas en el archivo adjunto junto a las
        mediciones remitidas del mes. <br><br>
        Asimismo, aprovechamos la oportunidad para expresarle que a efectos del cálculo de los indicadores mensuales de calidad, han sido
        excluidas las Estaciones Base que se encuentran en etapa de pruebas (Cuadro I), así como aquellas que han sido puestas en operación
        durante el mes (Cuadro II). <br><br>
        Finalmente, conforme a lo indicado mediante vuestra comunicación C.464-GFS/2010, para el cálculo del indicador de Calidad TLLI (a
        nivel de estación base) se han excluido las estaciones base señaladas en el Cuadro III.
        </p>

        <strong class='l2_listado'>2. LISTADO DE ESTACIONES BASE PUESTAS EN SERVICIO COMERCIAL (ON AIR) INDICANDO NOMBRE, CÓDIGO DE ESTACIÓN
            BASE Y FECHA.</strong>

            <p class='l2_texto'>La información correspondiente a las Estaciones Base que fueron puestas en operación durante el mes de {{ $fecha }} se encuentra contenida
                en el Cuadro II del presente anexo
                </p>
                <strong class='l3_listado'>3. LISTADO DE “ESTACIONES BASE DESHABILITADAS” DEL SERVICIO COMERCIAL DURANTE EL MES DE {{ $fecha }} DE {{ $anio }},
                    INDICANDO NOMBRE, CÓDIGO DE ESTACIÓN BASE Y FECHA.</strong>
                    <p class='l3_texto'>Durante el mes de {{ $fecha }} se ha deshabilitado 0 Estación Base
                        </p>
                        <strong class='l4_listado'>4. EXCLUSIONES APLICABLES:
                        </strong>
                            <p class='l4_texto'>En relación a ello, le expresamos a vuestro Despacho que para el cálculo de los indicadores de Calidad del mes de {{ $fecha }}, se ha tomado
                                en consideración lo dispuesto en el Anexo 16 punto 5.B del Nuevo Reglamento General de Calidad de los Servicios Públicos de
                                Telecomunicaciones.
                            </p>
                            <br><br>
    <center> <strong class="titulo_pdf"> CUADRO I   </strong> </center>
    <br>
    <center> <strong class="titulo_pdf"> LISTADO DE ESTACIONES QUE SE ENCUENTRAN EN PERIODO DE PRUEBAS DURANTE EL MES DE {{ $fecha }} {{ $anio }}   </strong> </center>
    <br>
    <div class="tabla_contenedor">
        <table border=1 >
            <tr>
                <th>CODIGO</th>
                <th>NOMBRE ESTACIÓN</th>
            </tr>
            <tr>
                <td> <br>   </td>
                <td>   <br> </td>
            </tr>
        </table>

    </div>
    <br><br>
    <center> <strong class="titulo_pdf"> CUADRO II   </strong> </center>
    <br>
    <center> <strong class="titulo_pdf"> LISTADO DE ESTACIONES BASE PUESTAS EN SERVICIO COMERCIAL (ON AIR) DURANTE EL MES DE {{ $fecha }} 2021</strong> </center>
    <br>
    <div class="tabla_contenedor2">
        <table border=1 >
            <tr>
                <th>NOMBRE ESTACIÓN</th>
                <th>CODIGO</th>
                <th>ALTA</th>
            </tr>
            <tr>
                <td> <br>   </td>
                <td>   <br> </td>
                <td>   <br> </td>
            </tr>
        </table>

    </div>
    <div class="pagebreak"> </div>

    <center> <strong class="titulo_pdf"> CUADRO III   </strong> </center>
    <br>
    <center> <strong class="titulo_pdf"> LISTADO DE ESTACIONES BASE EXCLUÍDAS PARA EL CÁLCULO DE TLLI, SEGÚN OSIPTEL CARTA C.464-GFS/2010
    </strong> </center>
    <br>
    <br>


        <div class="tabla_contenedor3">
            <table border=1 >
                <tr>
                    <th>NOMBRE ESTACIÓN</th>
                    <th>CODIGO</th>
                </tr>
                <tr>
                    <td> SIHUAS  </td>
                    <td> TA5772 </td>
                </tr>
                <tr>
                    <td>AIJA </td>
                    <td>TA5775</td>
                </tr>
                <tr>
                    <td>MARCAPATA </td>
                    <td>AC4460</td>
                </tr>
                <tr>
                    <td>HUAYOPATA </td>
                    <td>AC4449</td>
                </tr>
                <tr>
                    <td>YANATILE QUEBRADA HONDA</td>
                    <td>AC4443</td>
                </tr>
                <tr>
                    <td>COCHABAMBA CACHACARA</td>
                    <td>TC5874</td>
                </tr>
                <tr>
                    <td>CHIRINOS </td>
                    <td>TC5870</td>
                </tr>
                <tr>
                    <td>SAN RAFAEL</td>
                    <td>LH3010</td>
                </tr>
                <tr>
                    <td>CARRETERA CENTRAL</td>
                    <td>LI0611</td>
                </tr>
                <tr>
                    <td>VILCASHUAMAN VISCHONGO</td>
                    <td>LA2844</td>
                </tr>
                <tr>
                    <td>CHONGOS BAJOS</td>
                    <td>LJ2713</td>
                </tr>
                <tr>
                    <td>COMAS COCHAS TUNZO</td>
                    <td>LJ2734</td>
                </tr>
                <tr>
                    <td>DORSAL GUAYACA</td>
                    <td>AP4746</td>
                </tr>
                <tr>
                    <td>PRIMAVERA</td>
                    <td>AC1731</td>
                </tr>
                <tr>
                    <td>HIPOLITO_AMARU</td>
                    <td>AC3694</td>
                </tr>
                <tr>
                    <td>HUASCAR </td>
                    <td>AC4359</td>
                </tr>
                <tr>
                    <td>ESTADIO_GARCILASO</td>
                    <td>AC4377</td>
                </tr>
                <tr>
                    <td>BAJO_MIRADOR </td>
                    <td>AC4479</td>
                </tr>
                <tr>
                    <td>EL_MESIAS </td>
                    <td>AC4485</td>
                </tr>
                <tr>
                    <td>CIRCUNVALACION_CUSCO </td>
                    <td>AC4527</td>
                </tr>
                <tr>
                    <td>ANTONIO_RAYMONDI </td>
                    <td>AC5376</td>
                </tr>
                <tr>
                    <td>SAN_FRANCISCO_CUSCO_I </td>
                    <td>AC6526</td>
                </tr>
                <tr>
                    <td>PUEBLO_LIBRE </td>
                    <td>ACU2442</td>
                </tr>
                <tr>
                    <td>PICO_CHARUYOC8 </td>
                    <td>AP18039</td>
                </tr>
                <tr>
                    <td>ULLAGACHI </td>
                    <td>AP4682</td>
                </tr>
                <tr>
                    <td>CERRO_GLORIA </td>
                    <td>AR3884</td>
                </tr>
                <tr>
                    <td>COTAHUASI_HUAYNACOTAS </td>
                    <td>AR3982</td>
                </tr>
                <tr>
                    <td>PROLONGACION_AV_EJERCITO </td>
                    <td>AR4003</td>
                </tr>
                <tr>
                    <td>BACKUS_AREQUIPA </td>
                    <td>AR4013</td>
                </tr>
                <tr>
                    <td>BACKUS </td>
                    <td>AR4013</td>
                </tr>
                <tr>
                    <td>PACUADROS </td>
                    <td>AR4044</td>
                </tr>
                <tr>
                    <td>LA_CHINA_CV </td>
                    <td>ARU2819</td>
                </tr>
                <tr>
                    <td>PAMPA_CANAHUAS </td>
                    <td>ARU3360</td>
                </tr>
                <tr>
                    <td>CERRO_GLORIA </td>
                    <td>ARU3884</td>
                </tr>
                <tr>
                    <td>QUILCA </td>
                    <td>ARU3926</td>
                </tr>
                <tr>
                    <td>CERRO_SAMA </td>
                    <td>ATU4789</td>
                </tr>
                <tr>
                    <td>CERRO_830 </td>
                    <td>ATU4804</td>
                </tr>
                <tr>
                    <td>ASAMBLEA </td>
                    <td>LA2851</td>
                </tr>
                <tr>
                    <td>PICO_GOYMA </td>
                    <td>LH18997</td>
                </tr>
                <tr>
                    <td>BELAUNDE </td>
                    <td>LI0014</td>
                </tr>
                <tr>
                    <td>LOS_MILAGROS </td>
                    <td>LI0055</td>
                </tr>
                <tr>
                    <td>ANGLO_PERUANO </td>
                    <td>LI0107</td>
                </tr>
                <tr>
                    <td>MICROCELDA_GAMARRA </td>
                    <td>LI0267</td>
                </tr>
                <tr>
                    <td>VILLA2 </td>
                    <td>LI0473</td>
                </tr>
                <tr>
                    <td>CASUARINAS_SUR </td>
                    <td>LI0488</td>
                </tr>
                <tr>
                    <td>WIESSE </td>
                    <td>LI0495</td>
                </tr>
                <tr>
                    <td>PRO </td>
                    <td>LI0496</td>
                </tr>
                <tr>
                    <td>28_DE_DICIEMBRE_TDP </td>
                    <td>LI0577</td>
                </tr>
                <tr>
                    <td>FAP_ESPALDA </td>
                    <td>LI0718</td>
                </tr>
                <tr>
                    <td>SANTA_ROSA_DE_LIMA </td>
                    <td>LI0734</td>
                </tr>
                <tr>
                    <td>MANYARI </td>
                    <td>LI0786</td>
                </tr>
                <tr>
                    <td>GERONIMO_DE_ALIAGA </td>
                    <td>LI0895</td>
                </tr>
                <tr>
                    <td>HUACHO_CHANCAY </td>
                    <td>LI0909</td>
                </tr>
                <tr>
                    <td>EL_DIENTE  </td>
                    <td>LI0913</td>
                </tr>
                
            </table>
    
        </div>
        <div class="table_contenedor3_final"> 
            <table>
                
                <tr>
                    <td> EL_SOL_DE_LURIGANCHO  </td>
                    <td> LI1022 </td>
                </tr>
                <tr>
                    <td>SAN_HILARION </td>
                    <td>LI1023</td>
                </tr>
                <tr>
                    <td>HEROES_DEL_CENEPA </td>
                    <td>LI1024</td>
                </tr>
                <tr>
                    <td>LOS_JARDINES </td>
                    <td>LI1026</td>
                </tr>
                <tr>
                    <td>JIRON_MERCADO </td>
                    <td>LI1095</td>
                </tr>
                <tr>
                    <td>SAN_DIEGO </td>
                    <td>LI1103</td>
                </tr>
                <tr>
                    <td>PESQUERO_VILLA </td>
                    <td>LI1117</td>
                </tr>
                <tr>
                    <td>VIENA </td>
                    <td>LI1206</td>
                </tr>
                <tr>
                    <td>EL_GRANDE </td>
                    <td>LI1251</td>
                </tr>
                <tr>
                    <td>VILLA_HERMOSA  </td>
                    <td>LI1290</td>
                </tr>
                <tr>
                    <td>URB_PALOMINO_MILLER  </td>
                    <td>LI1773</td>
                </tr>
                <tr>
                    <td>CALLE_21_MANCHAY  </td>
                    <td>LI2078</td>
                </tr>
                <tr>
                    <td>LA_HUAYRONA  </td>
                    <td>LI2625</td>
                </tr>
                <tr>
                    <td>URB_AMAUTA  </td>
                    <td>LI2626</td>
                </tr>
                <tr>
                    <td>MINA_ORQUIDEA  </td>
                    <td>LI2952</td>
                </tr>
                <tr>
                    <td>TERMINAL_PESQUERO_VMT  </td>
                    <td>LI3466</td>
                </tr>
                <tr>
                    <td>AV_LAS_NACIONES  </td>
                    <td>LI3573</td>
                </tr>
                <tr>
                    <td>SAN_REMO  </td>
                    <td>LI4295</td>
                </tr>
                <tr>
                    <td>DEFENSORES_VMT  </td>
                    <td>LI4621</td>
                </tr>
                <tr>
                    <td>LA_CHIRA  </td>
                    <td>LI4911</td>
                </tr>
                <tr>
                    <td>EL_DIENTE  </td>
                    <td>LIU0913</td>
                </tr>
                <tr>
                    <td>SAN_ANTONIO_DE_CHUCO  </td>
                    <td>LR18501</td>
                </tr>
                <tr>
                    <td>YAULI  </td>
                    <td>LV3060</td>
                </tr>
                <tr>
                    <td>ASUY  </td>
                    <td>TA5660</td>
                </tr>
                <tr>
                    <td>CERRO_BAUL  </td>
                    <td>TAU5641</td>
                </tr>
                <tr>
                    <td>CERRO_INFIERNILLO  </td>
                    <td>TAU5647</td>
                </tr>
                <tr>
                    <td>COMANDANTE_NOEL  </td>
                    <td>TAU5736</td>
                </tr>
            </table>
        </div>
        <div class="pagebreak"> </div>
        <div class="tabla_contenedor4"> 
            <strong class="titulo_pdf"> LISTADO DE ESTACIONES BASE EXCLUÍDAS PARA <br> EL CÁLCULO DE TLLI, SEGÚN OSIPTEL CARTA C.464-GFS/2010
            </strong>
            <br>
            <br>
            <table border=1>
                <tr>
                    <th>BCF_NAME </th>
                    <th>NOMBRESITE</th>
                </tr>
                <tr>
                    <td>AC0442</td>
                    <td>MAQUETE_SERANTA</td>
                </tr>
                <tr>
                    <td>AC0488</td>
                    <td>IVANKIRIARI</td>
                </tr>
                <tr>
                    <td>AC0500</td>
                    <td>HELARES</td>
                </tr>
                <tr>
                    <td>AC0505</td>
                    <td>NUEVA_ESPERANZA</td>
                </tr>
                <tr>
                    <td>AC0539</td>
                    <td>TAMBO_DEL_ENE</td>
                </tr>
                <tr>
                    <td>AC1614</td>
                    <td>SIHUINCHA</td>
                </tr>
                <tr>
                    <td>AC1731</td>
                    <td>PRIMAVERA</td>
                </tr>
                <tr>
                    <td>AC1846</td>
                    <td>EL_TRIUNFO_CUSCO</td>
                </tr>
                <tr>
                    <td>AC1856</td>
                    <td>NAT_QORIWAYRACHINA_P2</td>
                </tr>
                <tr>
                    <td>AC1856</td>
                    <td>NAT_QORIWAYRACHINA</td>
                </tr>
                <tr>
                    <td>AC1856</td>
                    <td>NAT_QORIWAYRACHINA_P1</td>
                </tr>
                <tr>
                    <td>AC18900</td>
                    <td>LARES</td>
                </tr>
                <tr>
                    <td>AC2026</td>
                    <td>CCOLLPAMOCO</td>
                </tr>
                <tr>
                    <td>AC2813</td>
                    <td>HUALLATUYO</td>
                </tr>
                <tr>
                    <td>AC2958</td>
                    <td>PICO_PILPINTO</td>
                </tr>
                <tr>
                    <td>AC3209</td>
                    <td>ARAY_PALPA</td>
                </tr>
                <tr>
                    <td>AC3694</td>
                    <td>HIPOLITO_AMARU</td>
                </tr>
                <tr>
                    <td>AC4076</td>
                    <td>RONDOCAN</td>
                </tr>
                <tr>
                    <td>AC4077</td>
                    <td>QUEHUE</td>
                </tr>
                <tr>
                    <td>AC4078</td>
                    <td>CHECCA</td>
                </tr>
                <tr>
                    <td>AC4079</td>
                    <td>PULPERA</td>
                </tr>
                <tr>
                    <td>AC4080</td>
                    <td>VIRGINIYOC</td>
                </tr>
                <tr>
                    <td>AC4081</td>
                    <td>OMACHA</td>
                </tr>
                <tr>
                    <td>AC4082</td>
                    <td>COLQUEPATA</td>
                </tr>
                <tr>
                    <td>AC4359</td>
                    <td>HUASCAR</td>
                </tr>
                <tr>
                    <td>AC4361</td>
                    <td>SAN_BLAS</td>
                </tr>
                <tr>
                    <td>AC4371</td>
                    <td>PACCARITAMBO</td>
                </tr>
                <tr>
                    <td>AC4373</td>
                    <td>UCHUCARCO</td>
                </tr>
                <tr>
                    <td>AC4377</td>
                    <td>ESTADIO_GARCILASO</td>
                </tr>
                <tr>
                    <td>AC4405</td>
                    <td>TINTAYA</td>
                </tr>
                <tr>
                    <td>AC4422</td>
                    <td>PALMA_REAL</td>
                </tr>
                <tr>
                    <td>AC4423</td>
                    <td>CCARHUAYO</td>
                </tr>
                <tr>
                    <td>AC4424</td>
                    <td>RAYANCANCHA</td>
                </tr>
                <tr>
                    <td>AC4435</td>
                    <td>JAJAYACTA</td>
                </tr>
                <tr>
                    <td>AC4448</td>
                    <td>DORSAL_CHIARAJE</td>
                </tr>
                <tr>
                    <td>AC4449</td>
                    <td>HUAYOPATA</td>
                </tr>
                <tr>
                    <td>AC4453</td>
                    <td>KITENI_PROGRESO</td>
                </tr>
                <tr>
                    <td>AC4460</td>
                    <td>MARCAPATA</td>
                </tr>
                <tr>
                    <td>AC4466</td>
                    <td>KUCYA</td>
                </tr>
                <tr>
                    <td>AC4475</td>
                    <td>HUDBAY</td>
                </tr>
            </table>
        </div>
        <div class="table_contenedor4_final">
            <table>
                <tr>
                    <td>AC4479</td>
                    <td>BAJO_MIRADOR</td>
                </tr>
                <tr>
                    <td>AC4484</td>
                    <td>ANTAPACCAY</td>
                </tr>
                <tr>
                    <td>AC4485</td>
                    <td>EL_MESIAS</td>
                </tr>
                <tr>
                    <td>AC4487</td>
                    <td>CAMINO_TINTAYA</td>
                </tr>
                <tr>
                    <td>AC4492</td>
                    <td>PISCACUCHO</td>
                </tr>
                <tr>
                    <td>AC4493</td>
                    <td>CCAPI</td>
                </tr>
                <tr>
                    <td>AC4497</td>
                    <td>XSTRATA_SUR</td>
                </tr>
                <tr>
                    <td>AC4527</td>
                    <td>CIRCUNVALACION_CUSCO</td>
                </tr>
                <tr>
                    <td>AC4851</td>
                    <td>TRAILER_CASA_ANDINA</td>
                </tr>
                <tr>
                    <td>AC4851</td>
                    <td>RRU_EXT_CASA_ANDINA</td>
                </tr>
                <tr>
                    <td>AC4932</td>
                    <td>CARRETA_YAURISQUE</td>
                </tr>
                <tr>
                    <td>AC4934</td>
                    <td>TRUCK_CCAPACMARCA</td>
                </tr>
                <tr>
                    <td>AC4935</td>
                    <td>TINQUI</td>
                </tr>
                <tr>
                    <td>AC5028</td>
                    <td>LUCMA_PUNCUYOC</td>
                </tr>
                <tr>
                    <td>AC5030</td>
                    <td>LLUSCO</td>
                </tr>
                <tr>
                    <td>AC5376</td>
                    <td>RRU_EXT_TAMBOMACHAY</td>
                </tr>
                <tr>
                    <td>AC5376</td>
                    <td>ANTONIO_RAYMONDI</td>
                </tr>
                <tr>
                    <td>AC5481</td>
                    <td>YANAHUARA_CUSCO</td>
                </tr>
                <tr>
                    <td>AC5745</td>
                    <td>INCAPAUCAR</td>
                </tr>
                <tr>
                    <td>AC5786</td>
                    <td>PALCCA_ALTA</td>
                </tr>
                <tr>
                    <td>AC5789</td>
                    <td>MOSCCO</td>
                </tr>
                <tr>
                    <td>AC5892</td>
                    <td>ACCACCO</td>
                </tr>
                <tr>
                    <td>AC5930</td>
                    <td>CCASILLO</td>
                </tr>
                <tr>
                    <td>AC5935</td>
                    <td>SAN_JOSE</td>
                </tr>
                <tr>
                    <td>AC5946</td>
                    <td>PFOCCOCHALA</td>
                </tr>
                <tr>
                    <td>AC6066</td>
                    <td>LARAPATA</td>
                </tr>
                <tr>
                    <td>AC6526</td>
                    <td>SAN_FRANCISCO_CUSCO_I</td>
                </tr>
                <tr>
                    <td>ACU2442</td>
                    <td><br></td>
                </tr>
                <tr>
                    <td>AM1175</td>
                    <td>SALVIANI</td>
                </tr>
                <tr>
                    <td>AM18442</td>
                    <td>SANTIAGO_DE_PACHAS</td>
                </tr>
                <tr>
                    <td>AM3720</td>
                    <td>MINA_SAN_GABRIEL</td>
                </tr>
                <tr>
                    <td>AM4505</td>
                    <td>REFINERIA_SOUTHERN</td>
                </tr>
                <tr>
                    <td>AM4508</td>
                    <td>CARUMAS</td>
                </tr>
                <tr>
                    <td>AM4510</td>
                    <td>CUAJONE_BOTIFLACA</td>
                </tr>
                <tr>
                    <td>AM4512</td>
                    <td>ALGARROBAL</td>
                </tr>
                <tr>
                    <td>AM4513</td>
                    <td>CRUCE_ILO</td>
                </tr>
                <tr>
                    <td>AM4514</td>
                    <td>EL_PORTILLO</td>
                </tr>
                <tr>
                    <td>AM4523</td>
                    <td>MINA_CHAPI</td>
                </tr>
                <tr>
                    <td>AM4524</td>
                    <td>CUAJONE_BASE_1</td>
                </tr>
                <tr>
                    <td>AM4528</td>
                    <td>UBINAS</td>
                </tr>
                <tr>
                    <td>AM4602</td>
                    <td>PUQUINA</td>
                </tr>
                <tr>
                    <td>AM4605</td>
                    <td>CHOJATA</td>
                </tr>
                <tr>
                    <td>AM4606</td>
                    <td>COALAQUE</td>
                </tr>
                
            </table>
        </div>
        <div class="pagebreak"> </div>
        <div class="tabla_contenedor4">
            <table border=1>

                <tr>
                    <td>AM4607</td>
                    <td>LA_CAPILLA</td>
                </tr>
                <tr>
                    <td>AM4608</td>
                    <td>LLOQUE</td>
                </tr>
                <tr>
                    <td>AP1036</td>
                    <td>REP_SARA_TMP</td>
                </tr>
                <tr>
                    <td>AP18039</td>
                    <td><br></td>
                </tr>
                <tr>
                    <td>AP18342</td>
                    <td>HUANCABAMBA</td>
                </tr>
                <tr>
                    <td>AP18346</td>
                    <td>PAMPA_SALINAS</td>
                </tr>
                <tr>
                    <td>AP18358</td>
                    <td>CHOCAL</td>
                </tr>
                <tr>
                    <td>AP18366</td>
                    <td>PAMPA_GRANDE_BUENAVISTA</td>
                </tr>
                <tr>
                    <td>AP18368</td>
                    <td>PATA_YANAMAYO</td>
                </tr>
                <tr>
                    <td>AP18374</td>
                    <td>OYCUSMAYO</td>
                </tr>
                <tr>
                    <td>AP18376</td>
                    <td>RIO_BLANCO</td>
                </tr>
                <tr>
                    <td>AP18410</td>
                    <td>AYMANA</td>
                </tr>
                <tr>
                    <td>AP18422</td>
                    <td>PRIMER_LLOCOLLOCO</td>
                </tr>
                <tr>
                    <td>AP18424</td>
                    <td>MILLICUYO</td>
                </tr>
                <tr>
                    <td>AP18428</td>
                    <td>QUISHUARANI</td>
                </tr>
                <tr>
                    <td>AP18432</td>
                    <td>JURIRUNI</td>
                </tr>
                <tr>
                    <td>AP18434</td>
                    <td>PICHACANI_CENTRAL</td>
                </tr>
                <tr>
                    <td>AP18436</td>
                    <td>COMBUCO</td>
                </tr>
                <tr>
                    <td>AP18438</td>
                    <td>POTONI</td>
                </tr>
                <tr>
                    <td>AP18450</td>
                    <td>HUARACUCYO</td>
                </tr>
                <tr>
                    <td>AP18461</td>
                    <td>HUERTACUCHO</td>
                </tr>
                <tr>
                    <td>AP3018</td>
                    <td>CARUCAYA</td>
                </tr>
                <tr>
                    <td>AP3211</td>
                    <td>CENTRO_CAHUAYA</td>
                </tr>
                <tr>
                    <td>AP3213</td>
                    <td>CORISUYOA</td>
                </tr>
                <tr>
                    <td>AP3215</td>
                    <td>SANTIAGO_SORAZA</td>
                </tr>
                <tr>
                    <td>AP3216</td>
                    <td>PRIMER_CHOCCO</td>
                </tr>
                <tr>
                    <td>AP3598</td>
                    <td>SULLCA_CERCADO</td>
                </tr>
                <tr>
                    <td>AP3730</td>
                    <td>PIATA</td>
                </tr>
                <tr>
                    <td>AP3732</td>
                    <td>PICHINCUTA</td>
                </tr>
                <tr>
                    <td>AP3739</td>
                    <td>AJANANI_GRANDE</td>
                </tr>
                <tr>
                    <td>AP4301</td>
                    <td>COTACUCHO</td>
                </tr>
                <tr>
                    <td>AP4302</td>
                    <td>ISIVILLA</td>
                </tr>
                <tr>
                    <td>AP4634</td>
                    <td>TUNQUINI</td>
                </tr>
                <tr>
                    <td>AP4636</td>
                    <td>SAN_MIGUEL</td>
                </tr>
                <tr>
                    <td>AP4638</td>
                    <td>TUNI_REQUENA</td>
                </tr>
                <tr>
                    <td>AP4639</td>
                    <td>SAN_SEBASTIAN_Y_COLLAN</td>
                </tr>
                <tr>
                    <td>AP4667</td>
                    <td>ISULLUO_CALLO</td>
                </tr>
                <tr>
                    <td>AP4668</td>
                    <td>CHIMALACARA</td>
                </tr>
                <tr>
                    <td>AP4669</td>
                    <td>MOROPACCO</td>
                </tr>
                <tr>
                    <td>AP4670</td>
                    <td>BAJO_JURINSALLA</td>
                </tr>
                <tr>
                    <td>AP4672</td>
                    <td>ALMOCANCHI</td>
                </tr>
                <tr>
                    <td>AP4673</td>
                    <td>COPANI_DEL_ROSARIO</td>
                </tr>
                <tr>
                    <td>AP4674</td>
                    <td>CERRO_FUTIN</td>
                </tr>
                <tr>
                    <td>AP4675</td>
                    <td>CHIFRON</td>
                </tr>
                <tr>
                    <td>AP4676</td>
                    <td>GUITARRA</td>
                </tr>
                <tr>
                    <td>AP4680</td>
                    <td>CANCHARANI</td>
                </tr>
                <tr>
                    <td>AP4681</td>
                    <td>ALIGRANDE</td>
                </tr>
                <tr>
                    <td>AP4682</td>
                    <td>ULLAGACHI</td>
                </tr>
                <tr>
                    <td>AP4684</td>
                    <td>BALSAPATA</td>
                </tr>
            </table>
        </div> 
        <div class="table_contenedor5_final">
            <table border=1>
                <tr>
                    <td>AP4685</td>
                    <td>CHOJACHI</td>
                </tr>
                <tr>
                    <td>AP4693</td>
                    <td>ITUATA</td>
                </tr>
                <tr>
                    <td>AP4694</td>
                    <td>QUIACA</td>
                </tr>
                <tr>
                    <td>AP4696</td>
                    <td>SAN_ANTONIO_DE_PUNO</td>
                </tr>
                <tr>
                    <td>AP4744</td>
                    <td>DORSAL_CERRO_HUISOROQUE</td>
                </tr>
                <tr>
                    <td>AP4745</td>
                    <td>DORSAL_SALLAHUANCA</td>
                </tr>
                <tr>
                    <td>AP4746</td>
                    <td>DORSAL_GUAYACA</td>
                </tr>
                <tr>
                    <td>AP4749</td>
                    <td>CRUCERO_POTONI</td>
                </tr>
                <tr>
                    <td>AP4752</td>
                    <td>SILLUSTANI</td>
                </tr>
                <tr>
                    <td>AP4755</td>
                    <td>MINSUR</td>
                </tr>
                <tr>
                    <td>AP4760</td>
                    <td>ISANURA_SECTOR</td>
                </tr>
                <tr>
                    <td>AP4767</td>
                    <td>COPANI</td>
                </tr>
                <tr>
                    <td>AP4783</td>
                    <td>ISLAS_DEL_TITICACA</td>
                </tr>
                <tr>
                    <td>AP4832</td>
                    <td>PALCA</td>
                </tr>
                <tr>
                    <td>AP4844</td>
                    <td>CHIJICHAYA</td>
                </tr>
                <tr>
                    <td>AP4848</td>
                    <td>CORANI</td>
                </tr>
                <tr>
                    <td>AP4849</td>
                    <td>CAPAZO</td>
                </tr>
                <tr>
                    <td>AP4850</td>
                    <td>YANAMAYO</td>
                </tr>
                <tr>
                    <td>AP4858</td>
                    <td>UMACHIRI</td>
                </tr>
                <tr>
                    <td>AP4863</td>
                    <td>SINA</td>
                </tr>
                <tr>
                    <td>AP4864</td>
                    <td>AYRAMPUNI</td>
                </tr>
                <tr>
                    <td>AP4865</td>
                    <td>INCHUPALLA</td>
                </tr>
                <tr>
                    <td>AP4866</td>
                    <td>VILQUE_CHICO</td>
                </tr>
                <tr>
                    <td>AP4869</td>
                    <td>PILCOPATA</td>
                </tr>
                <tr>
                    <td>AP4870</td>
                    <td>NACARIA</td>
                </tr>
                <tr>
                    <td>AP4874</td>
                    <td>CHOGNACAHUA</td>
                </tr>
                <tr>
                    <td>AR18386</td>
                    <td>LA_ESPANOLITA</td>
                </tr>
                <tr>
                    <td>AR2062</td>
                    <td>NAT_CONSTRUCCIONES_LINGA</td>
                </tr>
                <tr>
                    <td>AR2096</td>
                    <td>NAT_CUARTO_CONTROL_C2</td>
                </tr>
                <tr>
                    <td>AR2592</td>
                    <td>CERRO_NEGRO_CV</td>
                </tr>
                <tr>
                    <td>AR2592</td>
                    <td>RRU_EXT_TAJO_CERRO_VERDE</td>
                </tr>
                <tr>
                    <td>AR2592</td>
                    <td>RRU_EXT_TAJO_SANTA_ROSA</td>
                </tr>
                <tr>
                    <td>AR2953</td>
                    <td>DISPATCH_CV</td>
                </tr>
                <tr>
                    <td>AR3360</td>
                    <td>PAMPA_CANAHUAS</td>
                </tr>
                <tr>
                    <td>AR3377</td>
                    <td>CHUCURA</td>
                </tr>
                <tr>
                    <td>AR3816</td>
                    <td>NAT_PAMPA_BAJA</td>
                </tr>
                <tr>
                    <td>AR3856</td>
                    <td>TOMEPAMPA</td>
                </tr>
                <tr>
                    <td>AR3879</td>
                    <td>FREEPORT_MCMORAN</td>
                </tr>
                <tr>
                    <td>AR3879</td>
                    <td>RRU_EXT_NUEVAS_CONSTRUCCIONES_
                        TMP
                        </td>
                </tr>
                <tr>
                    <td>AR3879</td>
                    <td>RRU_EXT_BALANZA_CV_TMP</td>
                </tr>
                <tr>
                    <td>AR3880</td>
                    <td>ATIQUIPA</td>
                </tr>
                <tr>
                    <td>AR3884</td>
                    <td>CERRO_GLORIA</td>
                </tr>
                <tr>
                    <td>AR3896</td>
                    <td>LA_JOYA</td>
                </tr>
                <tr>
                    <td>AR3902</td>
                    <td>MEJIA</td>
                </tr>
                <tr>
                    <td>AR3921</td>
                    <td>EL_FISCAL</td>
                </tr>
                <tr>
                    <td>AR3923</td>
                    <td>URACA</td>
                </tr>
                <tr>
                    <td>AR3925</td>
                    <td>YURA</td>
                </tr>
                <tr>
                    <td>AR3926</td>
                    <td>QUILCA</td>
                </tr>
                <tr>
                    <td>AR3927</td>
                    <td>MOGOTES</td>
                </tr>
            </table>
        </div>
        <div class="pagebreak"> </div>
        <div class="tabla_contenedor4">
            <table border=1 >
                <tr>
                    <td>AR3930</td>
                    <td>CERRO_VERDE</td>
                </tr>
                <tr>
                    <td>AR3934</td>
                    <td>ALTO_QUILCA</td>
                </tr>
                <tr>
                    <td>AR3944</td>
                    <td>IQUIPI</td>
                </tr>
                <tr>
                    <td>AR3960</td>
                    <td>LA_NEGRA</td>
                </tr>
                <tr>
                    <td>AR3961</td>
                    <td>LA_PUNTA</td>
                </tr>
                <tr>
                    <td>AR3970</td>
                    <td>CHUQUIBAMBA</td>
                </tr>
                <tr>
                    <td>AR3978</td>
                    <td>CERRO_QUEMADO</td>
                </tr>
                <tr>
                    <td>AR3981</td>
                    <td>HUANCA_LUTA</td>
                </tr>
                <tr>
                    <td>AR3982</td>
                    <td>COTAHUASI_HUAYNACOTAS</td>
                </tr>
                <tr>
                    <td>AR3984</td>
                    <td>MINA_ARCATA</td>
                </tr>
                <tr>
                    <td>AR3987</td>
                    <td>MINA_ARES</td>
                </tr>
                <tr>
                    <td>AR4003</td>
                    <td>PROLONGACION_AV_EJERCITO</td>
                </tr>
                <tr>
                    <td>AR4013</td>
                    <td>BACKUS</td>
                </tr>
                <tr>
                    <td>AR4019</td>
                    <td>DORSAL_LAURAMOCO</td>
                </tr>
                <tr>
                    <td>AR4022</td>
                    <td>MADRIGAL</td>
                </tr>
                <tr>
                    <td>AR4024</td>
                    <td>SAYLA</td>
                </tr>
                <tr>
                    <td>AR4040</td>
                    <td>YANAQUIHUA</td>
                </tr>
                <tr>
                    <td>AR4044</td>
                    <td>PACUADROS</td>
                </tr>
                <tr>
                    <td>AR4049</td>
                    <td>CHIGUATA</td>
                </tr>
                <tr>
                    <td>AR4051</td>
                    <td>NAT_CLUB_MEJIA_P1</td>
                </tr>
                <tr>
                    <td>AR4051</td>
                    <td>NAT_CLUB_MEJIA_P2</td>
                </tr>
                <tr>
                    <td>AR4053</td>
                    <td>LLUTA</td>
                </tr>
                <tr>
                    <td>AR4054</td>
                    <td>POLOBAYA</td>
                </tr>
                <tr>
                    <td>AR4061</td>
                    <td>SIGUAS</td>
                </tr>
                <tr>
                    <td>AR4065</td>
                    <td>CAHUACHO</td>
                </tr>
                <tr>
                    <td>AR4066</td>
                    <td>ACHANIZO</td>
                </tr>
                <tr>
                    <td>AR4067</td>
                    <td>PUEBLO_VIEJO</td>
                </tr>
                <tr>
                    <td>AR4069</td>
                    <td>ANDAGUA</td>
                </tr>
                <tr>
                    <td>AR4071</td>
                    <td>TUTI</td>
                </tr>
                <tr>
                    <td>AR4072</td>
                    <td>CAYARANI</td>
                </tr>
                <tr>
                    <td>AR4073</td>
                    <td>CHICHAS</td>
                </tr>
                <tr>
                    <td>AR4074</td>
                    <td>VELINGA</td>
                </tr>
                <tr>
                    <td>AR4120</td>
                    <td>ALCA</td>
                </tr>
                <tr>
                    <td>AR4222</td>
                    <td>CARRETA_CHAPI</td>
                </tr>
                <tr>
                    <td>AR5101</td>
                    <td>NAT_IMATA</td>
                </tr>
                <tr>
                    <td>AR5301</td>
                    <td>LA_CANO</td>
                </tr>
                <tr>
                    <td>AR5701</td>
                    <td>CALSUR</td>
                </tr>
                <tr>
                    <td>AR5769</td>
                    <td>PLANCHADA</td>
                </tr>
                <tr>
                    <td>ARU2819</td>
                    <td><br></td>
                </tr>
                <tr>
                    <td>ARU3360</td>
                    <td><br></td>
                </tr>
                <tr>
                    <td>ARU3884</td>
                    <td><br></td>
                </tr>
                <tr>
                    <td>ARU3926</td>
                    <td><br></td>
                </tr>
                <tr>
                    <td>AT3122</td>
                    <td>NAT_TOMOLLO</td>
                </tr>
                <tr>
                    <td>AT3628</td>
                    <td>NAT_BOCA_DEL_RIO</td>
                </tr>
                <tr>
                    <td>AT3770</td>
                    <td>NAT_LA_MARINA_TACNA</td>
                </tr>
                <tr>
                    <td>AT4062</td>
                    <td>PALCA</td>
                </tr>
                <tr>
                    <td>AT4063</td>
                    <td>CURIBAYA</td>
                </tr>
                <tr>
                    <td>AT4363</td>
                    <td>BARRIO_OBRERO</td>
                </tr>
                <tr>
                    <td>AT4379</td>
                    <td>HIGUERANI</td>
                </tr>
            </table>
        </div>
        <div class="table_contenedor6_final">
            <table>
                <tr>
                    <td>AT4548</td>
                    <td>HOSPICIO_LOS_PALOS</td>
                </tr>
                <tr>
                    <td>AT4787</td>
                    <td>SANTA_ROSA</td>
                </tr>
                <tr>
                    <td>AT4788</td>
                    <td>TOQUEPALA_INCAPUQUIO</td>
                </tr>
                <tr>
                    <td>AT4789</td>
                    <td>CERRO_SAMA</td>
                </tr>
                <tr>
                    <td>AT4790</td>
                    <td>BOCA_DEL_RIO</td>
                </tr>
                <tr>
                    <td>AT4791</td>
                    <td>LA_YARADA</td>
                </tr>
                <tr>
                    <td>AT4796</td>
                    <td>ILO_SAMA</td>
                </tr>
                <tr>
                    <td>AT4797</td>
                    <td>LOS_PALOS</td>
                </tr>
                <tr>
                    <td>AT4799</td>
                    <td>ILABAYA</td>
                </tr>
                <tr>
                    <td>AT4800</td>
                    <td>CERRO_TOQUEPALA</td>
                </tr>
                <tr>
                    <td>AT4804</td>
                    <td>CERRO_830</td>
                </tr>
                <tr>
                    <td>AT4805</td>
                    <td>LAS_YARAS</td>
                </tr>
                <tr>
                    <td>AT4827</td>
                    <td>ITE</td>
                </tr>
                <tr>
                    <td>AT4837</td>
                    <td>CAMILACA</td>
                </tr>
                <tr>
                    <td>AT4872</td>
                    <td>MINSUR_PUCAMARCA</td>
                </tr>
                <tr>
                    <td>AT6234</td>
                    <td>MIRAVE</td>
                </tr>
                <tr>
                    <td>ATU4789</td>
                    <td><br></td>
                </tr>
                <tr>
                    <td>ATU4804</td>
                    <td><br></td>
                </tr>
                <tr>
                    <td>LA0032</td>
                    <td>CONCEPCION</td>
                </tr>
                <tr>
                    <td>LA0140</td>
                    <td>CCOCHAPAMPA</td>
                </tr>
                <tr>
                    <td>LA0141</td>
                    <td>ANCHACHUASI</td>
                </tr>
                <tr>
                    <td>LA0146</td>
                    <td>ROSASPATA</td>
                </tr>
                <tr>
                    <td>LA0171</td>
                    <td>MANALLASAQ</td>
                </tr>
                <tr>
                    <td>LA0184</td>
                    <td>ANDABAMBA</td>
                </tr>
                <tr>
                    <td>LA0197</td>
                    <td>ACCQCCASA</td>
                </tr>
                <tr>
                    <td>LA0231</td>
                    <td>MILLPO</td>
                </tr>
                <tr>
                    <td>LA0247</td>
                    <td>HUAYLLAPAMPA</td>
                </tr>
                <tr>
                    <td>LA0277</td>
                    <td>QANQAYLLO</td>
                </tr>
                <tr>
                    <td>LA0281</td>
                    <td>LOS_ANGELES</td>
                </tr>
                <tr>
                    <td>LA0285</td>
                    <td>COCHAS</td>
                </tr>
                <tr>
                    <td>LA0302</td>
                    <td>SANTA_CATALINA_TRANCA</td>
                </tr>
                <tr>
                    <td>LA0325</td>
                    <td>URAS</td>
                </tr>
                <tr>
                    <td>LA0330</td>
                    <td>CHAQO</td>
                </tr>
                <tr>
                    <td>LA0342</td>
                    <td>COCHAS_ALTA</td>
                </tr>
                <tr>
                    <td>LA18390</td>
                    <td>SAN_JOSE_DE_SOCOS</td>
                </tr>
                <tr>
                    <td>LA18392</td>
                    <td>VALLE_MARCOPUQUIO</td>
                </tr>
                <tr>
                    <td>LA18458</td>
                    <td>SANTA_ISABEL_DEL_TRIGAL</td>
                </tr>
                <tr>
                    <td>LA2299</td>
                    <td>SAN_JOSE_DE_SECCE</td>
                </tr>
                <tr>
                    <td>LA2628</td>
                    <td>SANTA_ANA_DE_HUAYCAHUACHO</td>
                </tr>
                <tr>
                    <td>LA2801</td>
                    <td>CARRETA_CATALINA</td>
                </tr>
                <tr>
                    <td>LA2824</td>
                    <td>LARAMATE</td>
                </tr>
                <tr>
                    <td>LA2825</td>
                    <td>ENTRADA_CHUSCHI</td>
                </tr>
                <tr>
                    <td>LA2830</td>
                    <td>CERRO_YANAORCO</td>
                </tr>
                <tr>
                    <td>LA2834</td>
                    <td>MOZOBAMBA</td>
                </tr>
                <tr>
                    <td>LA2844</td>
                    <td>VILCASHUAMAN_VISCHONGO</td>
                </tr>
                <tr>
                    <td>LA2851</td>
                    <td>ASAMBLEA</td>
                </tr>
                <tr>
                    <td>LA2857</td>
                    <td>CCOWISA</td>
                </tr>
                <tr>
                    <td>LA2861</td>
                    <td>ACOS_VINCHOS</td>
                </tr>
                <tr>
                    <td>LA2863</td>
                    <td>ACOCRO</td>
                </tr>
            </table>
        </div>
        <div class="pagebreak"> </div>
        <div class="tabla_contenedor4">
            <table border=1 >
                <tr><td>LA2871</td> <td>VINCHOS                      </td></tr>
<tr><td>LA2874</td> <td>CARHUANCA                        </td></tr>
<tr><td>LA2878</td> <td>SANTIAGO_DE_LUCANAMARCA                      </td></tr>
<tr><td>LA2879</td> <td>OCROS                        </td></tr>
<tr><td>LA2881</td> <td>VIRACOCHAN                       </td></tr>
<tr><td>LA2882</td> <td>CHIQUINTIRCA                     </td></tr>
<tr><td>LA2883</td> <td>CHUNGUI                      </td></tr>
<tr><td>LA2884</td> <td>PAMPAS_CARRANZA                      </td></tr>
<tr><td>LA2885</td> <td>TAMBO_QUEMADO                        </td></tr>
<tr><td>LA2886</td> <td>LLAUTA                       </td></tr>
<tr><td>LA2887</td> <td>SAISA                        </td></tr>
<tr><td>LA2889</td> <td>SAN_PEDRO_DE_PALCO                       </td></tr>
<tr><td>LA2890</td> <td>LUCIA                        </td></tr>
<tr><td>LA2891</td> <td>RIVACAYCO                        </td></tr>
<tr><td>LA2892</td> <td>ALPABAMBA                        </td></tr>
<tr><td>LA2893</td> <td>SAURAMA                      </td></tr>
<tr><td>LA2894</td> <td>PACCHA                       </td></tr>
<tr><td>LA2895</td> <td>CACHI                        </td></tr>
<tr><td>LA2897</td> <td>CHILCAS                      </td></tr>
<tr><td>LA2898</td> <td>SANTA_FILOMENA                       </td></tr>
<tr><td>LA2899</td> <td>ANISO                        </td></tr>
<tr><td>LA2900</td> <td>MARCABAMBA                       </td></tr>
<tr><td>LA2908</td> <td>PARAS                        </td></tr>
<tr><td>LA2909</td> <td>PUYUSCA                      </td></tr>
<tr><td>LA3062</td> <td>MINA_INMACULADA                      </td></tr>
<tr><td>LA3089</td> <td>HUAMBALPA                        </td></tr>
<tr><td>LA3102</td> <td>ACCOMARCA                        </td></tr>
<tr><td>LA3250</td> <td>PUTACCA                      </td></tr>
<tr><td>LA6100</td> <td>QUILCATA                     </td></tr>
<tr><td>LA6128</td> <td>CAYARA                       </td></tr>
<tr><td>LA6383</td> <td>LARCAY                       </td></tr>
<tr><td>LA6553</td> <td>MACHENTE                     </td></tr>
<tr><td>LC1483</td> <td>HUMAY                        </td></tr>
<tr><td>LC1715</td> <td>PUERTO_SAN_NICOLAS                       </td></tr>
<tr><td>LC2047</td> <td>SHOUGANG_1                       </td></tr>
<tr><td>LC2509</td> <td>SAN_ANTONIO                      </td></tr>
<tr><td>LC2529</td> <td>LUCUMILLO                        </td></tr>
<tr><td>LC2602</td> <td>LOS_CERRILLOS                        </td></tr>
<tr><td>LC2607</td> <td>MARCONA                      </td></tr>
<tr><td>LC2610</td> <td>PAMPAHUASI                       </td></tr>
<tr><td>LC2630</td> <td>CHINCHA_BAJA                     </td></tr>
<tr><td>LC2651</td> <td>ALTO_PISCO                       </td></tr>
<tr><td>LC2658</td> <td>GASEODUCTO                       </td></tr>
<tr><td>LC2660</td> <td>SAN_ANDRES_ICA                       </td></tr>
<tr><td>LC2672</td> <td>CERRO_LINDO                      </td></tr>
<tr><td>LC2687</td> <td>CHAVIN_ICA                       </td></tr>
<tr><td>LC2688</td> <td>YANAC                        </td></tr>
<tr><td>LC2689</td> <td>TIBILLO                      </td></tr>
<tr><td>LC3243</td> <td>LA_CALERA                        </td></tr>
            </table>
        </div>
        <div class="table_contenedor7_final">
            <table>
                <tr><td>LC3569 </td><td>SHOUGANG_2</td></tr>
<tr><td>LC3967 </td><td>ENTRADA_ICA</td></tr>
<tr><td>LC4080 </td><td>CHANGUILLO</td></tr>
<tr><td>LC4326 </td><td>MARCOBRE</td></tr>
<tr><td>LC6176 </td><td>MINA_JUSTA</td></tr>
<tr><td>LH0596 </td><td>SAN_JOAQUIN</td></tr>
<tr><td>LH0603 </td><td>OCUCALLA</td></tr>
<tr><td>LH0604 </td><td>CORRALCANCHA</td></tr>
<tr><td>LH0648 </td><td>SN_SEBASTIAN_SHISMAY</td></tr>
<tr><td>LH0725 </td><td>SAN_JUAN_LIBERTAD</td></tr>
<tr><td>LH0763 </td><td>ANTIL</td></tr>
<tr><td>LH0793 </td><td>LIBERACION_DE_YAURAN</td></tr>
<tr><td>LH0800 </td><td>HUANGANA_PAMPA</td></tr>
<tr><td>LH0806 </td><td>YURIMAGUAS</td></tr>
<tr><td>LH0816 </td><td>BUENOS_AIRES</td></tr>
<tr><td>LH0824 </td><td>TUPAC_YUPANQUI</td></tr>
<tr><td>LH0825 </td><td>LA_MERCED_DE_LOCRO</td></tr>
<tr><td>LH1880 </td><td>SANTA_ROSA_DE_YANAYACU</td></tr>
<tr><td>LH18800</td><td> ALTO_PENDENCIA</td></tr>
<tr><td>LH18806</td><td> NUEVO_TAHUANTINSUYO</td></tr>
<tr><td>LH18808</td><td> ANTAPUCRO</td></tr>
<tr><td>LH18812</td><td> CULINPAMPA</td></tr>
<tr><td>LH18816</td><td> HOYADA</td></tr>
<tr><td>LH1885 </td><td>PICO_DANUBIO</td></tr>
<tr><td>LH18862</td><td> SAN_CRISTOBAL</td></tr>
<tr><td>LH18866</td><td> FLOR_DE_UMARI</td></tr>
<tr><td>LH18997</td><td> PICO_GOYMA</td></tr>
<tr><td>LH1962 </td><td>MACUYA</td></tr>
<tr><td>LH2729 </td><td>7_DE_OCTUBRE</td></tr>
<tr><td>LH2931 </td><td>ODEBRECH_CHAGLLA</td></tr>
<tr><td>LH2939 </td><td>JACAS_CHICO</td></tr>
<tr><td>LH2949 </td><td>TANTAMAYO</td></tr>
<tr><td>LH2951 </td><td>SAN_MIGUEL_DE_CAURI</td></tr>
<tr><td>LH3026 </td><td>ESTANCIA_PATA</td></tr>
<tr><td>LH3027 </td><td>RANCAY</td></tr>
<tr><td>LH3032 </td><td>CHUQUISPACHA</td></tr>
<tr><td>LH3033 </td><td>CAYNA</td></tr>
<tr><td>LH3035 </td><td>HATUN_RUMI</td></tr>
<tr><td>LH3037 </td><td>CERRO_DIVISORIA</td></tr>
<tr><td>LH3612 </td><td>CHURUBAMBA</td></tr>
<tr><td>LH3614 </td><td>ALOMIAS</td></tr>
<tr><td>LH3615 </td><td>LAS_PALMAS_HUANUCO</td></tr>
<tr><td>LH3616 </td><td>SAN_PEDRO_DE_CHONTA</td></tr>
<tr><td>LH3617 </td><td>SAN_BUENAVENTURA</td></tr>
<tr><td>LH3620 </td><td>QUEROPALCA</td></tr>
<tr><td>LH3621 </td><td>JIRCAN</td></tr>
<tr><td>LH3622 </td><td>HERMILIO_VALDIZAN</td></tr>
<tr><td>LH5016 </td><td>UMARI_TAMBILLO</td></tr>
<tr><td>LH6476 </td><td>SAN_ANTONIO_DE_PALERMO</td></tr>
            </table>
        </div>
        <div class="pagebreak"> </div>
        <div class="tabla_contenedor4">
            <table border=1 >
                <tr><td>LH6477 </td><td>PACCHAYOG</td></tr>
<tr><td>LI0014 </td><td><br></td></tr>
<tr><td>LI0055 </td><td>LOS_MILAGROS</td></tr>
<tr><td>LI0107 </td><td>ANGLO_PERUANO</td></tr>
<tr><td>LI0267 </td><td>MICROCELDA_GAMARRA</td></tr>
<tr><td>LI0419 </td><td>CRUCES_GRANDES</td></tr>
<tr><td>LI0473 </td><td>VILLA2</td></tr>
<tr><td>LI0488 </td><td><br></td></tr>
<tr><td>LI0495 </td><td>WIESSE</td></tr>
<tr><td>LI0496 </td><td>PRO</td></tr>
<tr><td>LI0510 </td><td>PUNTA_CORRIENTES</td></tr>
<tr><td>LI0577 </td><td>NAT_SAN_FRANCISCO_P1</td></tr>
<tr><td>LI0577 </td><td>28_DE_DICIEMBRE_TDP</td></tr>
<tr><td>LI0611 </td><td>CARRETERA_CENTRAL</td></tr>
<tr><td>LI0718 </td><td>FAP_ESPALDA</td></tr>
<tr><td>LI0734 </td><td>SANTA_ROSA_DE_LIMA</td></tr>
<tr><td>LI0786 </td><td>MANYARI</td></tr>
<tr><td>LI0895 </td><td>GERONIMO_DE_ALIAGA</td></tr>
<tr><td>LI0906 </td><td>CERRO_SUCHE</td></tr>
<tr><td>LI0909 </td><td>HUACHO_CHANCAY</td></tr>
<tr><td>LI0913 </td><td>EL_DIENTE</td></tr>
<tr><td>LI0916 </td><td>HUANEC_YAUYOS</td></tr>
<tr><td>LI0919 </td><td>PLAYA_PALILLOS</td></tr>
<tr><td>LI0924 </td><td>CARRETERA_LUNAHUANA</td></tr>
<tr><td>LI0929 </td><td>QUILMANA</td></tr>
<tr><td>LI0930 </td><td>SANTA_ROSA_DE_QUIVEZ</td></tr>
<tr><td>LI0935 </td><td>SAN_CRISTOBAL</td></tr>
<tr><td>LI0938 </td><td>PLAYA_LOS_LOBOS</td></tr>
<tr><td>LI1003 </td><td>CASAPALCA</td></tr>
<tr><td>LI1022 </td><td>EL_SOL_DE_LURIGANCHO</td></tr>
<tr><td>LI1023 </td><td>SAN_HILARION</td></tr>
<tr><td>LI1024 </td><td>HEROES_DEL_CENEPA</td></tr>
<tr><td>LI1026 </td><td>LOS_JARDINES_ESTE</td></tr>
<tr><td>LI1095 </td><td>JIRON_MERCADO</td></tr>
<tr><td>LI1103 </td><td>SAN_DIEGO</td></tr>
<tr><td>LI1117 </td><td>PESQUERO_VILLA</td></tr>
<tr><td>LI1146 </td><td>MINA_UCCHUCHACUA</td></tr>
<tr><td>LI1147 </td><td>CASAPALCA_DORSAL</td></tr>
<tr><td>LI1148 </td><td>MINERA_LOS_QUENUALES</td></tr>
<tr><td>LI1148 </td><td>NAT_QUENUALES</td></tr>
<tr><td>LI1163 </td><td>LAMPAY_TUMAN</td></tr>
<tr><td>LI1185 </td><td>SUMBILCA</td></tr>
<tr><td>LI1186 </td><td>LAMPIAN</td></tr>
<tr><td>LI1188 </td><td>SAN_DAMIAN</td></tr>
<tr><td>LI1192 </td><td>LANGA</td></tr>
<tr><td>LI1194 </td><td>LARAOS</td></tr>
<tr><td>LI1195 </td><td>VINAC</td></tr>
<tr><td>LI1196 </td><td>OMAS</td></tr>
<tr><td>LI1197 </td><td>PILAS</td></tr>
            </table>
        </div>
        <div class="table_contenedor8_final">
            <table>
                <tr><td>LI1206 </td><td>VIENA</td></tr>
<tr><td>LI1251 </td><td>EL_GRANDE</td></tr>
<tr><td>LI1285 </td><td>SANTA_CRUZ_ANDAMARCA</td></tr>
<tr><td>LI1290 </td><td>VILLA_HERMOSA</td></tr>
<tr><td>LI1514 </td><td>OTOPONGO</td></tr>
<tr><td>LI1540 </td><td>CATAHUASI</td></tr>
<tr><td>LI1541 </td><td>CAUJUL</td></tr>
<tr><td>LI1542 </td><td>HONGOS</td></tr>
<tr><td>LI1543 </td><td>IHUARI</td></tr>
<tr><td>LI1544 </td><td>LOS_OLLEROS</td></tr>
<tr><td>LI1545 </td><td>SANTA_CRUZ_HUAURA</td></tr>
<tr><td>LI1546 </td><td>TUPE</td></tr>
<tr><td>LI1547 </td><td>ALLAUCA</td></tr>
<tr><td>LI1548 </td><td>AZANGARO_YAUYOS</td></tr>
<tr><td>LI1549 </td><td>CACRA</td></tr>
<tr><td>LI1551 </td><td>CHOCOS</td></tr>
<tr><td>LI1552 </td><td>HUANCAYA</td></tr>
<tr><td>LI1553 </td><td>LINCHA</td></tr>
<tr><td>LI1554 </td><td>MANAS</td></tr>
<tr><td>LI1555 </td><td>MARIATANA</td></tr>
<tr><td>LI1556 </td><td>PIRCA</td></tr>
<tr><td>LI1557 </td><td>TANTA</td></tr>
<tr><td>LI1558 </td><td>ARAHUAY</td></tr>
<tr><td>LI1559 </td><td>COCHAMARCA</td></tr>
<tr><td>LI1560 </td><td>JUCUL</td></tr>
<tr><td>LI1562 </td><td>LA_MERCED_SAYAN</td></tr>
<tr><td>LI1563 </td><td>QUINOCAY</td></tr>
<tr><td>LI1566 </td><td>PACARAN</td></tr>
<tr><td>LI1695 </td><td>ATAVILLOS</td></tr>
<tr><td>LI1718 </td><td>PLAYA_MISTERIO</td></tr>
<tr><td>LI1722 </td><td>COAYLLO</td></tr>
<tr><td>LI1773 </td><td>URB_PALOMINO_MILLER</td></tr>
<tr><td>LI18303</td><td> PASCANITA</td></tr>
<tr><td>LI18595</td><td> ALIS</td></tr>
<tr><td>LI18596</td><td> COLONIA</td></tr>
<tr><td>LI18599</td><td> HUANTAN</td></tr>
<tr><td>LI18652</td><td> PUEBLO_NUEVO_CHOCOS</td></tr>
<tr><td>LI18656</td><td> CANCHAN</td></tr>
<tr><td>LI18658</td><td> TANA</td></tr>
<tr><td>LI18766</td><td> AIZA</td></tr>
<tr><td>LI18818</td><td> ALLOCA</td></tr>
<tr><td>LI18820</td><td> SANTA_CRUZ_DE_PULACAMA</td></tr>
<tr><td>LI18822</td><td> CONCHAO</td></tr>
<tr><td>LI18824</td><td> PUMAHUAIN</td></tr>
<tr><td>LI18826</td><td> AUCO</td></tr>
<tr><td>LI18828</td><td> BANOS_HUARAL</td></tr>
<tr><td>LI18832</td><td> COLLARAY</td></tr>
<tr><td>LI18834</td><td> HUAYCHO_VIEJO</td></tr>
<tr><td>LI18836</td><td> OTEC</td></tr>
            </table>
        </div>
        <div class="pagebreak"> </div>
        <div class="tabla_contenedor4">
            <table border=1 >
                <tr><td>LI18838</td><td> HUACHINGA</td></tr>
<tr><td>LI18995</td><td> PICO_LLACALLACA</td></tr>
<tr><td>LI2078 </td><td>CALLE_21_MANCHAY</td></tr>
<tr><td>LI2441 </td><td>TRUCK_VERANO_LAS_GRAMAS</td></tr>
<tr><td>LI2625 </td><td>LA_HUAYRONA</td></tr>
<tr><td>LI2626 </td><td>URB_AMAUTA</td></tr>
<tr><td>LI2872 </td><td>CARAL</td></tr>
<tr><td>LI2952 </td><td>MINA_ORQUIDEAS</td></tr>
<tr><td>LI3136 </td><td>CARHUAPAMPA</td></tr>
<tr><td>LI3466 </td><td>TERMINAL_PESQUERO_VMT</td></tr>
<tr><td>LI3573 </td><td>AV_LAS_NACIONES</td></tr>
<tr><td>LI3678 </td><td>PLAYA_LA_ENCONTRADA</td></tr>
<tr><td>LI3784 </td><td>NAMIAHUAIN_CAJATAMBO</td></tr>
<tr><td>LI3893 </td><td>PACARAN_FITEL</td></tr>
<tr><td>LI4295 </td><td>SAN_REMO</td></tr>
<tr><td>LI4621 </td><td>DEFENSORES_VMT</td></tr>
<tr><td>LI4838 </td><td>ZUNIGA</td></tr>
<tr><td>LI4911 </td><td>LA_CHIRA</td></tr>
<tr><td>LI4930 </td><td>FLORIDA_HUARAL</td></tr>
<tr><td>LI5071 </td><td>SAN_PEDRO_DE_HUAROQUIN</td></tr>
<tr><td>LI5328 </td><td>VILCAHUAURA</td></tr>
<tr><td>LI5378 </td><td>CHAMBARA_ALTA</td></tr>
<tr><td>LI5427 </td><td>YANGAS</td></tr>
<tr><td>LI5728 </td><td>QUINTAY</td></tr>
<tr><td>LI6241 </td><td>PALMERAS</td></tr>
<tr><td>LIU0913</td><td></td></tr>
<tr><td>LJ0826 </td><td>TUPAC_AMARU_II</td></tr>
<tr><td>LJ0852 </td><td>ALTO_INCARIADO</td></tr>
<tr><td>LJ0857 </td><td>SAN_JUAN_CENTRO_AUTIKI</td></tr>
<tr><td>LJ0858 </td><td>INPITATO_CASCADA</td></tr>
<tr><td>LJ0859 </td><td>PAMPA_CAMONA_CANON</td></tr>
<tr><td>LJ0867 </td><td>EL_MILAGRO</td></tr>
<tr><td>LJ0874 </td><td>28_DE_JULIO</td></tr>
<tr><td>LJ0876 </td><td>UNION_SANTA_ROSA</td></tr>
<tr><td>LJ0879 </td><td>TUNZO_ALTO</td></tr>
<tr><td>LJ0887 </td><td>TALHUIS</td></tr>
<tr><td>LJ1417 </td><td>VALLE_MANTARO</td></tr>
<tr><td>LJ1800 </td><td>KIVINAKI</td></tr>
<tr><td>LJ2685 </td><td>9_DE_DICIEMBRE_HYO</td></tr>
<tr><td>LJ2694 </td><td>RUNATULLO</td></tr>
<tr><td>LJ2708 </td><td>CONCENTRADORA</td></tr>
<tr><td>LJ2720 </td><td>SAN_LUIS_DE_SHUARO</td></tr>
<tr><td>LJ2725 </td><td>SAN_CRISTOBAL_JUNIN</td></tr>
<tr><td>LJ2730 </td><td>LLOCLLAPAMPA</td></tr>
<tr><td>LJ2731 </td><td>CHONGOS_ALTOS</td></tr>
<tr><td>LJ2734 </td><td>COMAS_COCHAS_TUNZO</td></tr>
<tr><td>LJ2735 </td><td>VITOC_JUNIN</td></tr>
<tr><td>LJ2750 </td><td>RIO_TAMBO</td></tr>
<tr><td>LJ2751 </td><td>SANTA_BARBARA_DE_CARHUACAYAN</td></tr>
            </table>
        </div>

        <div class="table_contenedor9_final">
            <table>
                <tr><td>LJ2752 </td><td>YURINAKI</td></tr>
<tr><td>LJ2754 </td><td>LLAYLLA</td></tr>
<tr><td>LJ2758 </td><td>PUERTO_OCOPA</td></tr>
<tr><td>LJ2769 </td><td>RIO_NEGRO</td></tr>
<tr><td>LJ2774 </td><td>SAN_JOSE_DE_QUERO</td></tr>
<tr><td>LJ2775 </td><td>RICRAN</td></tr>
<tr><td>LJ2780 </td><td>TRUCK_CHINALCO</td></tr>
<tr><td>LJ2786 </td><td>YANACANCHA</td></tr>
<tr><td>LJ2787 </td><td>JARPA</td></tr>
<tr><td>LJ2788 </td><td>PAMPA_CAMONA</td></tr>
<tr><td>LJ2791 </td><td>MARCAPOMACOCHA</td></tr>
<tr><td>LJ2796 </td><td>CERRO_QUILLA</td></tr>
<tr><td>LJ2798 </td><td>PACCHA_MIRAFLORES</td></tr>
<tr><td>LJ2806 </td><td>SANTO_DOMINGO_DE_ACOBAMBA</td></tr>
<tr><td>LJ2817 </td><td>PANTI</td></tr>
<tr><td>LJ2976 </td><td>HUAYRE</td></tr>
<tr><td>LJ3823 </td><td>CAMPAMENTO_TUNSHURUCO_S1</td></tr>
<tr><td>LJ3953 </td><td>SAN_VICENTE</td></tr>
<tr><td>LJ4384 </td><td>SANCHAMARCA</td></tr>
<tr><td>LJU2805</td><td><br></td></tr>
<tr><td>LL0898 </td><td>DOS_DE_MAYO</td></tr>
<tr><td>LL0904 </td><td>TUPAC_AMARU</td></tr>
<tr><td>LL0916 </td><td>TRANCAYACU</td></tr>
<tr><td>LL0947 </td><td>PUERTO_PERU</td></tr>
<tr><td>LL0965 </td><td>SAN_PEDRO_ZAPOTE</td></tr>
<tr><td>LL0986 </td><td>SANTA_TERESA</td></tr>
<tr><td>LL0995 </td><td>DOS_DE_MAYO</td></tr>
<tr><td>LL0998 </td><td>SANTA_CLARA_I</td></tr>
<tr><td>LL0999 </td><td>SANTA_CLOTILDE</td></tr>
<tr><td>LL1015 </td><td>SANTA_CLARA_III_ZONA</td></tr>
<tr><td>LL1714 </td><td>ROABOYA_NATIVA</td></tr>
<tr><td>LL3426 </td><td>ANDOAS</td></tr>
<tr><td>LM3328 </td><td>LA_NOVIA_MDD</td></tr>
<tr><td>LM3506 </td><td>CARRETERA_INAMBARI</td></tr>
<tr><td>LM3516 </td><td>DELTA_1</td></tr>
<tr><td>LM3553 </td><td>CAFETAL_MAVILA</td></tr>
<tr><td>LM3554 </td><td>ALEGRIA</td></tr>
<tr><td>LM3556 </td><td>SUDADERO</td></tr>
<tr><td>LM3558 </td><td>CHORRILLOS_MADRE_DE_DIOS</td></tr>
<tr><td>LM3559 </td><td>SAN_JUAN_GRANDE</td></tr>
<tr><td>LM3560 </td><td>CAYCHIHUE</td></tr>
<tr><td>LM3561 </td><td>CARMENRITA</td></tr>
<tr><td>LM3562 </td><td>VIRGEN_DE_LA_CANDELARIA</td></tr>
<tr><td>LM3563 </td><td>FLORIDA_ALTA</td></tr>
<tr><td>LM3564 </td><td>IZUYANA</td></tr>
<tr><td>LM3565 </td><td>SACHAVACAYOC</td></tr>
<tr><td>LM3735 </td><td>INFIERNO</td></tr>
<tr><td>LM3737 </td><td>PALMA_REAL_CANON</td></tr>
<tr><td>LM3738 </td><td>PUENTE_INAMBARI</td></tr>
            </table>
        </div>

        <div class="pagebreak"> </div>
        <div class="tabla_contenedor4">
            <table border=1 >
                <tr><td>LM4229 </td><td>TUMI</td></tr>
<tr><td>LM4936 </td><td>LA_PAMPA_MDD</td></tr>
<tr><td>LM5479 </td><td>SAN_LORENZO_MDD</td></tr>
<tr><td>LM5782 </td><td>ALERTA_MDD</td></tr>
<tr><td>LM6347 </td><td>CHOQUE</td></tr>
<tr><td>LP1032 </td><td>CHURUMAZU</td></tr>
<tr><td>LP18844</td><td> ANCAHUACHANAN</td></tr>
<tr><td>LP18858</td><td> SANTA_ROSA_DE_CHORA_BAJA</td></tr>
<tr><td>LP3203 </td><td>MILPO</td></tr>
<tr><td>LP3231 </td><td>PAN_AMERICAN_SILVER</td></tr>
<tr><td>LP3255 </td><td>YARUSYACAN</td></tr>
<tr><td>LP3258 </td><td>SHAURIN</td></tr>
<tr><td>LP4856 </td><td>AUQUIMARCA</td></tr>
<tr><td>LP4857 </td><td>BELLAVISTA_PASCO</td></tr>
<tr><td>LP4860 </td><td>CHINCHE</td></tr>
<tr><td>LP4861 </td><td>ANTAPIRCA</td></tr>
<tr><td>LP5948 </td><td>UNION_SIRIA</td></tr>
<tr><td>LP6478 </td><td>MACHCAN</td></tr>
<tr><td>LR1582 </td><td>NAT_CC_CAMP_P1</td></tr>
<tr><td>LR1850 </td><td>SAN_ANTONIO_DE_CHUCO</td></tr>
<tr><td>LR18501</td><td><br></td></tr>
<tr><td>LR3097 </td><td>CONCENTRADORA</td></tr>
<tr><td>LR3131 </td><td>SOJYAJASA</td></tr>
<tr><td>LR3140 </td><td>OCOBAMBA_ROCHAC</td></tr>
<tr><td>LR3143 </td><td>MINA_SELENE</td></tr>
<tr><td>LR3147 </td><td>CACHORA_CHOQUEQUIARO</td></tr>
<tr><td>LR3148 </td><td>SANTA_MARIA_DE_CHICMO</td></tr>
<tr><td>LR3149 </td><td>ISCAHUACA</td></tr>
<tr><td>LR3155 </td><td>PICHIRHUA</td></tr>
<tr><td>LR3156 </td><td>PACOBAMBA</td></tr>
<tr><td>LR3157 </td><td>XSTRATA_CC_CAMP</td></tr>
<tr><td>LR3158 </td><td>CUPISA</td></tr>
<tr><td>LR3159 </td><td>LAMBRAMA</td></tr>
<tr><td>LR3163 </td><td>CHARCASCOCHA</td></tr>
<tr><td>LR3165 </td><td>XSTRATA_CHALCOBAMBA</td></tr>
<tr><td>LR3169 </td><td>CASINCHIHUA</td></tr>
<tr><td>LR3172 </td><td>COLLPA</td></tr>
<tr><td>LR3173 </td><td>CHAPIMARCA</td></tr>
<tr><td>LR3174 </td><td>SAN_JUAN_DE_CHACNA</td></tr>
<tr><td>LR3175 </td><td>SOTCCOMAYO</td></tr>
<tr><td>LR3176 </td><td>VISCHINGAY</td></tr>
<tr><td>LR3178 </td><td>PATAYPAMPA</td></tr>
<tr><td>LR3179 </td><td>SAN_ANTONIO</td></tr>
<tr><td>LR3180 </td><td>HUAYANA</td></tr>
<tr><td>LR3181 </td><td>MATAPUQUIO</td></tr>
<tr><td>LR3183 </td><td>HUANCARAY</td></tr>
<tr><td>LR3184 </td><td>ANDARAPA</td></tr>
<tr><td>LR3186 </td><td>TORAYA</td></tr>
<tr><td>LR3187 </td><td>SAN_ANTONIO_DE_CACHI</td></tr>
            </table>
        </div>

        <div class="table_contenedor10_final">
            <table>
                <tr><td>LR3188 </td><td>SANAYCA</td></tr>
<tr><td>LR3190 </td><td>KAQUIABAMBA</td></tr>
<tr><td>LR3192 </td><td>COTARUSE</td></tr>
<tr><td>LR3194 </td><td>LUCRE</td></tr>
<tr><td>LR3275 </td><td>COLCABAMBA_APURIMAC</td></tr>
<tr><td>LR3746 </td><td>YURICANCHA</td></tr>
<tr><td>LR3747 </td><td>SANTA_ROSA</td></tr>
<tr><td>LR3750 </td><td>CURCA</td></tr>
<tr><td>LS1063 </td><td>SAN_JUAN_DE_CANO</td></tr>
<tr><td>LS1072 </td><td>NUEVO_MOYOBAMBA</td></tr>
<tr><td>LS1087 </td><td>LA_VERDAD</td></tr>
<tr><td>LS1163 </td><td>CACHIYACU</td></tr>
<tr><td>LS1180 </td><td>LA_CRUZ_DE_ALTO_MAYO</td></tr>
<tr><td>LS1183 </td><td>NUEVO_JAEN</td></tr>
<tr><td>LS1194 </td><td>MANTENCION</td></tr>
<tr><td>LS1195 </td><td>SHISHIYACU</td></tr>
<tr><td>LS2795 </td><td>RAMIREZ</td></tr>
<tr><td>LS2997 </td><td>CAYNARACHI</td></tr>
<tr><td>LS3109 </td><td>CAYENA</td></tr>
<tr><td>LS3230 </td><td>SAN_FRANCISCO</td></tr>
<tr><td>LS3292 </td><td>TINGO_DE_PONAZA</td></tr>
<tr><td>LS3293 </td><td>PILLUANA</td></tr>
<tr><td>LS3298 </td><td>SAN_ANTONIO_DE_CUMBAZA</td></tr>
<tr><td>LS3304 </td><td>PACAYZAPA</td></tr>
<tr><td>LS3400 </td><td>MACEDA</td></tr>
<tr><td>LS4055 </td><td>MORRO_CALZADA</td></tr>
<tr><td>LU1196 </td><td>CALLERIA</td></tr>
<tr><td>LU1197 </td><td>NUEVO_BAGAZAN</td></tr>
<tr><td>LU1228 </td><td>NUEVA_ALEJANDRIA</td></tr>
<tr><td>LU1269 </td><td>CORAZON_JESUS</td></tr>
<tr><td>LU1281 </td><td>ASCENCION_DEL_AGUAYTILLO</td></tr>
<tr><td>LU1335 </td><td>NUEVO_SAN_JUAN_KM69</td></tr>
<tr><td>LU3209 </td><td>SANTA_ELVITA</td></tr>
<tr><td>LU3670 </td><td>SANTA_ROSA_DE_LIMA</td></tr>
<tr><td>LU4094 </td><td>ALTO_SHIRINGAL</td></tr>
<tr><td>LU5848 </td><td>BOQUERON</td></tr>
<tr><td>LV0561 </td><td>CHUSPI</td></tr>
<tr><td>LV18300</td><td> YURUPATA</td></tr>
<tr><td>LV18310</td><td><br></td></tr>
<tr><td>LV18314</td><td> COLPA</td></tr>
<tr><td>LV18340</td><td> PICO_PUEBLO_LIBRE</td></tr>
<tr><td>LV18454</td><td> RAYAN_PATA</td></tr>
<tr><td>LV18456</td><td> SANTA_CRUZ_DE_BELLAVISTA</td></tr>
<tr><td>LV18597</td><td> ARMA</td></tr>
<tr><td>LV18786</td><td> PICO_SANTA_ANA</td></tr>
<tr><td>LV18842</td><td> PICO_CAPILLAS</td></tr>
<tr><td>LV18966</td><td> PICO_ROSA_DE_PALCA</td></tr>
<tr><td>LV2029 </td><td>ANTACALLI</td></tr>
<tr><td>LV2710 </td><td>QUICHUAS</td></tr>
            </table>
        </div>

        <div class="pagebreak"> </div>
        <div class="tabla_contenedor4">
            <table border=1 >
                <tr><td>LV3056 </td><td>IZCUCHACA</td></tr>
<tr><td>LV3058 </td><td>PALCA_ACORIA</td></tr>
<tr><td>LV3060 </td><td>YAULI</td></tr>
<tr><td>LV3063 </td><td>TOCAS_POCCYAC</td></tr>
<tr><td>LV3066 </td><td>ANTAPITE</td></tr>
<tr><td>LV3068 </td><td>ACOSTAMBO</td></tr>
<tr><td>LV3072 </td><td>PUCUTO</td></tr>
<tr><td>LV3074 </td><td>HUANCA_HUANCA</td></tr>
<tr><td>LV3075 </td><td>PAUCARBAMBA</td></tr>
<tr><td>LV3076 </td><td>CONGALLA</td></tr>
<tr><td>LV3077 </td><td>SALCABAMBA</td></tr>
<tr><td>LV3078 </td><td>SURCUBAMBA</td></tr>
<tr><td>LV3079 </td><td>HUARIBAMBA</td></tr>
<tr><td>LV3081 </td><td>SALCAHUASI</td></tr>
<tr><td>LV3082 </td><td>PACHAMARCA</td></tr>
<tr><td>LV3084 </td><td>TINQUERCCASA</td></tr>
<tr><td>LV3088 </td><td>TOMAS_DE_PATA</td></tr>
<tr><td>LV3090 </td><td>CUSICANCHA</td></tr>
<tr><td>LV3091 </td><td>TAMBO</td></tr>
<tr><td>LV3092 </td><td>ANANCUSI</td></tr>
<tr><td>LV3094 </td><td>MANTA</td></tr>
<tr><td>LV3095 </td><td>HUAMATAMBO</td></tr>
<tr><td>LV3096 </td><td>SANGALLAICO</td></tr>
<tr><td>LV3101 </td><td>CHUPAMARCA</td></tr>
<tr><td>LV3103 </td><td>HUACHOS</td></tr>
<tr><td>LV3104 </td><td>COCAS</td></tr>
<tr><td>LV3105 </td><td>TRICAPO</td></tr>
<tr><td>LV3106 </td><td>SAN_ISIDRO_HUANCAVELICA</td></tr>
<tr><td>LV3109 </td><td>HUARACCOPATA</td></tr>
<tr><td>LV3111 </td><td>CORDOVA</td></tr>
<tr><td>LV3113 </td><td>PILPICHACA</td></tr>
<tr><td>LV3114 </td><td>ANDABAMBA</td></tr>
<tr><td>LV3366 </td><td>SAN_JUAN_DE_PATE</td></tr>
<tr><td>LV3477 </td><td>CDA_CASA_DE_MAQUINAS</td></tr>
<tr><td>LV3478 </td><td>CDA_PRESA</td></tr>
<tr><td>LV4531 </td><td>VILLA_MANTARO</td></tr>
<tr><td>TA1278 </td><td>HUANZALA</td></tr>
<tr><td>TA18029</td><td> QUIROBAMBA</td></tr>
<tr><td>TA18646</td><td> HONUHUANAY</td></tr>
<tr><td>TA18756</td><td> OCSHAY</td></tr>
<tr><td>TA18758</td><td> PATARA</td></tr>
<tr><td>TA18760</td><td> PARCO</td></tr>
<tr><td>TA18762</td><td> TURUNA</td></tr>
<tr><td>TA18764</td><td> CHANCASA</td></tr>
<tr><td>TA18770</td><td> RAYANPAMPA</td></tr>
<tr><td>TA18774</td><td> MANYANYACU</td></tr>
<tr><td>TA18776</td><td> TAMBO_ANCASH</td></tr>
<tr><td>TA18778</td><td> OTOCO</td></tr>
<tr><td>TA18782</td><td><br></td></tr>
            </table>
        </div>
        <div class="table_contenedor10_final">
            <table>
                <tr><td>TA18788</td><td> CHACAS</td></tr>
<tr><td>TA18790</td><td> YUMPE</td></tr>
<tr><td>TA18792</td><td> SANTA_CRUZ_-_ANCASH</td></tr>
<tr><td>TA18796</td><td> QUERORAGRA</td></tr>
<tr><td>TA2154 </td><td>CONOCOCHA</td></tr>
<tr><td>TA4240 </td><td>NAT_ANTAMINA_P2</td></tr>
<tr><td>TA4240 </td><td>NAT_ANTAMINA_P1</td></tr>
<tr><td>TA4377 </td><td>YURACMARCA</td></tr>
<tr><td>TA4976 </td><td>ANTAMINA</td></tr>
<tr><td>TA5303 </td><td>CANON_DEL_PATO</td></tr>
<tr><td>TA5354 </td><td>HUAMBACHO</td></tr>
<tr><td>TA5404 </td><td>CORICOTO</td></tr>
<tr><td>TA5636 </td><td>TORTUGAS</td></tr>
<tr><td>TA5641 </td><td>CERRO_BAUL</td></tr>
<tr><td>TA5642 </td><td>CERRO_PETROLEO</td></tr>
<tr><td>TA5644 </td><td>LA_RAMADA</td></tr>
<tr><td>TA5645 </td><td>CERRO_GRANDE</td></tr>
<tr><td>TA5647 </td><td>CERRO_INFIERNILLO</td></tr>
<tr><td>TA5657 </td><td>PIRA</td></tr>
<tr><td>TA5660 </td><td>ASUY</td></tr>
<tr><td>TA5730 </td><td>ACZO</td></tr>
<tr><td>TA5736 </td><td>COMANDANTE_NOEL</td></tr>
<tr><td>TA5766 </td><td>HUAYLAS</td></tr>
<tr><td>TA5775 </td><td>AIJA</td></tr>
<tr><td>TA5802 </td><td>DORSAL_LLAMACORRAL</td></tr>
<tr><td>TA5807 </td><td>HUACCHIS</td></tr>
<tr><td>TA5808 </td><td>OCROS</td></tr>
<tr><td>TA5820 </td><td>PAMPAROMAS</td></tr>
<tr><td>TA5821 </td><td>HUAIRAPAMPA</td></tr>
<tr><td>TA5822 </td><td>HUANCHUY</td></tr>
<tr><td>TA5823 </td><td>QUILLO</td></tr>
<tr><td>TA5824 </td><td>CORIS</td></tr>
<tr><td>TA6438 </td><td>COLCABAMBA</td></tr>
<tr><td>TA6439 </td><td>OLLEROS</td></tr>
<tr><td>TA6443 </td><td>COPA</td></tr>
<tr><td>TA6444 </td><td>HUACHIS</td></tr>
<tr><td>TA6445 </td><td>HUANCHAY</td></tr>
<tr><td>TA6446 </td><td>HUANDOVAL</td></tr>
<tr><td>TA6448 </td><td>PAMPACHACRA</td></tr>
<tr><td>TA6449 </td><td>UCHUJIRCA</td></tr>
<tr><td>TA6450 </td><td>SAN_JUAN_DE_RONTOY</td></tr>
<tr><td>TA6451 </td><td>SANACHGAN</td></tr>
<tr><td>TA6452 </td><td>ULLULLUCO</td></tr>
<tr><td>TA6453 </td><td>YAUYA</td></tr>
<tr><td>TA6454 </td><td>BAMBAS</td></tr>
<tr><td>TA6455 </td><td>YUPAN</td></tr>
<tr><td>TA6456 </td><td>ANRA</td></tr>
<tr><td>TA6457 </td><td>CHANA</td></tr>
<tr><td>TA6458 </td><td>HUAYAN</td></tr>
            </table>
        </div>

        <div class="pagebreak"> </div>
        <div class="tabla_contenedor4">
            <table border=1 >
                <tr><td>TA6460 </td><td>LLAMA_ANCASH</td></tr>
<tr><td>TA6461 </td><td>LLIPA</td></tr>
<tr><td>TA6474 </td><td>HUANZA_ANCASH</td></tr>
<tr><td>TA6475 </td><td>HUARAC_PAMPA</td></tr>
<tr><td>TAU5641</td><td><br></td></tr>
<tr><td>TAU5647</td><td><br></td></tr>
<tr><td>TAU5736</td><td><br></td></tr>
<tr><td>TC0837 </td><td>KM_11</td></tr>
<tr><td>TC1050 </td><td>LINGAN_GRANDE</td></tr>
<tr><td>TC1121 </td><td>EL_REJO</td></tr>
<tr><td>TC1310 </td><td>YANACANCHA_ALTA</td></tr>
<tr><td>TC18057</td><td> CANA_VIEJA</td></tr>
<tr><td>TC18059</td><td> CHIRIMOYO0</td></tr>
<tr><td>TC18197</td><td> PICO_SALITRE</td></tr>
<tr><td>TC18648</td><td> NUEVO_LAGUNA</td></tr>
<tr><td>TC18650</td><td> MUYOC_GRANDE</td></tr>
<tr><td>TC18654</td><td> HUANGAPATA</td></tr>
<tr><td>TC18660</td><td> SAULACA</td></tr>
<tr><td>TC18662</td><td> SANTA_ROSA_CAJAMARCA</td></tr>
<tr><td>TC18664</td><td> LAMALAMA</td></tr>
<tr><td>TC18668</td><td> NURUNUPE</td></tr>
<tr><td>TC18676</td><td> SAN_JUAN_DE_PIOBAMBA</td></tr>
<tr><td>TC18678</td><td> NUEVO_BELLA_AURORA</td></tr>
<tr><td>TC18680</td><td> CASADENCITO</td></tr>
<tr><td>TC18696</td><td> SAN_ISIDRO_DE_LAS_ROSAS</td></tr>
<tr><td>TC18698</td><td> LA_COLMENA</td></tr>
<tr><td>TC18700</td><td> VENECIA</td></tr>
<tr><td>TC18703</td><td> BUENA_VISTA</td></tr>
<tr><td>TC18704</td><td> TAYAPAMBA</td></tr>
<tr><td>TC18707</td><td> PAMPLONA</td></tr>
<tr><td>TC18710</td><td> EL_INFIERNILLO</td></tr>
<tr><td>TC18874</td><td> ESLABON</td></tr>
<tr><td>TC2967 </td><td>SHAGUINDO</td></tr>
<tr><td>TC4588 </td><td>MATARA_2</td></tr>
<tr><td>TC4926 </td><td>DUKE_CARHUAQUERO</td></tr>
<tr><td>TC4929 </td><td>TABACONAS</td></tr>
<tr><td>TC5033 </td><td>LA_POLVORA</td></tr>
<tr><td>TC5366 </td><td>AEROPUERTO_SHUMBA</td></tr>
<tr><td>TC5589 </td><td>PAMPALARGA</td></tr>
<tr><td>TC5829 </td><td>KM_24_YANACOCHA</td></tr>
<tr><td>TC5843 </td><td>CERRO_NEGRO</td></tr>
<tr><td>TC5846 </td><td>SAN_JUAN_DE_CAJAMARCA</td></tr>
<tr><td>TC5847 </td><td>ODEBRECHT_ORIENTE</td></tr>
<tr><td>TC5850 </td><td>LA_FILA</td></tr>
<tr><td>TC5851 </td><td>BELLAVISTA_CAJAMARCA</td></tr>
<tr><td>TC5858 </td><td>PINDOC</td></tr>
<tr><td>TC5860 </td><td>YERBA_BUENA</td></tr>
<tr><td>TC5871 </td><td>AGOPITI_DORSAL</td></tr>
<tr><td>TC5874 </td><td>COCHABAMBA_CACHACARA</td></tr>

            </table>
        </div>
        <div class="table_contenedor10_final">
            <table>
                <tr><td>TC5875 </td><td>BOLIVAR_NIEPOS_LA_FLORIDA</td></tr>
<tr><td>TC5877 </td><td>CHALAMARCA</td></tr>
<tr><td>TC5880 </td><td>RIO_TINTO</td></tr>
<tr><td>TC5884 </td><td>KM_45</td></tr>
<tr><td>TC5888 </td><td>SAN_PEDRO_DE_CHOTA</td></tr>
<tr><td>TC5891 </td><td>CUJILLO_YAMON</td></tr>
<tr><td>TC5894 </td><td>GALLITO_CIEGO</td></tr>
<tr><td>TC5896 </td><td>COLLOTAN</td></tr>
<tr><td>TC5899 </td><td>EL_LIRIO</td></tr>
<tr><td>TC5900 </td><td>CHETILLA</td></tr>
<tr><td>TC5901 </td><td>CACHACHI</td></tr>
<tr><td>TC5902 </td><td>CHUMUCH</td></tr>
<tr><td>TC5903 </td><td>OXAMARCA</td></tr>
<tr><td>TC59041</td><td> TRIGOPAMPA</td></tr>
<tr><td>TC5905 </td><td>TRINIDAD</td></tr>
<tr><td>TC5906 </td><td>GUZMANGO</td></tr>
<tr><td>TC5907 </td><td>SAN_BENITO</td></tr>
<tr><td>TC5908 </td><td>SAN_LUIS_DE_LUCMA</td></tr>
<tr><td>TC5909 </td><td>SANTA_CRUZ_DE_CUTERVO</td></tr>
<tr><td>TC5910 </td><td>SALLIQUE</td></tr>
<tr><td>TC5911 </td><td>SAN_FELIPE_JAEN</td></tr>
<tr><td>TC5913 </td><td>CHANCAY_CAJAMARCA</td></tr>
<tr><td>TC5914 </td><td>SHIRAC</td></tr>
<tr><td>TC5915 </td><td>EL_PRADO</td></tr>
<tr><td>TC5916 </td><td>SAN_GREGORIO</td></tr>
<tr><td>TC5917 </td><td>MIRACOSTA</td></tr>
<tr><td>TC5918 </td><td>LICUPIS</td></tr>
<tr><td>TC5919 </td><td>TOCMOCHE</td></tr>
<tr><td>TC5920 </td><td>CATAN</td></tr>
<tr><td>TC5921 </td><td>LA_SACILIA</td></tr>
<tr><td>TC5922 </td><td>HUARANDOZA</td></tr>
<tr><td>TC5923 </td><td>LA_GRAMA</td></tr>
<tr><td>TC5924 </td><td>NANCHOC</td></tr>
<tr><td>TC5925 </td><td>LIBERTAD_DE_LA_FRONTERA</td></tr>
<tr><td>TC6088 </td><td>ANEXO_CHILON</td></tr>
<tr><td>TC6400 </td><td>CHAQUICOCHA</td></tr>
<tr><td>TC6407 </td><td>SOROCHUCO</td></tr>
<tr><td>TC6412 </td><td>LA_ZANJA</td></tr>
<tr><td>TC6414 </td><td>EL_TAMBO_BAMBAMARCA</td></tr>
<tr><td>TC6416 </td><td>MIRADOR_YANACOCHA</td></tr>
<tr><td>TC6418 </td><td>QUEROCOTO</td></tr>
<tr><td>TC6429 </td><td>LA_LIBERTAD_DE_PALLAN</td></tr>
<tr><td>TC6433 </td><td>NINABAMBA</td></tr>
<tr><td>TC6462 </td><td>UNANCA</td></tr>
<tr><td>TC6463 </td><td>PATINO</td></tr>
<tr><td>TC6464 </td><td>CARRETA_SHUGARES</td></tr>
<tr><td>TC6466 </td><td>LA_ARTEZA</td></tr>
<tr><td>TC6467 </td><td>NUEVO_PARAISO</td></tr>
<tr><td>TC6468 </td><td>LA_LIBERTAD_DE_OXAMARCA</td></tr>
            </table>
        </div>
        <div class="pagebreak"> </div>
        <div class="tabla_contenedor4">
            <table border=1 >
                <tr><td>TC6503 </td><td>BOLIVAR_CARAHUASI</td></tr>
<tr><td>TC6509 </td><td>UDIMA</td></tr>
<tr><td>TJ1166 </td><td>LA_VICTORIA</td></tr>
<tr><td>TJ1181 </td><td>SAN_ISIDRO</td></tr>
<tr><td>TJ1187 </td><td>PACHIN_BAJO</td></tr>
<tr><td>TJ1473 </td><td>PALO_REDONDO</td></tr>
<tr><td>TJ1748 </td><td>MG</td></tr>
<tr><td>TJ1781 </td><td>EL_CONVENTO</td></tr>
<tr><td>TJ18019</td><td> HUARISMALCA</td></tr>
<tr><td>TJ18105</td><td> AGUA_AGRIA</td></tr>
<tr><td>TJ18216</td><td> PICO_CALEMAR</td></tr>
<tr><td>TJ18235</td><td> PICO_LOS_LOROS</td></tr>
<tr><td>TJ18239</td><td> PICO_KENTY</td></tr>
<tr><td>TJ1843 </td><td>FUNDO_COMPOSITAN</td></tr>
<tr><td>TJ18670</td><td> LONGOTEA</td></tr>
<tr><td>TJ18722</td><td> CUSHURUPAMBA</td></tr>
<tr><td>TJ18728</td><td> CRUZ_DE_AVAYA</td></tr>
<tr><td>TJ18736</td><td> LOS_LLOQUES</td></tr>
<tr><td>TJ18738</td><td> MULLAMANDAY</td></tr>
<tr><td>TJ18742</td><td> SATAPAMPA</td></tr>
<tr><td>TJ18748</td><td> LA_VINA_-_LA_LIBERTA</td></tr>
<tr><td>TJ2458 </td><td>GANDA</td></tr>
<tr><td>TJ2984 </td><td>HUAGUIL</td></tr>
<tr><td>TJ3786 </td><td>EL_TORO</td></tr>
<tr><td>TJ5087 </td><td>WARACO</td></tr>
<tr><td>TJ5105 </td><td>COSCOMBA_CHICO</td></tr>
<tr><td>TJ5106 </td><td>RINCONADA</td></tr>
<tr><td>TJ5107 </td><td>CERRO_TRES_PUNTAS</td></tr>
<tr><td>TJ5112 </td><td>LOS_PANCHITOS</td></tr>
<tr><td>TJ5115 </td><td>BOTADERO_BARRICK</td></tr>
<tr><td>TJ5117 </td><td>COCHABAMBA_LOS_PAREDONES</td></tr>
<tr><td>TJ5118 </td><td>LUCMA</td></tr>
<tr><td>TJ5121 </td><td>ALTO_CHICAMA</td></tr>
<tr><td>TJ5122 </td><td>T2_ALTO_CHICAMA</td></tr>
<tr><td>TJ5129 </td><td>VIRU_3</td></tr>
<tr><td>TJ5140 </td><td>OUICHIBAMBA</td></tr>
<tr><td>TJ5143 </td><td>CERRO_RAGACHE</td></tr>
<tr><td>TJ5161 </td><td>NUEVA_CAMPANA</td></tr>
<tr><td>TJ5171 </td><td>SINSICAP</td></tr>
<tr><td>TJ5172 </td><td>CUYUCHUNGO_COINA</td></tr>
<tr><td>TJ5174 </td><td>ATACOCHA_CASCAMINAS</td></tr>
<tr><td>TJ5175 </td><td>ATACOCHA_QUIRISPUSCO</td></tr>
<tr><td>TJ5176 </td><td>ATACOCHA_EL_CEDRO_CM_SANTA_MARIA</td></tr>
<tr><td>TJ5176 </td><td>ATACOCHA_EL_CEDRO_CM_VIJUS</td></tr>
<tr><td>TJ5176 </td><td>ATACOCHA_EL_CEDRO_CM_PARAIZO</td></tr>
<tr><td>TJ5176 </td><td>ATACOCHA_EL_CEDRO</td></tr>
<tr><td>TJ5200 </td><td>SIMBAL</td></tr>
<tr><td>TJ5235 </td><td>BARRICK_LA_LIBERTAD</td></tr>
<tr><td>TJ5236 </td><td>CHUQUIMANCO</td></tr>
            </table>
        </div>
        <div class="table_contenedor10_final">
            <table>
                <tr><td>TJ5237 </td><td>HUASO_JULCAN</td></tr>
<tr><td>TJ5238 </td><td>ONGON</td></tr>
<tr><td>TJ5240 </td><td>SANAGORAN</td></tr>
<tr><td>TJ5241 </td><td>SAN_IGNACIO_OTUZCO</td></tr>
<tr><td>TJ5261 </td><td>SITABAMBA</td></tr>
<tr><td>TJ5285 </td><td>FUNDO_ACP</td></tr>
<tr><td>TJ5358 </td><td>SOCIEDAD_AGRICOLA_VIRU</td></tr>
<tr><td>TJ5374 </td><td>FLORENCIA_ALTA</td></tr>
<tr><td>TJ5403 </td><td>ARICAPAMPA</td></tr>
<tr><td>TJ5712 </td><td>CANUCUBAMBA</td></tr>
<tr><td>TJ5728 </td><td>EL_ROCIO</td></tr>
<tr><td>TJ5764 </td><td>EL_ALIZAR</td></tr>
<tr><td>TJ6046 </td><td>MACULLIDA</td></tr>
<tr><td>TJ6102 </td><td>PUERTO_MORIN</td></tr>
<tr><td>TJ6197 </td><td>BARRICK_DISPATCH</td></tr>
<tr><td>TJ6214 </td><td>POROTO</td></tr>
<tr><td>TJ6271 </td><td>LOMBRIZ</td></tr>
<tr><td>TJ6277 </td><td>TRUCK_DANPER_VENTUROSA</td></tr>
<tr><td>TJ6328 </td><td>AERODROMO_CHAGUAL</td></tr>
<tr><td>TJ6471 </td><td>UNGUAY</td></tr>
<tr><td>TJ6472 </td><td>SANTA_ELENA_HUASO</td></tr>
<tr><td>TJ6473 </td><td>LOS_LOROS</td></tr>
<tr><td>TJ6483 </td><td>RIO_ALTO</td></tr>
<tr><td>TJU5105</td><td></td></tr>
<tr><td>TL18674</td><td> CANARIS</td></tr>
<tr><td>TL2438 </td><td>PLANTACION_DEL_SOL</td></tr>
<tr><td>TL3407 </td><td>PAMPA_BAJA</td></tr>
<tr><td>TL4699 </td><td>INAGRO</td></tr>
<tr><td>TL5567 </td><td>TRUCK_RETIRO_OLMOS</td></tr>
<tr><td>TL5648 </td><td>MICROCELDA_DEL_SOL</td></tr>
<tr><td>TL5937 </td><td>INCAHUASI</td></tr>
<tr><td>TL5957 </td><td>MOTUPE_JAYANCA</td></tr>
<tr><td>TL5962 </td><td>CERRO_BOLICHE</td></tr>
<tr><td>TL5970 </td><td>PAN_DE_AZUFRE</td></tr>
<tr><td>TL5971 </td><td>OASIS</td></tr>
<tr><td>TL6011 </td><td>PENACHI</td></tr>
<tr><td>TL6015 </td><td>KERGUER</td></tr>
<tr><td>TL6065 </td><td>CAMPAMENTO_OLMOS_IRRIGACION</td></tr>
<tr><td>TL6404 </td><td>ODEBRECHT_OLMOS</td></tr>
<tr><td>TM4053 </td><td>SAN_PABLO_AMAZONAS</td></tr>
<tr><td>TM4059 </td><td>AGUA_DULCE</td></tr>
<tr><td>TM4582 </td><td>YAMBRASBAMBA</td></tr>
<tr><td>TM5319 </td><td>WARAPATURCO</td></tr>
<tr><td>TM5357 </td><td>CAJALLIN</td></tr>
<tr><td>TM5582 </td><td>KUELAP</td></tr>
<tr><td>TM5583 </td><td>MIRAFLORES_AMAZONAS</td></tr>
<tr><td>TM5585 </td><td>CAJARURO</td></tr>
<tr><td>TM5590 </td><td>JAMALCA</td></tr>
<tr><td>TM5599 </td><td>DAGUAS</td></tr>
            </table>
        </div>
        <div class="pagebreak"> </div>
        <div class="tabla_contenedor4">
            <table border=1 >
                <tr><td>TM5607 </td><td>SAN_JERONIMO_AMAZONAS</td></tr>
<tr><td>TM5611 </td><td>JUMBILLA</td></tr>
<tr><td>TM5616 </td><td>VIEJO_LAMUD</td></tr>
<tr><td>TM5619 </td><td>MOLINOPAMPA</td></tr>
<tr><td>TM5620 </td><td>LEVANTO</td></tr>
<tr><td>TM5622 </td><td>NARANJITO</td></tr>
<tr><td>TM5624 </td><td>MASHUYACO</td></tr>
<tr><td>TM5625 </td><td>CHIRIMOTO</td></tr>
<tr><td>TP1217 </td><td>TALANDRACAS</td></tr>
<tr><td>TP18035</td><td> CHAPIPAMPA</td></tr>
<tr><td>TP18037</td><td> HUACAS_ALTO</td></tr>
<tr><td>TP18630</td><td> TAZAJERAS</td></tr>
<tr><td>TP18636</td><td> TAPUL</td></tr>
<tr><td>TP18638</td><td> YAPANGO_BAJO</td></tr>
<tr><td>TP18640</td><td> ARRENDAMIENTOS</td></tr>
<tr><td>TP18642</td><td> HUASANCHE</td></tr>
<tr><td>TP3375 </td><td>TRUCK_CARSOL</td></tr>
<tr><td>TP5013 </td><td>CASINO_COLAN</td></tr>
<tr><td>TP6144 </td><td>BAYOVAR</td></tr>
<tr><td>TP6145 </td><td>CIENEGUILLO</td></tr>
<tr><td>TP6153 </td><td>BUENOS_AIRES</td></tr>
<tr><td>TP6154 </td><td>COLAN</td></tr>
<tr><td>TP6156 </td><td>SECHURA_CARRETERA</td></tr>
<tr><td>TP6163 </td><td>CHOCAN</td></tr>
<tr><td>TP6174 </td><td>SONDORILLO</td></tr>
<tr><td>TP6279 </td><td>ENACE</td></tr>
<tr><td>TP6280 </td><td>LA_BREA</td></tr>
<tr><td>TP6288 </td><td>ODEBRECH_OCCIDENTE</td></tr>
<tr><td>TP6292 </td><td>MORANTE_CHICO</td></tr>
<tr><td>TP6293 </td><td>CRUZ_DE_CANA</td></tr>
<tr><td>TP6348 </td><td>SUYOS</td></tr>
<tr><td>TP6349 </td><td>LANCONES</td></tr>
<tr><td>TP6359 </td><td>LAGUNAS_PIURA</td></tr>
<tr><td>TP6360 </td><td>RAMOS</td></tr>
<tr><td>TP6480 </td><td>SALVIA</td></tr>
<tr><td>TP6539 </td><td>YERBAS_BUENAS</td></tr>
<tr><td>TT0645 </td><td>PUNTA_MERO</td></tr>
<tr><td>TT0996 </td><td>CHERRELIQUE</td></tr>
<tr><td>TT2540 </td><td>TUTUMO</td></tr>
<tr><td>TT5515 </td><td>EL_LECHUGAL</td></tr>
<tr><td>TT6329 </td><td>CASITAS</td></tr>
<tr><td>TT6331 </td><td>MATAPALO</td></tr>
<tr><td>TT6332 </td><td>RICA_PLAYA</td></tr>
            </table>
            <strong class="titulo_pdf"> CUADRO IV
            </strong>
            
        </div>
        <div class="table_contenedor11_final">
            <strong class="titulo_pdf"> LISTADO DE ESTACIONES BASE DADOS DE BAJA DEL<br>
                SERVICIO COMERCIAL DURANTE EL MES DE JULIO DE
                2021.
            </strong>
            <table>
                <tr>
                    <th>NOMBRE ESTACIÓN</th>
                    <th>CODIGO</th>
                    <th>BAJA</th>
                </tr>
                <tr>
                    <td><br></td>
                    <td><br></td>
                    <td><br></td>
                </tr>
            </table>
        </div>
</body>
</html>