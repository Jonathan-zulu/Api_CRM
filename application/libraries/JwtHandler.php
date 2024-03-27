<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHandler {
    private $key;
    private $algorithm;
    private $CI;

    public function __construct() {
        $this->CI =& get_instance();
        $this->key = '1_/it-4n"0Ec';
        $this->algorithm = 'HS256';
    }

    public function generateToken($data) {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600;  // token válido por 1 hora
        $payload = [
            'iss' => 'the_issuer', // Identificador del emisor
            'aud' => 'the_audience', // Identificador del destinatario
            'iat' => $issuedAt, // Tiempo de emisión
            'exp' => $expirationTime, // Tiempo de expiración
            'userData' => $data
        ];

        return JWT::encode($payload, $this->key, $this->algorithm);
    }

    public function validateToken($token) {
        try {
            $decoded = JWT::decode($token, new Key($this->key, $this->algorithm));
            return ['valid' => true, 'data' => (array) $decoded];
        } catch (Exception $e) {
            return ['valid' => false, 'message' => $e->getMessage()];
        }
    }
}
