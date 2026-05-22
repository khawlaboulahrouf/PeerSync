<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../Entities/HelpRequest.php';
require_once __DIR__ . '/../Entities/Evaluation.php';
require_once __DIR__ . '/../Enums/Status.php';

/**
 * Accès SQL aux tickets d'aide.
 */
class HelpRequestRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function create($helpRequest)
    {
        try {
            $sql = 'INSERT INTO help_requests (titre, description, technologie, status, id_student, id_tutor) VALUES (:titre, :description, :technologie, :status, :id_student, :id_tutor)';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':titre' => $helpRequest->getTitre(),
                ':description' => $helpRequest->getDescription(),
                ':technologie' => $helpRequest->getTechnologie(),
                ':status' => $helpRequest->getStatus(),
                ':id_student' => $helpRequest->getIdStudent(),
                ':id_tutor' => $helpRequest->getIdTutor()
            ]);
            $helpRequest->setId((int) $this->conn->lastInsertId());
        } catch (PDOException $e) {
            throw new Exception('Erreur lors de la création du ticket : ' . $e->getMessage());
        }
    }

    public function getAll()
    {
        try {
            $sql = 'SELECT id, titre, description, technologie, status, commentaire, id_student, id_tutor, created_at FROM help_requests ORDER BY id DESC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $requests = [];
            foreach ($rows as $row) {
                $requests[] = $this->hydrate($row);
            }
            return $requests;
        } catch (PDOException $e) {
            throw new Exception('Erreur lors de la récupération des tickets : ' . $e->getMessage());
        }
    }

    public function findById($id)
    {
        try {
            $sql = 'SELECT id, titre, description, technologie, status, commentaire, id_student, id_tutor, created_at FROM help_requests WHERE id = :id LIMIT 1';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return null;
            }
            return $this->hydrate($row);
        } catch (PDOException $e) {
            throw new Exception('Erreur lors de la récupération du ticket : ' . $e->getMessage());
        }
    }

    public function update($helpRequest)
    {
        try {
            $sql = 'UPDATE help_requests SET titre = :titre, description = :description, technologie = :technologie, status = :status, commentaire = :commentaire, id_tutor = :id_tutor WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':titre' => $helpRequest->getTitre(),
                ':description' => $helpRequest->getDescription(),
                ':technologie' => $helpRequest->getTechnologie(),
                ':status' => $helpRequest->getStatus(),
                ':commentaire' => $helpRequest->getCommentaire(),
                ':id_tutor' => $helpRequest->getIdTutor(),
                ':id' => $helpRequest->getId()
            ]);
        } catch (PDOException $e) {
            throw new Exception('Erreur lors de la mise à jour du ticket : ' . $e->getMessage());
        }
    }

    public function saveEvaluation($evaluation)
    {
        try {
            $sql = 'INSERT INTO evaluations (note, commentaire, id_help_request) VALUES (:note, :commentaire, :id_help_request)';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':note' => $evaluation->getNote(),
                ':commentaire' => $evaluation->getCommentaire(),
                ':id_help_request' => $evaluation->getIdHelpRequest()
            ]);
        } catch (PDOException $e) {
            throw new Exception('Erreur lors de l enregistrement de l évaluation : ' . $e->getMessage());
        }
    }

    private function hydrate($row)
    {
        return new HelpRequest(
            (int) $row['id'],
            $row['titre'],
            $row['description'],
            $row['technologie'],
            (int) $row['id_student'],
            isset($row['id_tutor']) && $row['id_tutor'] !== null ? (int) $row['id_tutor'] : null,
            $row['status'],
            $row['commentaire'] ?? null,
            $row['created_at'] ?? null
        );
    }
}
