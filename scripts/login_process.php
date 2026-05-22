<?php

/**
 * Traitement du formulaire de connexion.
 * Supporte les mots de passe hashés et les anciens mots de passe en clair.
 */
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../src/Repositories/UserRepository.php';

startSessionIfNeeded();

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ../public/index.php?error=1');
        exit;
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $_SESSION['error'] = 'Email et mot de passe obligatoires.';
        header('Location: ../public/index.php?error=1');
        exit;
    }

    $userRepo = new UserRepository();
    $user = $userRepo->findByEmail($email);

    // debug temporaire :
    // var_dump($user);
    // var_dump($password);
    // die();

    if ($user === null) {
        $_SESSION['error'] = 'Identifiants incorrects.';
        header('Location: ../public/index.php?error=1');
        exit;
    }

    $storedPassword = $user->getPassword();
    $isValidPassword = false;

    if ($storedPassword !== '' && password_verify($password, $storedPassword)) {
        $isValidPassword = true;
    } elseif ($password === $storedPassword) {
        $isValidPassword = true;
        // Ancien mot de passe stocké en clair : on le sécurise automatiquement.
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $userRepo->updatePasswordById($user->getId(), $newHash);
    }

    if (!$isValidPassword) {
        $_SESSION['error'] = 'Identifiants incorrects.';
        header('Location: ../public/index.php?error=1');
        exit;
    }

    $_SESSION['user_id'] = $user->getId();
    $_SESSION['user_name'] = $user->getName();
    $_SESSION['user_email'] = $user->getEmail();
    $_SESSION['user_role'] = $user->getRole();
    $_SESSION['role'] = $user->getRole();
    $_SESSION['success'] = 'Connexion réussie. Bienvenue, ' . $user->getName() . ' !';

    header('Location: ../public/dashboard.php');
    exit;
} catch (Exception $e) {
    startSessionIfNeeded();
    $_SESSION['error'] = 'Erreur lors de la connexion : ' . $e->getMessage();
    header('Location: ../public/index.php?error=1');
    exit;
}
