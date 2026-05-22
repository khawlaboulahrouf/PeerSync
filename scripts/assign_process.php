<?php

/**
 * Assignation d'un ticket à un tuteur connecté (règle métier assignTo).
 */
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../src/Enums/Status.php';
require_once __DIR__ . '/../src/Repositories/UserRepository.php';
require_once __DIR__ . '/../src/Repositories/HelpRequestRepository.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ../public/dashboard.php');
        exit;
    }

    if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'tutor') {
        $_SESSION['error'] = 'Seul un tuteur peut s\'assigner un ticket.';
        header('Location: ../public/dashboard.php');
        exit;
    }

    $requestId = (int) ($_POST['request_id'] ?? 0);
    if ($requestId <= 0) {
        throw new Exception('Ticket invalide.');
    }

    $helpRepo = new HelpRequestRepository();
    $userRepo = new UserRepository();

    $helpRequest = $helpRepo->findById($requestId);
    if ($helpRequest === null) {
        throw new Exception('Ticket introuvable.');
    }

    if ($helpRequest->getStatut() !== Status::EN_ATTENTE) {
        throw new Exception('Ce ticket n\'est plus en attente.');
    }

    $tutor = $userRepo->findById((int) $_SESSION['user_id']);
    if ($tutor === null) {
        throw new Exception('Tuteur introuvable.');
    }

    $helpRequest->assignTo($tutor);
    $helpRepo->update($helpRequest);

    $_SESSION['success'] = 'Ticket assigné avec succès.';
    header('Location: ../public/request_detail.php?id=' . $requestId);
    exit;
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    $id = (int) ($_POST['request_id'] ?? 0);
    if ($id > 0) {
        header('Location: ../public/request_detail.php?id=' . $id);
    } else {
        header('Location: ../public/dashboard.php');
    }
    exit;
}
