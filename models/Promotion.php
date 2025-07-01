<?php
class Promotion {
    private $conn;
    private $table_name = "promotions";

    public $id_promotion;
    public $promo_name;
    public $description;
    public $discount_type;
    public $discount_value;
    public $is_active;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Membaca semua promosi (untuk admin)
    function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY start_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Membaca promosi yang aktif saja (untuk customer)
    function readActive() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE is_active = 1 ORDER BY start_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Membuat promosi baru
    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET promo_name=:promo_name, description=:description, discount_type=:discount_type, discount_value=:discount_value, start_date=UTC_TIMESTAMP(), end_date=UTC_TIMESTAMP(), is_active=1";
        
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->promo_name=htmlspecialchars(strip_tags($this->promo_name));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->discount_type=htmlspecialchars(strip_tags($this->discount_type));
        $this->discount_value=htmlspecialchars(strip_tags($this->discount_value));

        // Bind data
        $stmt->bindParam(":promo_name", $this->promo_name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":discount_type", $this->discount_type);
        $stmt->bindParam(":discount_value", $this->discount_value);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Mengubah status aktif/tidak aktif
    function updateStatus() {
        $query = "UPDATE " . $this->table_name . " SET is_active = :is_active WHERE id_promotion = :id_promotion";
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->is_active=htmlspecialchars(strip_tags($this->is_active));
        $this->id_promotion=htmlspecialchars(strip_tags($this->id_promotion));

        // Bind data
        $stmt->bindParam(':is_active', $this->is_active);
        $stmt->bindParam(':id_promotion', $this->id_promotion);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>