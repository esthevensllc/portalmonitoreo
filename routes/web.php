<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('dashboard');
});

Route::get('/test', function () {
    return env('CAS_SESSION_NAME');
});


Route::get('/testmap', function () {
    $url = 'https://gpservices.analytics.pe/GeoPointPlatformService/BASecurityMethods/api/TokenFromApiKeypackage';
        // URL de destino
    //$url = 'https://public-api/api/TokenFromApiKeypackage';
    $header = "Authorization: Bearer " . env("GEOS_TOKEN");

    // Configuración del proxy
    $proxy = 'http://claro-proxy:80';

    // Inicializa cURL
    $curl = curl_init();

    // Establece la URL y otras opciones necesarias
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [$header]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, ""); // Enviar un cuerpo de solicitud vacío

    // Configura el proxy
    curl_setopt($curl, CURLOPT_PROXY, $proxy);

    // Si el proxy requiere autenticación
    // curl_setopt($curl, CURLOPT_PROXYUSERPWD, "$username:$password");

    // Realiza la solicitud y obtén la respuesta
    $response = curl_exec($curl);

    // Manejo de errores
    if ($response === false) {
        $error_msg = curl_error($curl);
        curl_close($curl);
        throw new Exception("cURL Error: $error_msg");
    }

    // Cierra la conexión cURL
    curl_close($curl);

    // Decodificar la respuesta JSON
    $response = json_decode($response);

    // Verificar que la respuesta JSON se decodificó correctamente
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("JSON Decode Error: " . json_last_error_msg());
    }

    // Verificar que el token esté presente en la respuesta
    if (!isset($response->tokenpk)) {
        throw new Exception("Token not found in response.");
    }

    return $response->tokenpk;

    // return [$array];
});

Route::get('/sql', function () {
    $query = "SELECT * FROM
    (SELECT IDLOGALARM,CELLNAME,ALARMA,
                TO_CHAR(FECHAINICIO,'YYYY-MM-DD HH24:MI:SS')FECHA_INICIO,
                TO_CHAR(FECHAFIN,'YYYY-MM-DD HH24:MI:SS')FECHA_FIN,
                SEVERIDAD,
                CODIGOSITE,
                NOMBRESITE,
                DEPARTAMENTO
    ,
            ROWNUM fila
            FROM reg_oss700_alarmas_id
            WHERE FECHAINICIO>TRUNC(SYSDATE)-15
                AND SEVERIDAD in ('Major','Critical')
    )TBLN";

    $query2 = " SELECT LOG_SERIAL_NUMBER,OBJECT_IDENTITY_NAME,OBJECT_IDENTITY,OBJECT_INSTANCE_TYPE,NETYPE,ALARM_SOURCE,ALARM_ID,ALARMNAME,TO_CHAR(OCCURRENCETIME,('DD/MM/YYYY HH24:MI:SS'))OCCURRENCETIME,TO_CHAR(CLEARANCETIME,('DD/MM/YYYY HH24:MI:SS'))CLEARANCETIME,SEVERITY,TYPE,ENODEB_FUNCTION_NAME,CELLNAME,ROWNUM FILA FROM PSO_0DATA_07_15 A   WHERE D IS NULL";

    $queries = DB::select(DB::raw("select t3.type_description from pso_type t
    inner join pso_type t1 on t1.type_father = t.id_type and t1.type_name = '15'
    inner join pso_type t2 on t2.type_father = t1.id_type and t2.type_name = '8'
    inner join pso_type t3 on t3.type_father = t2.id_type --and t3.type_name = '8'
    where t.type_father = '232'"));
    $response = [];
    foreach($queries as $query){
        $response[] = get_query_parts($query->type_description);
    }

    return response()->json($response);
});

Route::get("decode", function(){
    return urldecode("http://172.19.10.171/portalmonitoreov2/public/alarmas/23?url=alarmas%2F23&occurrencetime=%7B%22from%22%3A%222022-12-01+00%3A00%3A00+%22%2C%22to%22%3A%222022-12-05+23%3A59%3A59%22%7D#");
});
