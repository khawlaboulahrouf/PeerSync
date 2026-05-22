<?php

/**
 * Résolution d'un ticket + commentaire optionnel + évaluation (note 1-5).
 */
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../src/Entities/Evaluation.php';
require_once __DIR__ . '/../src/Repositories/HelpRequestRepository.php';
require_once __DIR__ . '/../src/Enums/Status.php';

startSessionIfNeeded();

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ../public/dashboard.php');
        exit;
    }

    if (empty($_SESSION['user_id'])) {
        $_SESSION['error'] = 'Connexion requise.';
        header('Location: ../public/index.php');
        exit;
    }

    $requestId = (int) ($_POST['request_id'] ?? 0);
    $action = $_POST['action'] ?? 'resolve';

    $helpRepo = new HelpRequestRepository();
    $helpRequest = $helpRepo->findById($requestId);

    if ($helpRequest === null) {
        throw new Exception('Ticket introuvable.');
    }

    if ($action === 'comment') {
        $commentaire = trim($_POST['commentaire'] ?? '');
        if ($commentaire === '') {
            throw new Exception('Le commentaire ne peut pas être vide.');
        }
        $helpRequest->setCommentaire($commentaire);
        $helpRepo->update($helpRequest);
        $_SESSION['success'] = 'Commentaire enregistré.';
        header('Location: ../public/request_detail.php?id=' . $requestId);
        exit;
    }

    if ($action === 'evaluate') {
        if ($_SESSION['user_role'] !== 'student' || (int) $_SESSION['user_id'] !== $helpRequest->getIdStudent()) {
            throw new Exception('Seul l\'étudiant auteur peut noter ce ticket.');
        }
        if ($helpRequest->getStatus() !== Status::RESOLVED) {
            throw new Exception('Le ticket doit être résolu avant l\'évaluation.');
        }
        $note = (int) ($_POST['note'] ?? 0);
        $evalComment = trim($_POST['eval_commentaire'] ?? '') ?: null;
        $evaluation = new Evaluation($note, $evalComment, $requestId);
        $helpRepo->saveEvaluation($evaluation);
        $_SESSION['success'] = 'Évaluation enregistrée.';
        header('Location: ../public/request_detail.php?id=' . $requestId);
        exit;
    }

    if ($_SESSION['user_role'] !== 'tutor' || (int) $_SESSION['user_id'] !== $helpRequest->getIdTutor()) {
        throw new Exception('Seul le tuteur assigné peut résoudre ce ticket.');
    }
    if ($helpRequest->getStatus() !== Status::ASSIGNED) {
        throw new Exception('Le ticket doit être assigné pour être résolu.');
    }

    $helpRequest->resolve();
    $resolutionComment = trim($_POST['commentaire'] ?? '');
    if ($resolutionComment !== '') {
        $helpRequest->setCommentaire($resolutionComment);
    }
    $helpRepo->update($helpRequest);

    $_SESSION['success'] = 'Ticket marqué comme RESOLVED.';
    header('Location: ../public/request_detail.php?id=' . $requestId);
    exit;
} catch (Exception $e) {
    startSessionIfNeeded();
    $_SESSION['error'] = $e->getMessage();
    $id = (int) ($_POST['request_id'] ?? 0);
    header('Location: ' . ($id > 0 ? '../public/request_detail.php?id=' . $id : '../public/dashboard.php'));
    exit;
}
