<?php
require_once 'includes/config.php';

$userModel = new User();
$userModel->logout();

header('Location: index.php');
