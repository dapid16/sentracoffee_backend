<?php
class Transaction {
    private $conn;
    private $table_name = "transactions";
    private $detail_table_name = "transaction_details";

    public $id_transaction;
    public $id_customer;
    public $id_staff; // Dari SQL: NOT NULL, jadi perlu dikirim atau ada default
    public $transaction_date;
    public $payment_method;
    public $total_amount;
    public $points_earned;
    public $status; // Default: 'Pending' atau 'Completed'
    public $details; // Array untuk transaction_details

    public function __construct($db){
        $this->conn = $db;
    }

    // Metode untuk membuat transaksi baru beserta detailnya
    function create(){
        // Mulai transaksi database
        $this->conn->beginTransaction();

        try {
            // 1. Insert ke tabel transactions
            $query = "INSERT INTO " . $this->table_name . "
                      SET id_customer=:id_customer, id_staff=:id_staff,
                          payment_method=:payment_method, total_amount=:total_amount,
                          points_earned=:points_earned, status=:status";

            $stmt = $this->conn->prepare($query);

            // Bersihkan data
            $this->id_customer = htmlspecialchars(strip_tags($this->id_customer));
            // Asumsi: id_staff akan dikirim atau di-default ke 1 untuk sementara
            $this->id_staff = htmlspecialchars(strip_tags($this->id_staff));
            $this->payment_method = htmlspecialchars(strip_tags($this->payment_method));
            $this->total_amount = htmlspecialchars(strip_tags($this->total_amount));
            $this->points_earned = htmlspecialchars(strip_tags($this->points_earned));
            $this->status = htmlspecialchars(strip_tags($this->status));

            // Bind nilai
            $stmt->bindParam(":id_customer", $this->id_customer);
            $stmt->bindParam(":id_staff", $this->id_staff);
            $stmt->bindParam(":payment_method", $this->payment_method);
            $stmt->bindParam(":total_amount", $this->total_amount);
            $stmt->bindParam(":points_earned", $this->points_earned);
            $stmt->bindParam(":status", $this->status);

            if($stmt->execute()){
                $this->id_transaction = $this->conn->lastInsertId(); // Ambil ID transaksi yang baru dibuat

                // 2. Insert ke tabel transaction_details
                foreach ($this->details as $detail) {
                    $detail_query = "INSERT INTO " . $this->detail_table_name . "
                                     SET id_transaction=:id_transaction, id_menu=:id_menu,
                                         quantity=:quantity, subtotal=:subtotal";

                    $detail_stmt = $this->conn->prepare($detail_query);

                    // Bersihkan data detail
                    $detail_id_menu = htmlspecialchars(strip_tags($detail->id_menu));
                    $detail_quantity = htmlspecialchars(strip_tags($detail->quantity));
                    $detail_subtotal = htmlspecialchars(strip_tags($detail->subtotal));

                    // Bind nilai detail
                    $detail_stmt->bindParam(":id_transaction", $this->id_transaction);
                    $detail_stmt->bindParam(":id_menu", $detail_id_menu);
                    $detail_stmt->bindParam(":quantity", $detail_quantity);
                    $detail_stmt->bindParam(":subtotal", $detail_subtotal);

                    if(!$detail_stmt->execute()){
                        $this->conn->rollBack(); // Rollback jika ada detail yang gagal
                        return false;
                    }
                }
                $this->conn->commit(); // Commit transaksi jika semua sukses
                return true;
            } else {
                $this->conn->rollBack(); // Rollback jika insert transaksi gagal
                return false;
            }
        } catch (PDOException $e) {
            $this->conn->rollBack();
            // Log error
            error_log("Transaction creation failed: " . $e->getMessage());
            return false;
        }
    }

    // TODO: Metode read, update, delete untuk transaksi jika diperlukan
}
?>