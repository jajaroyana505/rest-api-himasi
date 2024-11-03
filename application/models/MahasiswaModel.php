<?php
class MahasiswaModel extends CI_Model
{
    protected $table = 'mahasiswa';
    //Simpan Data Siswa
    public function get_data($limit, $offset)
    {
        // Mendapatkan data dengan limit dan offset untuk pagination
        return $this->db->get('mahasiswa', $limit, $offset)->result();
    }

    public function get_by_nim($nim)
    {
        return $this->db->get_where($this->table, ['nim' => $nim]);
    }

    public function get_total_data()
    {
        // Menghitung total data di database
        return $this->db->count_all('mahasiswa');
    }

    public function update($nim, $data)
    {
        $this->db->where('nim', $nim);
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    public function delete($nim)
    {
        $this->db->delete($this->table, ['nim' => $nim]);
        return $this->db->affected_rows();
    }
}
