## Autentikasi JWT

Untuk mengakses endpoint pada API ini, Anda harus terlebih dahulu mendapatkan token JWT melalui endpoint login. Setelah mendapatkan token, sertakan token tersebut di header setiap request ke endpoint yang membutuhkan autentikasi.

### Langkah-Langkah Autentikasi

1. **Login untuk Mendapatkan Token**

   - **Endpoint**: `POST /auth/login`
   - **Body**:
     ```json
     {
     	"username": "your_username",
     	"password": "your_password"
     }
     ```
   - Jika login berhasil, server akan mengembalikan token JWT yang perlu disertakan di setiap request.
   - **Contoh Response**:
     ```json
     {
     	"status": "success",
     	"message": "Authentication successful",
     	"token": "____your.jwt.token____"
     }
     ```

2. **Gunakan Token pada Setiap Request**
   - Sertakan token JWT pada header `Authorization` di setiap request endpoint yang membutuhkan autentikasi.
   - **Header**:
     ```
     Authorization: Bearer your_jwt_token
     ```

## Daftar Endpoint CRUD Mahasiswa

Setiap endpoint CRUD berikut ini memerlukan autentikasi JWT.

- **GET /students**

  - Mengambil daftar semua mahasiswa.
  - **Headers**:
    - `Authorization: Bearer your_jwt_token`

- **GET /student/{nim}**

  - Mengambil data mahasiswa berdasarkan `nim`.
  - **Headers**:
    - `Authorization: Bearer your_jwt_token`

- **PUT /students/{nim}**

  - Memperbarui data mahasiswa berdasarkan `nim`.
  - **Headers**:
    - `Authorization: Bearer your_jwt_token`
  - **Body**:
    ```json
    {
    	"nama": "MUHAMMAD JAJA ROYANA",
    	"kelas": "12.6A.15",
    	"asal_kampus": "15"
    }
    ```

- **DELETE /student/{nim}**
  - Menghapus data mahasiswa berdasarkan `nim`.
  - **Headers**:
    - `Authorization: Bearer your_jwt_token`

## Cara Penggunaan

1. **Login dan Dapatkan Token**

   - Lakukan request `POST /auth/login` dengan mengirimkan `username` dan `password`.
   - Simpan token yang diterima untuk digunakan pada header `Authorization` di setiap request berikutnya.

2. **Gunakan Token untuk Mengakses Endpoint**
   - Tambahkan header `Authorization` dengan nilai `Bearer your_jwt_token` pada setiap request endpoint yang mengelola data mahasiswa.

### Contoh Request dengan Token

- **GET /students**
  - **Headers**:
    ```json
    {
    	"Authorization": "Bearer your_jwt_token"
    }
    ```
  - Contoh Response:
    ```json
    {
    	"status": "success",
    	"message": "Data retrieved successfully",
    	"data": {
    		"pagination": {
    			"current_page": 1,
    			"per_page": 5,
    			"total_data": 661,
    			"total_pages": 67
    		},
    		"items": [
    			{
    				"nim": "12205555",
    				"nama": "ROBI",
    				"kelas": "99.9X.99",
    				"asal_kampus": "99"
    			},
    			{
    				"nim": "12206666",
    				"nama": "FINA ",
    				"kelas": "99.9X.99",
    				"asal_kampus": "99"
    			},
    			{
    				"nim": "12207777",
    				"nama": "ALPITO ",
    				"kelas": "99.9X.99",
    				"asal_kampus": "99"
    			},
    			{
    				"nim": "12208888",
    				"nama": "WINDA",
    				"kelas": "99.9X.99",
    				"asal_kampus": "99"
    			},
    			{
    				"nim": "12209999",
    				"nama": "SULTON",
    				"kelas": "99.9X.99",
    				"asal_kampus": "99"
    			}
    		]
    	}
    }
    ```

## Error Handling

- Jika token JWT tidak valid atau tidak ada, server akan mengembalikan respons dengan status **401 Unauthorized**.
- Jika `nim` tidak ditemukan, maka server akan mengembalikan respons dengan status **404 Not Found** dan pesan kesalahan.
- Jika terjadi kesalahan dalam validasi input, server akan mengembalikan respons dengan status **400 Bad Request**.
