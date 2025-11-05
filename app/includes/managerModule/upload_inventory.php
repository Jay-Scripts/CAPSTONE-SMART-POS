<?php
session_start();
include "../../config/dbConnection.php";

// Check file upload
if (isset($_FILES['inventory_file']) && $_FILES['inventory_file']['error'] === 0) {
    $file = $_FILES['inventory_file']['tmp_name'];

    $reportData = [
        'Base Ingredients' => [],
        'Ingredients' => [],
        'Materials' => []
    ];

    // Read CSV
    if (($handle = fopen($file, "r")) !== false) {
        $header = fgetcsv($handle); // Skip header
        while (($row = fgetcsv($handle)) !== false) {
            $itemName   = $row[0];
            $unit       = $row[1];
            $staffCount = floatval($row[2] ?? 0);

            // Fetch actual/system count and price from DB
            $stmt = $conn->prepare("SELECT quantity, price, inv_category_id FROM inventory_item WHERE item_name = :name LIMIT 1");
            $stmt->execute(['name' => $itemName]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$item) continue;

            $systemCount = floatval($item['quantity']);
            $amount = ($staffCount - $systemCount) * floatval($item['price']);
            $category = $item['inv_category_id'];

            // Map category to table
            if ($category == 3) $table = 'Base Ingredients';
            elseif ($category == 1) $table = 'Ingredients';
            else $table = 'Materials';

            $reportData[$table][] = [
                'Item Name'    => $itemName,
                'Unit'         => $unit,
                'System Count' => $systemCount,
                'Actual Count' => $staffCount,
                'Staff Count'  => $staffCount,
                'Variance'     => $staffCount - $systemCount,
                'Amount'       => $amount
            ];
        }
        fclose($handle);
    }

    // Generate CSV report for download
    $filename = "Inventory_Report_" . date('Y-m-d_His') . ".csv";
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    $output = fopen('php://output', 'w');

    foreach ($reportData as $tableName => $rows) {
        if (empty($rows)) continue;
        // Table title
        fputcsv($output, [$tableName]);
        // Header
        fputcsv($output, array_keys($rows[0]));
        // Data
        foreach ($rows as $row) {
            fputcsv($output, $row);
        }
        // Blank row between tables
        fputcsv($output, []);
    }

    fclose($output);
    exit;
}
?>
<?php
session_start();
include "../../config/dbConnection.php";

// Check file upload
if (isset($_FILES['inventory_file']) && $_FILES['inventory_file']['error'] === 0) {
    $file = $_FILES['inventory_file']['tmp_name'];

    $reportData = [
        'Base Ingredients' => [],
        'Ingredients' => [],
        'Materials' => []
    ];

    // Read CSV
    if (($handle = fopen($file, "r")) !== false) {
        $header = fgetcsv($handle); // Skip header
        while (($row = fgetcsv($handle)) !== false) {
            $itemName   = $row[0];
            $unit       = $row[1];
            $staffCount = floatval($row[2] ?? 0);

            // Fetch actual/system count and price from DB
            $stmt = $conn->prepare("SELECT quantity, price, inv_category_id FROM inventory_item WHERE item_name = :name LIMIT 1");
            $stmt->execute(['name' => $itemName]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$item) continue;

            $systemCount = floatval($item['quantity']);
            $amount = ($staffCount - $systemCount) * floatval($item['price']);
            $category = $item['inv_category_id'];

            // Map category to table
            if ($category == 3) $table = 'Base Ingredients';
            elseif ($category == 1) $table = 'Ingredients';
            else $table = 'Materials';

            $reportData[$table][] = [
                'Item Name'    => $itemName,
                'Unit'         => $unit,
                'System Count' => $systemCount,
                'Actual Count' => $staffCount,
                'Staff Count'  => $staffCount,
                'Variance'     => $staffCount - $systemCount,
                'Amount'       => $amount
            ];
        }
        fclose($handle);
    }

    // Generate CSV report for download
    $filename = "Inventory_Report_" . date('Y-m-d_His') . ".csv";
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    $output = fopen('php://output', 'w');

    foreach ($reportData as $tableName => $rows) {
        if (empty($rows)) continue;
        // Table title
        fputcsv($output, [$tableName]);
        // Header
        fputcsv($output, array_keys($rows[0]));
        // Data
        foreach ($rows as $row) {
            fputcsv($output, $row);
        }
        // Blank row between tables
        fputcsv($output, []);
    }

    fclose($output);
    exit;
}
?>
