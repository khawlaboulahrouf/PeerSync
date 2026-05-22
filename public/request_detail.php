<?php

/**
 * Détail d'un ticket et actions selon le rôle.
 */
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../src/Enums/Status.php';
require_once __DIR__ . '/../src/Repositories/HelpRequestRepository.php';
require_once __DIR__ . '/../src/Repositories/UserRepository.php';

requireLogin();

$requestId = (int) ($_GET['id'] ?? 0);
if ($requestId <= 0) {
    header('Location: dashboard.php');
    exit;
}

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

try {
    $repo = new HelpRequestRepository();
    $helpRequest = $repo->findById($requestId);
    if ($helpRequest === null) {
        throw new Exception('Ticket introuvable.');
    }
    $userRepo = new UserRepository();
    $student = $userRepo->findById($helpRequest->getIdStudent());
    $tutor = null;
    if ($helpRequest->getIdTutor()) {
        $tutor = $userRepo->findById($helpRequest->getIdTutor());
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: dashboard.php');
    exit;
}

$currentRole = $_SESSION['user_role'] ?? '';
$currentUserId = (int) ($_SESSION['user_id'] ?? 0);
$canAssign = $currentRole === 'tutor' && $helpRequest->getStatus() === Status::PENDING;
$canResolve = $currentRole === 'tutor' && $currentUserId === (int) $helpRequest->getIdTutor() && $helpRequest->getStatus() === Status::ASSIGNED;
$canEvaluate = $currentRole === 'student' && $currentUserId === (int) $helpRequest->getIdStudent() && $helpRequest->getStatus() === Status::RESOLVED;

$htmlStatus = htmlspecialchars($helpRequest->getStatus());
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail du ticket — PeerSync</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 760px; margin: 2rem auto; padding: 0 1rem; }
        nav a { margin-right: 1rem; }
        .msg-ok { color: #0a6; background: #e8f8ef; padding: 0.75rem; border-radius: 6px; }
        .msg-err { color: #b00; background: #fde8e8; padding: 0.75rem; border-radius: 6px; }
        .ticket { border: 1px solid #ddd; padding: 1rem; border-radius: 8px; margin: 1rem 0; }
        label { display: block; margin-top: 1rem; font-weight: 600; }
        input, textarea, select { width: 100%; padding: 0.5rem; margin-top: 0.25rem; box-sizing: border-box; }
        button { margin-top: 1rem; padding: 0.6rem 1rem; cursor: pointer; }
        .note { margin: 0.25rem 0; }
    </style>
</head>
<body>
    <nav>
        <a href="dashboard.php">← Retour</a>
        | <a href="profil.php">Profil</a>
        | <a href="../scripts/logout_process.php">Déconnexion</a>
    </nav>

    <h1>Détail du ticket</h1>

    <?php if ($success): ?>
        <p class="msg-ok"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p class="msg-err"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <article class="ticket">
        <h2><?= htmlspecialchars($helpRequest->getTitre()) ?></h2>
        <p><?= nl2br(htmlspecialchars($helpRequest->getDescription())) ?></p>
        <p class="note"><strong>Technologie :</strong> <?= htmlspecialchars($helpRequest->getTechnologie()) ?></p>
        <p class="note"><strong>Statut :</strong> <?= $htmlStatus ?></p>
        <p class="note"><strong>Étudiant :</strong> <?= htmlspecialchars($student ? $student->getName() : '—') ?></p>
        <p class="note"><strong>Tuteur :</strong> <?= htmlspecialchars($tutor ? $tutor->getName() : 'Non assigné') ?></p>
        <p class="note"><strong>Commentaire :</strong><br><?= nl2br(htmlspecialchars($helpRequest->getCommentaire() ?? 'Aucun commentaire')) ?></p>
    </article>

    <?php if ($canAssign): ?>
        <form action="../scripts/assign_process.php" method="POST">
            <input type="hidden" name="request_id" value="<?= (int) $helpRequest->getId() ?>">
            <button type="submit">S'assigner ce ticket</button>
        </form>
    <?php endif; ?>

    <?php if ($canResolve): ?>
        <form action="../scripts/close_process.php" method="POST">
            <input type="hidden" name="request_id" value="<?= (int) $helpRequest->getId() ?>">
            <input type="hidden" name="action" value="resolve">
            <label for="commentaire">Commentaire de résolution (facultatif)</label>
            <textarea id="commentaire" name="commentaire"></textarea>
            <button type="submit">Marquer comme résolu</button>
        </form>
    <?php endif; ?>

    <?php if ($canEvaluate): ?>
        <form action="../scripts/close_process.php" method="POST">
            <input type="hidden" name="request_id" value="<?= (int) $helpRequest->getId() ?>">
            <input type="hidden" name="action" value="evaluate">
            <label for="note">Note (1 à 5)</label>
            <select id="note" name="note" required>
                <option value="">Choisir</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <label for="eval_commentaire">Commentaire (facultatif)</label>
            <textarea id="eval_commentaire" name="eval_commentaire"></textarea>
            <button type="submit">Envoyer l'évaluation</button>
        </form>
    <?php endif; ?>
</body>
</html>
