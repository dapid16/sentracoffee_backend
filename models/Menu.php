<?php
class Menu {
    private $conn;
    private $table_name = "Menus"; // Nama tabel di database

    // Atribut objek
    public $id_menu;
    public $nama_menu;
    public $kategori;
    public $harga;
    public $is_available;

    // Konstruktor dengan koneksi DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Fungsi untuk membaca semua menu
    public function read() {
        $query = "SELECT id_menu, nama_menu, kategori, harga, is_available FROM " . $this->table_name . " ORDER BY nama_menu ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Fungsi untuk membuat menu baru
    public function create() {
        // Query untuk insert data
        $query = "INSERT INTO " . $this->table_name . " SET nama_menu=:nama_menu, kategori=:kategori, harga=:harga, is_available=:is_available";

        // Persiapkan statement
        $stmt = $this->conn->prepare($query);

        // Bersihkan data (sanitasi input)
        $this->nama_menu = htmlspecialchars(strip_tags($this->nama_menu));
        $this->kategori = htmlspecialchars(strip_tags($this->kategori));
        $this->harga = htmlspecialchars(strip_tags($this->harga));
        $this->is_available = htmlspecialchars(strip_tags($this->is_available));

        // Bind parameter
        $stmt->bindParam(":nama_menu", $this->nama_menu);
        $stmt->bindParam(":kategori", $this->kategori);
        $stmt->bindParam(":harga", $this->harga);
        $stmt->bindParam(":is_available", $this->is_available);

        // Eksekusi query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Fungsi untuk membaca satu menu berdasarkan ID
    public function readOne() {
        $query = "SELECT id_menu, nama_menu, kategori, harga, is_available FROM " . $this->table_name . " WHERE id_menu = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_menu); // Bind ID
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->nama_menu = $row['nama_menu'];
            $this->kategori = $row['kategori'];
            $this->harga = $row['harga'];
            $this->is_available = $row['is_available'];
            return true;
        }
        return false;
    }

    // Fungsi untuk mengupdate menu
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nama_menu=:nama_menu, kategori=:kategori, harga=:harga, is_available=:is_available WHERE id_menu = :id_menu";
        $stmt = $this->conn->prepare($query);

        // Sanitasi input
        $this->nama_menu = htmlspecialchars(strip_tags($this->nama_menu));
        $this->kategori = htmlspecialchars(strip_tags($this->kategori));
        $this->harga = htmlspecialchars(strip_tags($this->harga));
        $this->is_available = htmlspecialchars(strip_tags($this->is_available));
        $this->id_menu = htmlspecialchars(strip_tags($this->id_menu)); // Pastikan ID juga disanitasi

        // Bind parameter
        $stmt->bindParam(':nama_menu', $this->nama_menu);
        $stmt->bindParam(':kategori', $this->kategori);
        $stmt->bindParam(':harga', $this->harga);
        $stmt->bindParam(':is_available', $this->is_available);
        $stmt->bindParam(':id_menu', $this->id_menu);

        // Eksekusi query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Fungsi untuk menghapus menu
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_menu = ?";
        $stmt = $this->conn->prepare($query);

        // Sanitasi ID
        $this->id_menu = htmlspecialchars(strip_tags($this->id_menu));
        $stmt->bindParam(1, $this->id_menu);

        // Eksekusi query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>