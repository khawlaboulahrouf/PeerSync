<?php

require_once "../config/Database.php";

$db = Database::getInstance();

$conn = $db->getConnection();

echo "Connexion réussie ";