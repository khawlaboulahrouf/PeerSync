<?php

require_once "../config/Database.php";

class HelpRequestRepository {

    private $conn;

    public function __construct()
    {

        $database = Database::getInstance();

        $this->conn = $database->getConnection();
    }

    // ajouter ticket

    public function create($helpRequest)
    {

        $sql = "INSERT INTO help_requests
        (titre, description, technologie, status, id_student, id_tutor)

        VALUES

        (:titre, :description, :technologie, :status, :id_student, :id_tutor)
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([

            ':titre' => $helpRequest->getTitre(),

            ':description' => $helpRequest->getDescription(),

            ':technologie' => $helpRequest->getTechnologie(),

            ':status' => $helpRequest->getStatus(),

            ':id_student' => $helpRequest->getStudentId(),

            ':id_tutor' => $helpRequest->getTutorId()
        ]);
    }

    // afficher tickets

    public function getAll()
    {

        $sql = "SELECT * FROM help_requests";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}