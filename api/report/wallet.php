<?php
// Headers Wajib untuk API
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle pre-flight request (OPTIONS method)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    // Query untuk mengambil total penjualan per bulan dan per metode pembayaran
    $query = "
        SELECT
            YEAR(transaction_date) AS tahun,
            MONTH(transaction_date) AS bulan,
            payment_method,
            SUM(total_amount) AS total_bulanan
        FROM
            transactions
        WHERE
            status = 'Completed'
        GROUP BY
            tahun, bulan, payment_method
        ORDER BY
            tahun DESC, bulan DESC, payment_method ASC
    ";

    $stmt = $db->prepare($query);
    $stmt->execute();

    $reports = [];
    $monthly_totals = [];

    // Proses hasil query menjadi struktur yang lebih mudah diolah
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $month_key = $row['tahun'] . '-' . str_pad($row['bulan'], 2, '0', STR_PAD_LEFT);
        
        if (!isset($reports[$month_key])) {
            $reports[$month_key] = [
                'month_name' => DateTime::createFromFormat('!m', $row['bulan'])->format('F') . ' ' . $row['tahun'],
                'total_revenue' => 0,
                'breakdown' => []
            ];
        }

        $reports[$month_key]['breakdown'][$row['payment_method']] = (float)$row['total_bulanan'];
        $reports[$month_key]['total_revenue'] += (float)$row['total_bulanan'];
    }

    // Hitung total revenue per bulan untuk perhitungan perbandingan
    foreach ($reports as $key => $report) {
        $monthly_totals[$key] = $report['total_revenue'];
    }
    
    $keys = array_keys($monthly_totals);
    $final_reports = [];

    // Hitung perbandingan dengan bulan sebelumnya
    for ($i = 0; $i < count($keys); $i++) {
        $current_key = $keys[$i];
        $current_report = $reports[$current_key];
        
        $previous_key = isset($keys[$i + 1]) ? $keys[$i + 1] : null;
        $previous_total = $previous_key ? $monthly_totals[$previous_key] : 0;
        
        $comparison_text = '0,00% vs bulan lalu';
        if ($previous_total > 0) {
            $percentage_change = (($current_report['total_revenue'] - $previous_total) / $previous_total) * 100;
            $comparison_text = number_format($percentage_change, 2, ',', '.') . '% vs bulan lalu';
        }

        $current_report['comparison'] = $comparison_text;
        
        // Format breakdown
        $formatted_breakdown = [];
        foreach($current_report['breakdown'] as $method => $amount){
            $formatted_breakdown[ucfirst($method)] = 'Rp' . number_format($amount, 0, ',', '.');
        }
        $current_report['breakdown'] = $formatted_breakdown;

        // Format total revenue
        $current_report['total_revenue'] = 'Rp' . number_format($current_report['total_revenue'], 0, ',', '.');

        $final_reports[] = $current_report;
    }

    http_response_code(200);
    echo json_encode(['success' => true, 'reports' => array_values($final_reports)]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal mengambil data laporan.', 'error' => $e->getMessage()]);
}
?>