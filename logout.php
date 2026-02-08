<?php
require_once 'model/config.php';

$userModel = new User();
$userModel->logout();

header('Location: index.php');
