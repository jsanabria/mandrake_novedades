<?php

function obtenerTasaBCV() {
    // Configuración
    $api_url = "https://bcv-api.rafnixg.dev/rates/latest";
    $bcv_url = "https://www.bcv.org.ve/";
    $timeout = 5; // Segundos máximos de espera

    // --- PASO 1: Intentar con la API de Terceros ---
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); // Límite de tiempo
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200 && $response) {
        $data = json_decode($response, true);
        if (isset($data['USD']['value'])) {
            return [
                "tasa" => str_replace(',', '.', $data['USD']['value']),
                "fuente" => "API Comunitaria",
                "fecha" => $data['USD']['date'] ?? date('Y-m-d')
            ];
        }
    }

    // --- PASO 2: Fallback al Scraping Directo del BCV ---
    // Si llegamos aquí es porque la API falló o tardó mucho
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $bcv_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); // Timeout estricto para el BCV
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/110.0.0.0 Safari/537.36');

    $html = curl_exec($ch);
    $http_code_bcv = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code_bcv === 200 && $html) {
        // Buscamos el valor del Dólar en el HTML
        preg_match('/<div id="dolar".*?<strong>\s*(.*?)\s*<\/strong>/s', $html, $matches);
        if (isset($matches[1])) {
            return [
                "tasa" => str_replace(',', '.', trim($matches[1])),
                "fuente" => "BCV Directo (Scraping)",
                "fecha" => date('Y-m-d')
            ];
        }
    }

    // --- PASO 3: Error definitivo ---
    return false; 
}

// --- USO DEL SCRIPT ---
$resultado = obtenerTasaBCV();

if ($resultado) {
    echo "### Tasa Encontrada ###\n";
    echo "Valor: " . $resultado['tasa'] . " Bs.\n";
    echo "Fuente: " . $resultado['fuente'] . "\n";
} else {
    echo "Error: No se pudo obtener la tasa de ninguna fuente (Timeouts agotados).";
}