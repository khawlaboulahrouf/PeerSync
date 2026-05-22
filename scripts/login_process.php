<?php

/**
 * Traitement du formulaire de connexion (password_verify + session).
 */
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../src/Repositories/UserRepository.php';

startSessionIfNeeded();

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ../public/index.php');
        exit;
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $_SESSION['error'] = 'Email et mot de passe obligatoires.';
        header('Location: ../public/index.php');
        exit;
    }

    $userRepo = new UserRepository();
    $user = $userRepo->findByEmail($email);

    if ($user === null || !password_verify($password, $user->getPassword())) {
        $_SESSION['error'] = 'Identifiants incorrects.';
        header('Location: ../public/index.php');
        exit;
    }

    $_SESSION['user_id'] = $user->getId();
    $_SESSION['user_name'] = $user->getName();
    $_SESSION['user_email'] = $user->getEmail();
    $_SESSION['user_role'] = $user->getRole();
    $_SESSION['success'] = 'Connexion réussie. Bienvenue, ' . $user->getName() . ' !';

    header('Location: ../public/dashboard.php');
    exit;
} catch (Exception $e) {
    startSessionIfNeeded();
    $_SESSION['error'] = $e->getMessage();
    header('Location: ../public/index.php');
    exit;
}
