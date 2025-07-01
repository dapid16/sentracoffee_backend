<?php
class Customer {
    // Koneksi database dan nama tabel
    private $conn;
    private $table_name = "customers";

    // Atribut objek
    public $id_customer;
    public $nama;
    public $email;
    public $password;
    public $no_hp;
    public $points; // <<< DITAMBAHKAN

    // Konstruktor
    public function __construct($db) {
        $this->conn = $db;
    }

    // Fungsi untuk membuat customer baru (registrasi)
    // Poin otomatis 0 karena default di database
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nama=:nama, email=:email, password=:password, no_hp=:no_hp";
        $stmt = $this->conn->prepare($query);

        // Bersihkan data
        $this->nama = htmlspecialchars(strip_tags($this->nama));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->no_hp = htmlspecialchars(strip_tags($this->no_hp));

        // Bind parameter
        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":no_hp", $this->no_hp);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Fungsi untuk membaca semua customer
    public function read() {
        // <<< QUERY DIUBAH untuk mengambil 'points'
        $query = "SELECT id_customer, nama, email, no_hp, points FROM " . $this->table_name . " ORDER BY nama ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Fungsi untuk membaca satu customer berdasarkan ID
    public function readOne() {
        // <<< QUERY DIUBAH untuk mengambil 'points'
        $query = "SELECT id_customer, nama, email, no_hp, points FROM " . $this->table_name . " WHERE id_customer = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_customer);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->nama = $row['nama'];
            $this->email = $row['email'];
            $this->no_hp = $row['no_hp'];
            $this->points = $row['points']; // <<< DITAMBAHKAN
            return true;
        }
        return false;
    }

    // Fungsi untuk mencari customer berdasarkan email
    public function findByEmail() {
        // <<< QUERY DIUBAH untuk mengambil 'points'
        $query = "SELECT id_customer, nama, email, password, no_hp, points FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id_customer = $row['id_customer'];
            $this->nama = $row['nama'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            $this->no_hp = $row['no_hp'];
            $this->points = $row['points']; // <<< DITAMBAHKAN
            return true;
        }
        return false;
    }

    // Fungsi untuk mengupdate customer
    public function update() {
        // Fungsi update tidak perlu mengubah poin secara langsung, jadi biarkan
        $query = "UPDATE " . $this->table_name . " SET nama=:nama, email=:email, no_hp=:no_hp WHERE id_customer = :id_customer";
        $stmt = $this->conn->prepare($query);

        $this->nama = htmlspecialchars(strip_tags($this->nama));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->no_hp = htmlspecialchars(strip_tags($this->no_hp));
        $this->id_customer = htmlspecialchars(strip_tags($this->id_customer));

        $stmt->bindParam(':nama', $this->nama);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':no_hp', $this->no_hp);
        $stmt->bindParam(':id_customer', $this->id_customer);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Fungsi untuk menghapus customer
    public function delete() {
        // Peringatan: Menghapus customer bisa berefek ke tabel lain (foreign key)
        $query = "DELETE FROM " . $this->table_name . " WHERE id_customer = ?";
        $stmt = $this->conn->prepare($query);

        $this->id_customer = htmlspecialchars(strip_tags($this->id_customer));
        $stmt->bindParam(1, $this->id_customer);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>