<?php
include "../../config/dbConnection.php";


try {
    $staffLogs = $conn->query("
      SELECT 
          sl.logs_id,
          si.staff_id,
          si.staff_name,
          sl.login,
          sl.logout
      FROM staff_logs sl
      LEFT JOIN staff_info si ON sl.staff_id = si.staff_id
      ORDER BY sl.login DESC
  ")->fetchAll(PDO::FETCH_ASSOC);

    foreach ($staffLogs as $log) {
        echo "<tr class='hover:bg-blue-400 hover:text-white transition'>
      <td class='py-2 px-4 border border-[var(--border-color)]'>{$log['staff_id']}</td>
      <td class='py-2 px-4 border border-[var(--border-color)]'>" . htmlspecialchars($log['staff_name']) . "</td>
      <td class='py-2 px-4 border border-[var(--border-color)]'>" .
            ($log['login'] ? date('M d, Y • h:i A', strtotime($log['login'])) : '-') . "</td>
      <td class='py-2 px-4 border border-[var(--border-color)]'>" .
            ($log['logout'] ? date('M d, Y • h:i A', strtotime($log['logout'])) : '-') . "</td>
    </tr>";
    }
} catch (PDOException $e) {
    echo "<tr><td colspan='4' class='text-center py-3 text-gray-500'>Error loading logs</td></tr>";
}
