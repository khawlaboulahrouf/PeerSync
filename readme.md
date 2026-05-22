# PeerSync Backend — Guide d'installation

## Prérequis
- PHP 8.1+ (pour les Enums)
- MySQL 5.7+
- Un serveur local : XAMPP, WAMP, Laragon, ou MAMP

---

## Installation

### 1. Cloner le projet
```bash
git clone https://github.com/ton-user/peersync-backend.git
cd peersync-backend
```

### 2. Créer la base de données
Ouvre phpMyAdmin (ou ton client MySQL) et exécute :
```sql
-- Copie-colle tout le contenu du fichier schema.sql
```

### 3. Configurer la connexion
Ouvre `config/Database.php` et modifie ces lignes :
```php
$host     = 'localhost';
$dbName   = 'peersync';
$user     = 'root';
$password = 'TON_MOT_DE_PASSE'; // Mets le tien ici
```

### 4. Créer un utilisateur de test
Dans phpMyAdmin, exécute :
```sql
INSERT INTO users (nom, email, password, role) VALUES
    ('Alice', 'alice@test.com', '$2y$10$...', 'student');
```
Pour générer le hash du mot de passe, crée un fichier temporaire :
```php
<?php echo password_hash('motdepasse123', PASSWORD_DEFAULT); ?>
```

### 5. Lancer le projet
Place le dossier dans `htdocs/` (XAMPP) puis ouvre :
```
http://localhost/peersync-backend/public/index.php
```

---

## Architecture du projet

```
peersync-backend/
├── config/
│   └── Database.php          # Connexion PDO (Singleton)
├── src/
│   ├── Entities/             # Objets métier avec règles
│   │   ├── User.php
│   │   ├── HelpRequest.php
│   │   └── Evaluation.php
│   ├── Enums/
│   │   └── Status.php        # EN_ATTENTE | ASSIGNE | RESOLUE
│   └── Repositories/         # Requêtes SQL uniquement
│       ├── UserRepository.php
│       └── HelpRequestRepository.php
├── scripts/                  # Traitement des formulaires POST
│   ├── login_process.php
│   ├── request_process.php
│   ├── assign_process.php
│   └── close_process.php
├── public/                   # Pages HTML/PHP (interfaces)
│   ├── index.php
│   ├── dashboard.php
│   └── request_detail.php
├── schema.sql
└── README.md
```

---

## Tests des règles métiers

### ✅ Test 1 : Statut initial EN_ATTENTE
Créer un ticket → vérifier en BDD que `statut = 'EN_ATTENTE'`

### ✅ Test 2 : Un étudiant ne peut pas s'auto-assigner
Se connecter avec l'étudiant Alice → créer un ticket → cliquer "Prendre en charge"
**Résultat attendu** : message d'erreur "Un étudiant ne peut pas être le tuteur de son propre ticket !"

### ✅ Test 3 : Note invalide rejetée
Modifier `close_process.php` pour tester avec `$note = 6`
**Résultat attendu** : Exception "La note doit être comprise entre 1 et 5."

### ✅ Test 4 : Flux complet
1. Alice (student) crée un ticket → statut `EN_ATTENTE`
2. Bob (tutor) s'assigne → statut `ASSIGNE`
3. Alice clôture avec note 4 → statut `RESOLUE`, évaluation en BDD

---

## Commits suggérés (un par challenge)

```bash
git commit -m "Challenge 1 : Création du schema SQL"
git commit -m "Challenge 2 : Entité User + PDO Singleton"
git commit -m "Challenge 3 : Enum Status + Entité HelpRequest"
git commit -m "Challenge 4 : Méthode assignTo() avec règle métier"
git commit -m "Challenge 5 : Méthode resolve() + commentaire"
git commit -m "Challenge 6 : Entité Evaluation avec validation note"
git commit -m "Challenge 7 : Repositories - isolation du SQL"
```


