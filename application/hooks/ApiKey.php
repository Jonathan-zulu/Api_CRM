<?php
class ApiKey {
    public function check() {
        $CI =& get_instance();
        $apiKey = $CI->input->get_request_header('API-Key');

        if (!$apiKey || $apiKey !== '1954952eff1c76fbe2953b157502754fdbdcaffa') {
            // Respuesta si la API Key es inválida
            $CI->output
                ->set_content_type('application/json')
                ->set_status_header(401) // No autorizado
                ->set_output(json_encode(array('error' => 'API Key no válida')))
                ->_display();
            exit;
        }
    }
}
