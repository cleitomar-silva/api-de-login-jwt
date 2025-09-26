<?php

require_once __DIR__ . '/../Libraries/php-jwt-main/src/JWT.php';
require_once __DIR__ . '/../Libraries/php-jwt-main/src/Key.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {
    public static function handle() {
        // Pega todos os headers
        $headers = getallheaders();

        // Verifica se existe o header x-access-token
        if (!isset($headers['x-access-token'])) {
            http_response_code(401);
            echo json_encode(["success" => false, "msg" => "Acesso negado: token não fornecido"]);
            exit;
        }

        $token = $headers['x-access-token'];

        try {
            // Decodifica o JWT usando a secret
            $decoded = JWT::decode($token, new Key(SECRET, 'HS256'));

            // Opcional: salva os dados do usuário na sessão ou global
            $_SESSION['user'] = $decoded;

        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(["success" => false, "msg" => "Token inválido: " . $e->getMessage()]);
            exit;
        }
    }
}
