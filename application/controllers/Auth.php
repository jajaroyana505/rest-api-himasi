<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function login()
    {
        $input_data = json_decode($this->input->raw_input_stream, true);

        if (is_null($input_data)) {
            $response = [
                'status' => 'error',
                'message' => 'Invalid JSON data or empty request body'
            ];
            $this->output->set_status_header(400);
        } else {
            $this->form_validation->set_data($input_data);
            $this->form_validation->set_rules('username', 'Username', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');



            $username = $input_data['username'];
            $password = $input_data['password'];

            if ($this->form_validation->run() == TRUE) {
                if ($username == 'admin' && $password == 'password') {
                    $payload = [
                        'user_id' => 1,
                        'username' => 'jajaroyana',
                        'email' => 'example@example.com',
                        'iat' => time(),
                        'exp' => time() + 3600 // Token berlaku 1 jam
                    ];
                    $token = generate_jwt($payload);


                    $response = [
                        'status' => 'success',
                        'message' => 'Authentication successful',
                        'token' => $token
                    ];
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'Invalid credentials'
                    ];
                }
            } else {
                $this->output->set_status_header(400);
                $response = [
                    'status' => 'error',
                    'message' => 'Validation errors',
                    'errors' => $this->form_validation->error_array()
                ];
            }
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
