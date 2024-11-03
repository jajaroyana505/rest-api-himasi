<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function login()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // Contoh autentikasi sederhana
        if ($username == 'admin' && $password == 'password') {
            $payload = [
                'user_id' => 1,
                'username' => 'jajaroyana',
                'email' => 'example@example.com',
                'iat' => time(),
                'exp' => time() + 3600 // Token berlaku 1 jam
            ];
            $token = generate_jwt($payload);

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['token' => $token]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Invalid credentials']));
        }
    }
}
