<?php

/**
 * Formulaire de création de ticket (étudiants uniquement).
 */
require_once __DIR__ . '/../config/session.php';

requireLogin();

if (($_SESSION['user_role'] ?? '') !== 'student') {
    $_SESSION['error'] = 'Seuls les étudiants peuvent créer un ticket.';
    header('Location: dashboard.php');
    exit;
}

$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un ticket — PeerSync</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 520px; margin: 2rem auto; padding: 0 1rem; }
        label { display: block; margin-top: 1rem; font-weight: 600; }
        input, textarea { width: 100%; padding: 0.5rem; box-sizing: border-box; margin-top: 0.25rem; }
        textarea { min-height: 120px; }
        .msg-err { color: #b00; background: #fde8e8; padding: 0.75rem; border-radius: 6px; }
    </style>
</head>
<body>
    <p><a href="dashboard.php">← Retour au dashboard</a></p>
    <h1>Créer une demande d'aide</h1>

    <?php if ($error): ?>
        <p class="msg-err"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="../scripts/request_process.php" method="POST">
        <label for="titre">Titre</label>
        <input type="text" id="titre" name="titre" required maxlength="200">

        <label for="description">Description</label>
        <textarea id="description" name="description" required></textarea>

        <label for="technologie">Technologie</label>
        <input type="text" id="technologie" name="technologie" required maxlength="100">

        <p style="margin-top: 1.25rem">
            <button type="submit">Créer le ticket</button>
        </p>
    </form>
</body>
</html>
