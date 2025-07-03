<?php
class Staff {
    private $conn;
    private $table_name = "staffs";

    public $id_staff;
    public $id_owner;
    public $nama_staff;
    public $role;
    public $email;
    public F$password;
    public $no_hp;

    public function __construct($db) {
        $this->conn = $db;
    }

    // ... (fungsi read() dan create() yang sudah ada, biarkan saja) ...

    // <<< TAMBAHKAN FUNGSI BARU INI DI DALAM CLASS Staff >>>
    public function delete() {
        // Query untuk menghapus record berdasarkan id_staff
        $query = "DELETE FROM " . $this->table_name . " WHERE id_staff = :id_staff";
        
        $stmt = $this->conn->prepare($query);
        
        // Bersihkan data
        $this->id_staff = htmlspecialchars(strip_tags($this->id_staff));
        
        // Bind ID
        $stmt->bindParam(':id_staff', $this->id_staff);
        
        // Eksekusi query
        if($stmt->execute()) {
            // Jika berhasil, cek apakah ada baris yang terpengaruh
            if($stmt->rowCount() > 0){
                return true;
            }
        }
        
        return false;
    }
}
?>