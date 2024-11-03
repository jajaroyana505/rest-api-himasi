<?php
// application/helpers/jwt_helper.php

defined('BASEPATH') or exit('No direct script access allowed');

use Firebase\JWT\JWT;

if (!function_exists('jwt_authorization')) {
    function jwt_authorization()
    {
        $headers = $_SERVER['HTTP_AUTHORIZATION'];
        $token = str_replace('Bearer ', '', $headers);


        if ($token) {
            $decoded = verify_jwt($token);

            if ($decoded) {
                // print_r($decoded);
            } else {
                header('Content-Type: application/json');
                http_response_code(401);
                echo json_encode(
                    array(
                        'status' => "fail",
                        'code' => 401,
                        'message' => 'Unauthorized',
                    )
                );
                exit;
            }
        } else {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode(
                array(
                    'status' => "fail",
                    'code' => 401,
                    'message' => 'Unauthorized',
                )
            );
            exit;
        }
    }
}



if (!function_exists('generate_jwt')) {
    function generate_jwt($payload)
    {
        $CI = &get_instance();
        $key = $CI->config->item('jwt_key');
        $algorithm = $CI->config->item('jwt_algorithm');

        return JWT::encode($payload, $key, $algorithm);
    }
}

if (!function_exists('verify_jwt')) {
    function verify_jwt($token)
    {
        $CI = &get_instance();
        $key = $CI->config->item('jwt_key');
        $algorithm = $CI->config->item('jwt_algorithm');

        try {
            $decoded = JWT::decode($token, $key, array($algorithm));
            return (array) $decoded;
        } catch (Exception $e) {
            log_message('error', 'JWT Error: ' . $e->getMessage());
            return false; // Mengembalikan null jika verifikasi gagal
        }
    }
}
