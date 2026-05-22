<?php

require_once __DIR__ . '/../Enums/Status.php';
require_once __DIR__ . '/User.php';

/**
 * Ticket d'aide — règles métier assignTo() et resolve().
 */
class HelpRequest
{
    private ?int $id;
    private string $titre;
    private string $description;
    private string $technologie;
    private Status $statut;
    private ?string $commentaire;
    private int $id_student;
    private ?int $id_tutor;
    private ?string $created_at;

    public function __construct(
        ?int $id,
        string $titre,
        string $description,
        string $technologie,
        int $id_student,
        ?int $id_tutor = null,
        Status $statut = Status::EN_ATTENTE,
        ?string $commentaire = null,
        ?string $created_at = null
    ) {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->technologie = $technologie;
        $this->id_student = $id_student;
        $this->id_tutor = $id_tutor;
        $this->statut = $statut;
        $this->commentaire = $commentaire;
        $this->created_at = $created_at;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTechnologie(): string
    {
        return $this->technologie;
    }

    public function getStatut(): Status
    {
        return $this->statut;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function getIdStudent(): int
    {
        return $this->id_student;
    }

    public function getIdTutor(): ?int
    {
        return $this->id_tutor;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setCommentaire(?string $commentaire): void
    {
        $this->commentaire = $commentaire;
    }

    /**
     * Assigne un tuteur : interdit l'auto-assignation (même ID).
     */
    public function assignTo(User $tutor): void
    {
        if ($tutor->getId() === $this->id_student) {
            throw new Exception('Un étudiant ne peut pas s\'assigner lui-même.');
        }
        $this->statut = Status::ASSIGNE;
        $this->id_tutor = $tutor->getId();
    }

    /**
     * Clôture le ticket (statut RESOLUE).
     */
    public function resolve(): void
    {
        $this->statut = Status::RESOLUE;
    }
}
