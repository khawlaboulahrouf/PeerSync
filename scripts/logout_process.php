<?php

/**
 * Déconnexion — destruction de la session.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = [];
session_destroy();

header('Location: ../public/index.php');
exit;
