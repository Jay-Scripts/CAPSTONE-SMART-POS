<?php
include "../../config/dbConnection.php";


// Fetch today's feedback
$sql = "
SELECT 
    cf.feedback_id,
    rt.reg_transaction_id,
    cf.staff_attitude,
    cf.product_accuracy,
    cf.cleanliness,
    cf.speed_of_service,
    cf.overall_satisfaction,
    cf.feedback_text,
    cf.date_submitted
FROM customer_feedback cf
JOIN reg_transaction rt ON cf.reg_transaction_id = rt.reg_transaction_id
WHERE DATE(cf.date_submitted) = CURDATE()
ORDER BY cf.date_submitted DESC
";
$stmt = $conn->query($sql);
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Compute averages per category
$avg = ['staff' => 0, 'product' => 0, 'cleanliness' => 0, 'speed' => 0, 'overall' => 0];
$total = count($feedbacks);
if ($total > 0) {
    foreach ($feedbacks as $f) {
        $avg['staff'] += $f['staff_attitude'];
        $avg['product'] += $f['product_accuracy'];
        $avg['cleanliness'] += $f['cleanliness'];
        $avg['speed'] += $f['speed_of_service'];
        $avg['overall'] += $f['overall_satisfaction'];
    }
    foreach ($avg as $k => $v) $avg[$k] = round($v / $total, 2);
}

// Overall satisfaction percentage
$overall_avg = $total ? array_sum(array_map(
    fn($f) => ($f['staff_attitude'] + $f['product_accuracy'] + $f['cleanliness'] + $f['speed_of_service'] + $f['overall_satisfaction']) / 5,
    $feedbacks
)) / $total : 0;
$overall_percent = round($overall_avg / 5 * 100, 2);

// Return JSON
echo json_encode([
    'avg' => $avg,
    'overall_percent' => $overall_percent,
    'feedbacks' => $feedbacks,
    'total' => $total
]);
