<?php
class Transaction {
    private $conn;
    private $table_name = "transactions";
    private $detail_table_name = "transaction_details";

    public $id_transaction;
    public $id_customer;
    public $id_staff; 
    public $transaction_date;
    public $payment_method;
    public $total_amount;
    public $points_earned;
    public $status; 
    public $details; 

    public function __construct($db){
        $this->conn = $db;
    }

   
    function create(){
        
        $this->conn->beginTransaction();

        try {
            
            $query = "INSERT INTO " . $this->table_name . "
                      SET id_customer=:id_customer, id_staff=:id_staff,
                          payment_method=:payment_method, total_amount=:total_amount,
                          points_earned=:points_earned, status=:status";

            $stmt = $this->conn->prepare($query);

            
            $this->id_customer = htmlspecialchars(strip_tags($this->id_customer));
            $this->id_staff = htmlspecialchars(strip_tags($this->id_staff));
            $this->payment_method = htmlspecialchars(strip_tags($this->payment_method));
            $this->total_amount = htmlspecialchars(strip_tags($this->total_amount));
            $this->points_earned = htmlspecialchars(strip_tags($this->points_earned));
            $this->status = htmlspecialchars(strip_tags($this->status));

            
            $stmt->bindParam(":id_customer", $this->id_customer);
            $stmt->bindParam(":id_staff", $this->id_staff);
            $stmt->bindParam(":payment_method", $this->payment_method);
            $stmt->bindParam(":total_amount", $this->total_amount);
            $stmt->bindParam(":points_earned", $this->points_earned);
            $stmt->bindParam(":status", $this->status);

            if($stmt->execute()){
                $this->id_transaction = $this->conn->lastInsertId(); 
              
                foreach ($this->details as $detail) {
                    $detail_query = "INSERT INTO " . $this->detail_table_name . "
                                     SET id_transaction=:id_transaction, id_menu=:id_menu,
                                         quantity=:quantity, subtotal=:subtotal";

                    $detail_stmt = $this->conn->prepare($detail_query);

                   
                    $detail_id_menu = htmlspecialchars(strip_tags($detail->id_menu));
                    $detail_quantity = htmlspecialchars(strip_tags($detail->quantity));
                    $detail_subtotal = htmlspecialchars(strip_tags($detail->subtotal));

                   
                    $detail_stmt->bindParam(":id_transaction", $this->id_transaction);
                    $detail_stmt->bindParam(":id_menu", $detail_id_menu);
                    $detail_stmt->bindParam(":quantity", $detail_quantity);
                    $detail_stmt->bindParam(":subtotal", $detail_subtotal);

                    if(!$detail_stmt->execute()){
                        $this->conn->rollBack(); 
                        return false;
                    }
                }
                $this->conn->commit(); 
                return true;
            } else {
                $this->conn->rollBack(); 
                return false;
            }
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Transaction creation failed: " . $e->getMessage());
            return false;
        }
    }

  
}
?>