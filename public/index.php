<?php

/**
 * Page de connexion PeerSync.
 */
require_once __DIR__ . '/../config/session.php';

startSessionIfNeeded();

if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PeerSync — Connexion</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 420px; margin: 3rem auto; padding: 0 1rem; }
        .msg-ok { color: #0a6; background: #e8f8ef; padding: 0.75rem; border-radius: 6px; }
        .msg-err { color: #b00; background: #fde8e8; padding: 0.75rem; border-radius: 6px; }
        label { display: block; margin-top: 1rem; font-weight: 600; }
        input { width: 100%; padding: 0.5rem; margin-top: 0.25rem; box-sizing: border-box; }
        button { margin-top: 1.25rem; padding: 0.6rem 1.2rem; cursor: pointer; }
        .hint { font-size: 0.85rem; color: #555; margin-top: 1.5rem; }
    </style>
</head>
<body>
    <h1>PeerSync</h1>
    <p>Plateforme d'entraide entre apprenants</p>

    <?php if ($success): ?>
        <p class="msg-ok"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p class="msg-err"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="../scripts/login_process.php" method="POST">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required autocomplete="username">

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required autocomplete="current-password">

        <button type="submit">Se connecter</button>
    </form>

    <p class="hint">
        Comptes démo (mot de passe <strong>password123</strong>) :<br>
        Étudiant : alice@peersync.local<br>
        Tuteur : bob@peersync.local
    </p>
</body>
</html>
