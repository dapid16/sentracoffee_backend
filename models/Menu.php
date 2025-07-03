<?php
class Menu {
    private $conn;
    private $table_name = "menus"; 
    
    public $id_menu;
    public $nama_menu;
    public $kategori;
    public  $harga;
    public $is_available;
    public $gambar; 

    
    public function __construct($db) {
        $this->conn = $db;
    }

    
    public function read() {
        $query = "SELECT id_menu, nama_menu, kategori, harga, is_available, gambar 
                  FROM " . $this->table_name . " 
                  WHERE is_available = 1 
                  ORDER BY nama_menu ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

   
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nama_menu=:nama_menu, kategori=:kategori, harga=:harga, is_available=:is_available, gambar=:gambar";

        $stmt = $this->conn->prepare($query);

       
        $this->nama_menu = htmlspecialchars(strip_tags($this->nama_menu));
        $this->kategori = htmlspecialchars(strip_tags($this->kategori));
        $this->harga = htmlspecialchars(strip_tags($this->harga));
        $this->is_available = htmlspecialchars(strip_tags($this->is_available));
        $this->gambar = htmlspecialchars(strip_tags($this->gambar)); 

      
        $stmt->bindParam(":nama_menu", $this->nama_menu);
        $stmt->bindParam(":kategori", $this->kategori);
        $stmt->bindParam(":harga", $this->harga);
        $stmt->bindParam(":is_available", $this->is_available);
        $stmt->bindParam(":gambar", $this->gambar); 

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    
    public function readOne() {
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
            $this->gambar = $row['gambar']; 
            return true;
        }
        return false;
    }

  
    public function update() {
        
        $query = "UPDATE " . $this->table_name . " 
                  SET 
                    nama_menu = :nama_menu, 
                    kategori = :kategori, 
                    harga = :harga, 
                    is_available = :is_available, 
                    gambar = :gambar 
                  WHERE 
                    id_menu = :id_menu";
        
        $stmt = $this->conn->prepare($query);

        
        $this->nama_menu = htmlspecialchars(strip_tags($this->nama_menu));
        $this->kategori = htmlspecialchars(strip_tags($this->kategori));
        $this->harga = htmlspecialchars(strip_tags($this->harga));
        $this->is_available = htmlspecialchars(strip_tags($this->is_available));
        $this->gambar = htmlspecialchars(strip_tags($this->gambar)); 
        $this->id_menu = htmlspecialchars(strip_tags($this->id_menu));

       
        $stmt->bindParam(':nama_menu', $this->nama_menu);
        $stmt->bindParam(':kategori', $this->kategori);
        $stmt->bindParam(':harga', $this->harga);
        $stmt->bindParam(':is_available', $this->is_available);
        $stmt->bindParam(':gambar', $this->gambar); 
        $stmt->bindParam(':id_menu', $this->id_menu);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    
    public function delete() {
        
        $query = "UPDATE " . $this->table_name . " SET is_available = 0 WHERE id_menu = :id_menu";
    
        $stmt = $this->conn->prepare($query);
    
       
        $this->id_menu = htmlspecialchars(strip_tags($this->id_menu));
    
      
        $stmt->bindParam(':id_menu', $this->id_menu);
    
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>