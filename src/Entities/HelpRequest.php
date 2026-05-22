<?php

require_once __DIR__ . '/../Enums/Status.php';
require_once __DIR__ . '/User.php';

/**
 * Ticket d'aide — règles métier assignTo() et resolve().
 */
class HelpRequest
{
    private $id;
    private $titre;
    private $description;
    private $technologie;
    private $status;
    private $commentaire;
    private $id_student;
    private $id_tutor;
    private $created_at;

    public function __construct($id, $titre, $description, $technologie, $id_student, $id_tutor = null, $status = Status::PENDING, $commentaire = null, $created_at = null)
    {
        if (!Status::isValid($status)) {
            $status = Status::PENDING;
        }
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->technologie = $technologie;
        $this->id_student = $id_student;
        $this->id_tutor = $id_tutor;
        $this->status = $status;
        $this->commentaire = $commentaire;
        $this->created_at = $created_at;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitre()
    {
        return $this->titre;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getTechnologie()
    {
        return $this->technologie;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getCommentaire()
    {
        return $this->commentaire;
    }

    public function getIdStudent()
    {
        return $this->id_student;
    }

    public function getIdTutor()
    {
        return $this->id_tutor;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    }

    /**
     * Assigne un tuteur : interdit l'auto-assignation (même ID).
     */
    public function assignTo(User $tutor)
    {
        if ($tutor->getId() === $this->id_student) {
            throw new Exception('Un étudiant ne peut pas s\'assigner lui-même.');
        }
        $this->status = Status::ASSIGNED;
        $this->id_tutor = $tutor->getId();
    }

    /**
     * Clôture le ticket (statut RESOLVED).
     */
    public function resolve()
    {
        $this->status = Status::RESOLVED;
    }
}
