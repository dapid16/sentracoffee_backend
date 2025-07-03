<?php
class Staff {
    private $conn;
    private $table_name = "staffs";

    public $id_staff;
    public $id_owner;
    public $nama_staff;
    public $role;
    public $email;
    public $password;
    public $no_hp;

    public function __construct($db) {
        $this->conn = $db;
    }

    
    public function delete() {
       
        $query = "DELETE FROM " . $this->table_name . " WHERE id_staff = :id_staff";
        
        $stmt = $this->conn->prepare($query);
        
       
        $this->id_staff = htmlspecialchars(strip_tags($this->id_staff));
        
        
        $stmt->bindParam(':id_staff', $this->id_staff);
        
        
        if($stmt->execute()) {
            
            if($stmt->rowCount() > 0){
                return true;
            }
        }
        
        return false;
    }
}
?>