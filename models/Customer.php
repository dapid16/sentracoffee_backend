<?php
class Customer {
    // Koneksi database dan nama tabel
    private $conn;
    private $table_name = "customers";

    // Atribut objek
    public $id_customer;
    public $nama;
    public $email;
    public $password; // Sesuai kesepakatan tidak di-hash
    public $no_hp;

    // Konstruktor
    public function __construct($db) {
        $this->conn = $db;
    }

    // Fungsi untuk membuat customer baru (registrasi)
    public function create() {
        // Query untuk insert data
        $query = "INSERT INTO " . $this->table_name . " SET nama=:nama, email=:email, password=:password, no_hp=:no_hp";

        // Persiapkan statement
        $stmt = $this->conn->prepare($query);

        // Bersihkan dan validasi data
        $this->nama = htmlspecialchars(strip_tags($this->nama));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->no_hp = htmlspecialchars(strip_tags($this->no_hp));

        // Bind parameter
        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":no_hp", $this->no_hp);

        // Eksekusi query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Fungsi untuk membaca semua customer
    public function read() {
        $query = "SELECT id_customer, nama, email, no_hp FROM " . $this->table_name . " ORDER BY nama ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Fungsi untuk membaca satu customer berdasarkan ID
    public function readOne() {
        $query = "SELECT id_customer, nama, email, no_hp FROM " . $this->table_name . " WHERE id_customer = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_customer);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->nama = $row['nama'];
            $this->email = $row['email'];
            $this->no_hp = $row['no_hp'];
            // Tidak perlu set id_customer di sini, karena sudah ada di properti
            return true;
        }
        return false;
    }

    // Fungsi untuk mencari customer berdasarkan email (untuk login/cek duplikasi)
    public function findByEmail() {
        // --- Debugging: Log email yang dicari ---
        error_log("DEBUG Customer->findByEmail: Searching for email: " . $this->email);

        $query = "SELECT id_customer, nama, email, password, no_hp FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // --- Debugging: Log hasil fetch ---
        if ($row) {
            error_log("DEBUG Customer->findByEmail: Row found: " . print_r($row, true));
        } else {
            error_log("DEBUG Customer->findByEmail: No row found for email: " . $this->email);
        }
        // --- Akhir Debugging ---

        if ($row) {
            // Jika customer DITEMUKAN, baru set propertinya
            $this->id_customer = $row['id_customer'];
            $this->nama = $row['nama'];
            $this->email = $row['email'];
            $this->password = $row['password']; // Ambil password (tidak di-hash)
            $this->no_hp = $row['no_hp'];
            return true; // Ditemukan
        }
        // Jika tidak ditemukan, set id_customer ke null (penting untuk indikasi 'tidak ditemukan')
        $this->id_customer = null;
        // Tidak mereset properti lain di sini agar tidak mempengaruhi data dari create.php
        return false; // Tidak ditemukan
    }

    // Fungsi untuk mengupdate customer
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nama=:nama, email=:email, no_hp=:no_hp WHERE id_customer = :id_customer";
        $stmt = $this->conn->prepare($query);

        // Sanitasi input
        $this->nama = htmlspecialchars(strip_tags($this->nama));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->no_hp = htmlspecialchars(strip_tags($this->no_hp));
        $this->id_customer = htmlspecialchars(strip_tags($this->id_customer));

        // Bind parameter
        $stmt->bindParam(':nama', $this->nama);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':no_hp', $this->no_hp);
        $stmt->bindParam(':id_customer', $this->id_customer);

        // Eksekusi query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Fungsi untuk menghapus customer
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_customer = ?";
        $stmt = $this->conn->prepare($query);

        // Sanitasi ID
        $this->id_customer = htmlspecialchars(strip_tags($this->id_customer));
        $stmt->bindParam(1, $this->id_customer);

        // Eksekusi query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>