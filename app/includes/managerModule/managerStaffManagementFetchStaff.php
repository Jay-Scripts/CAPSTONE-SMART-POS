<?php
include "../../config/dbConnection.php";


$staffList = $conn->query("
  SELECT si.staff_id, si.staff_name, GROUP_CONCAT(sr.role ORDER BY sr.role SEPARATOR ', ') AS roles
  FROM staff_info si
  LEFT JOIN staff_roles sr ON si.staff_id = sr.staff_id
  GROUP BY si.staff_id
  ORDER BY si.staff_name
")->fetchAll(PDO::FETCH_ASSOC);

foreach ($staffList as $staff): ?>
    <tr class="border-b">
        <td class="py-3 px-4 border"><?= htmlspecialchars($staff['staff_name']) ?></td>
        <td class="py-3 px-4 border"><?= $staff['roles'] ?? 'NONE' ?></td>
        <td class="py-3 px-4 text-center flex justify-center gap-2 border">
            <button onclick="modifyRole(<?= $staff['staff_id'] ?>,'<?= addslashes($staff['staff_name']) ?>')" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Modify Role</button>
            <button onclick="addRole(<?= $staff['staff_id'] ?>,'<?= addslashes($staff['staff_name']) ?>')" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Add Role</button>
        </td>
    </tr>
<?php endforeach; ?>