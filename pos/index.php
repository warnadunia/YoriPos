<?php
// pos/index.php

$page = 'pos';
$pageTitle = "Point of Sale";

// Tentukan path konten untuk POS (merujuk ke app/views/pos.php)
$contentFile = __DIR__ . "/../app/views/pos.php";

// Render Global Layout
require_once __DIR__ . '/../app/views/layout.php';
?>