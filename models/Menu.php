<?php
class Menu {
    private $conn;
    private $table_name = "menus"; // Sesuai dengan SQL dump lo, nama tabelnya 'menus' (lowercase)

    // --- PERBAIKAN #1: Tambahkan properti untuk gambar ---
    public $id_menu;
    public $nama_menu;
    public $kategori;
    public  $harga;
    public $is_available;
    public $gambar; // <<< TAMBAHKAN INI

    // Konstruktor dengan koneksi DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // --- PERBAIKAN #2: Tambahkan 'gambar' ke query SELECT ---
    // Fungsi untuk membaca semua menu
    public function read() {
        // <<< UBAH QUERY INI
        $query = "SELECT id_menu, nama_menu, kategori, harga, is_available, gambar FROM " . $this->table_name . " ORDER BY nama_menu ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // --- PERBAIKAN #3: Tambahkan 'gambar' ke query INSERT ---
    // Fungsi untuk membuat menu baru
    public function create() {
        // <<< UBAH QUERY INI
        $query = "INSERT INTO " . $this->table_name . " SET nama_menu=:nama_menu, kategori=:kategori, harga=:harga, is_available=:is_available, gambar=:gambar";

        $stmt = $this->conn->prepare($query);

        // Bersihkan data
        $this->nama_menu = htmlspecialchars(strip_tags($this->nama_menu));
        $this->kategori = htmlspecialchars(strip_tags($this->kategori));
        $this->harga = htmlspecialchars(strip_tags($this->harga));
        $this->is_available = htmlspecialchars(strip_tags($this->is_available));
        $this->gambar = htmlspecialchars(strip_tags($this->gambar)); // <<< TAMBAHKAN INI

        // Bind parameter
        $stmt->bindParam(":nama_menu", $this->nama_menu);
        $stmt->bindParam(":kategori", $this->kategori);
        $stmt->bindParam(":harga", $this->harga);
        $stmt->bindParam(":is_available", $this->is_available);
        $stmt->bindParam(":gambar", $this->gambar); // <<< TAMBAHKAN INI

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // --- PERBAIKAN #4: Tambahkan 'gambar' ke query SELECT (untuk readOne) ---
    public function readOne() {
        // <<< UBAH QUERY INI
        $query = "SELECT id_menu, nama_menu, kategori, harga, is_available, gambar FROM " . $this->table_name . " WHERE id_menu = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_menu);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->nama_menu = $row['nama_menu'];
            $this->kategori = $row['kategori'];
            $this->harga = $row['harga'];
            $this->is_available = $row['is_available'];
            $this->gambar = $row['gambar']; // <<< TAMBAHKAN INI
            return true;
        }
        return false;
    }

    // --- PERBAIKAN #5: Tambahkan 'gambar' ke query UPDATE ---
    public function update() {
        // <<< UBAH QUERY INI
        $query = "UPDATE " . $this->table_name . " SET nama_menu=:nama_menu, kategori=:kategori, harga=:harga, is_available=:is_available, gambar=:gambar WHERE id_menu = :id_menu";
        
        $stmt = $this->conn->prepare($query);

        // Sanitasi input
        $this->nama_menu = htmlspecialchars(strip_tags($this->nama_menu));
        $this->kategori = htmlspecialchars(strip_tags($this->kategori));
        $this->harga = htmlspecialchars(strip_tags($this->harga));
        $this->is_available = htmlspecialchars(strip_tags($this->is_available));
        $this->gambar = htmlspecialchars(strip_tags($this->gambar)); // <<< TAMBAHKAN INI
        $this->id_menu = htmlspecialchars(strip_tags($this->id_menu));

        // Bind parameter
        $stmt->bindParam(':nama_menu', $this->nama_menu);
        $stmt->bindParam(':kategori', $this->kategori);
        $stmt->bindParam(':harga', $this->harga);
        $stmt->bindParam(':is_available', $this->is_available);
        $stmt->bindParam(':gambar', $this->gambar); // <<< TAMBAHKAN INI
        $stmt->bindParam(':id_menu', $this->id_menu);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Fungsi delete tidak perlu diubah
    public function delete() {
        // ... (biarkan seperti semula) ...
    }
}
?>