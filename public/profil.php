<?php

/**
 * Profil de l'utilisateur connecté.
 */
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../src/Repositories/UserRepository.php';

requireLogin();

$user = null;
$error = null;

try {
    $userRepo = new UserRepository();
    $user = $userRepo->findById((int) $_SESSION['user_id']);
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil — PeerSync</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 520px; margin: 2rem auto; padding: 0 1rem; }
        dl { display: grid; grid-template-columns: 120px 1fr; gap: 0.5rem 1rem; }
        dt { font-weight: 600; }
        .msg-err { color: #b00; }
    </style>
</head>
<body>
    <p><a href="dashboard.php">← Dashboard</a></p>
    <h1>Mon profil</h1>

    <?php if ($error): ?>
        <p class="msg-err"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($user): ?>
        <dl>
            <dt>ID</dt>
            <dd><?= (int) $user->getId() ?></dd>
            <dt>Nom</dt>
            <dd><?= htmlspecialchars($user->getName()) ?></dd>
            <dt>Email</dt>
            <dd><?= htmlspecialchars($user->getEmail()) ?></dd>
            <dt>Rôle</dt>
            <dd><?= htmlspecialchars($user->getRole()) ?></dd>
        </dl>
    <?php endif; ?>
</body>
</html>
