<?php

/**
 * Tableau de bord — liste des tickets et actions selon le rôle.
 */
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../src/Repositories/HelpRequestRepository.php';
require_once __DIR__ . '/../src/Repositories/UserRepository.php';

requireLogin();

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

try {
    $repo = new HelpRequestRepository();
    $requests = $repo->getAll();
    $userRepo = new UserRepository();
} catch (Exception $e) {
    $error = $e->getMessage();
    $requests = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — PeerSync</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 800px; margin: 2rem auto; padding: 0 1rem; }
        nav a { margin-right: 1rem; }
        .msg-ok { color: #0a6; background: #e8f8ef; padding: 0.75rem; border-radius: 6px; }
        .msg-err { color: #b00; background: #fde8e8; padding: 0.75rem; border-radius: 6px; }
        .ticket { border: 1px solid #ddd; padding: 1rem; margin: 1rem 0; border-radius: 8px; }
        .badge { display: inline-block; padding: 0.2rem 0.5rem; background: #eee; border-radius: 4px; font-size: 0.85rem; }
    </style>
</head>
<body>
    <nav>
        <strong>PeerSync</strong> —
        <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>
        (<?= htmlspecialchars($_SESSION['user_role'] ?? '') ?>)
        | <a href="profil.php">Profil</a>
        | <a href="../scripts/logout_process.php">Déconnexion</a>
    </nav>

    <h1>Liste des tickets</h1>

    <?php if ($success): ?>
        <p class="msg-ok"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p class="msg-err"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (($_SESSION['user_role'] ?? '') === 'student'): ?>
        <p><a href="create_request.php">+ Créer un ticket</a></p>
    <?php endif; ?>

    <?php if (empty($requests)): ?>
        <p>Aucun ticket pour le moment.</p>
    <?php else: ?>
        <?php foreach ($requests as $ticket): ?>
            <article class="ticket">
                <h3>
                    <a href="request_detail.php?id=<?= (int) $ticket->getId() ?>">
                        <?= htmlspecialchars($ticket->getTitre()) ?>
                    </a>
                </h3>
                <p><?= nl2br(htmlspecialchars($ticket->getDescription())) ?></p>
                <p>
                    <span class="badge"><?= htmlspecialchars($ticket->getTechnologie()) ?></span>
                    <span class="badge"><?= htmlspecialchars($ticket->getStatut()->value) ?></span>
                </p>
                <p><small>Créé le <?= htmlspecialchars($ticket->getCreatedAt() ?? '—') ?></small></p>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
