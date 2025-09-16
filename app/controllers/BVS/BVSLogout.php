<?php
session_start();

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    // Reload page with param to trigger modal
    header("Location: bvs.php?showLogin=1");
    exit;
}
