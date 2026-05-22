<?php

/**
 * Création d'un ticket — statut EN_ATTENTE par défaut (entité HelpRequest).
 */
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../src/Entities/HelpRequest.php';
require_once __DIR__ . '/../src/Repositories/HelpRequestRepository.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ../public/dashboard.php');
        exit;
    }

    if (empty($_SESSION['user_id'])) {
        $_SESSION['error'] = 'Vous devez être connecté pour créer un ticket.';
        header('Location: ../public/index.php');
        exit;
    }

    if ($_SESSION['user_role'] !== 'student') {
        $_SESSION['error'] = 'Seuls les étudiants peuvent créer un ticket.';
        header('Location: ../public/dashboard.php');
        exit;
    }

    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $technologie = trim($_POST['technologie'] ?? '');

    if ($titre === '' || $description === '' || $technologie === '') {
        $_SESSION['error'] = 'Tous les champs sont obligatoires.';
        header('Location: ../public/create_request.php');
        exit;
    }

    $helpRequest = new HelpRequest(
        null,
        $titre,
        $description,
        $technologie,
        (int) $_SESSION['user_id']
    );

    $repo = new HelpRequestRepository();
    $repo->create($helpRequest);

    $_SESSION['success'] = 'Ticket créé avec succès (statut EN_ATTENTE).';
    header('Location: ../public/dashboard.php');
    exit;
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: ../public/create_request.php');
    exit;
}
