<?php
// Fetch Add-ons
$addonsSql = "SELECT ADD_ONS_ID, ADD_ONS_NAME, PRICE FROM PRODUCT_ADD_ONS WHERE status='active' ORDER BY ADD_ONS_NAME ASC";
$addonsStmt = $conn->prepare($addonsSql);
$addonsStmt->execute();
$addons = $addonsStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Modifications
$modsSql = "SELECT MODIFICATION_ID, MODIFICATION_NAME FROM PRODUCT_MODIFICATIONS WHERE status='active' ORDER BY MODIFICATION_NAME ASC";
$modsStmt = $conn->prepare($modsSql);
$modsStmt->execute();
$modifications = $modsStmt->fetchAll(PDO::FETCH_ASSOC);
