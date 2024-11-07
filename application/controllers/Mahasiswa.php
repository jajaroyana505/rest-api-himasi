<?php
class Mahasiswa extends CI_Controller
{
    protected $nim;

    public function __construct()
    {
        parent::__construct();
        //load model
        $this->load->model('MahasiswaModel');

        $this->nim = $this->uri->segment('2');

        // Middleware untuk memeriksa token
        jwt_authorization();
    }


    public function index()
    {
        $method = $this->input->method();

        switch ($method) {
            case 'get':
                return $this->read();
                break;
            case 'post':
                return $this->create();
                break;
            case 'put':
                return $this->update($this->nim);
                break;
            case 'delete':
                return $this->delete($this->nim);
                break;
        }
    }

    private function read()
    {
        if ($this->nim != null) {
            return $this->show($this->nim);
        }
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;

        $offset = ($page - 1) * $limit;
        $data = $this->MahasiswaModel->get_data($limit, $offset);
        $total_data = $this->MahasiswaModel->get_total_data();
        // Menghitung jumlah halaman
        $total_pages = ceil($total_data / $limit);

        $response = [
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => [
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total_data' => $total_data,
                    'total_pages' => $total_pages,
                ],
                'items' => $data
            ]
        ];

        // Mengirimkan response JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    private function create()
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
            $this->form_validation->set_rules('nim', 'NIM', 'required|is_unique[mahasiswa.nim]');
            $this->form_validation->set_rules('nama', 'Nama', 'required');
            $this->form_validation->set_rules('kelas', 'Kelas', 'required');
            $this->form_validation->set_rules('asal_kampus', 'Asal Kampus', 'required');

            if ($this->form_validation->run() == TRUE) {
                try {
                    $result = $this->db->insert('mahasiswa', $input_data);

                    if ($result) {
                        $this->output->set_status_header(201); // Created
                        $response = [
                            'status' => 'success',
                            'message' => 'Data created successfully',
                            'data' => $input_data
                        ];
                    } else {
                        throw new Exception("Error database");
                    }
                } catch (Exception $e) {
                    $this->output->set_status_header(500);
                    $response = [
                        'status' => 'error',
                        'message' => 'Failed to create data',
                        'error' => $e->getMessage()
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

    private function update($nim)
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
            $this->form_validation->set_rules('nama', 'Nama', 'required');
            $this->form_validation->set_rules('kelas', 'Kelas', 'required');
            $this->form_validation->set_rules('asal_kampus', 'Asal Kampus', 'required');
            if ($this->form_validation->run() == TRUE) {
                try {
                    if ($nim == null) {
                        $this->output->set_status_header(400);
                        throw new Exception("Nim empty");
                    }

                    $result = $this->MahasiswaModel->update($nim, $input_data);

                    if ($result) {
                        $this->output->set_status_header(200);
                        $response = [
                            'status' => 'success',
                            'message' => 'Data updated successfully',
                            'data' => $input_data
                        ];
                    } else {
                        $this->output->set_status_header(200);
                        $response = [
                            'status' => 'error',
                            'message' => 'Failed to update data',
                        ];
                    }
                } catch (Exception $e) {
                    $response = [
                        'status' => 'error',
                        'message' => $e->getMessage(),
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

    private function show($nim)
    {
        try {
            $data = $this->MahasiswaModel->get_by_nim($nim)->row();
            if (empty($data)) {
                $this->output->set_status_header(404);
                throw new Exception("Data not found");
            }
            $response = [
                'status' => 'success',
                'message' => 'Data retrieved successfully',
                'data' => $data
            ];
        } catch (Exception $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    private function delete($nim)
    {
        try {
            if ($nim == null) {
                $this->output->set_status_header(400);
                throw new Exception("Nim empty");
            }

            $result = $this->MahasiswaModel->delete($nim);
            if ($result) {
                $this->output->set_status_header(200);
                $response = [
                    'status' => 'success',
                    'message' => 'Data deleted successfully',
                ];
            } else {
                $this->output->set_status_header(404);
                throw new Exception("Failed to delete data");
            }
        } catch (Exception $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage(),

            ];
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
