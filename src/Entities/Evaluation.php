<?php

/**
 * Évaluation d'un ticket résolu — note obligatoire entre 1 et 5.
 */
class Evaluation
{
    private ?int $id;
    private int $note;
    private ?string $commentaire;
    private int $id_help_request;

    public function __construct(
        int $note,
        ?string $commentaire,
        int $id_help_request,
        ?int $id = null
    ) {
        if ($note < 1 || $note > 5) {
            throw new Exception('La note doit être comprise entre 1 et 5.');
        }
        $this->id = $id;
        $this->note = $note;
        $this->commentaire = $commentaire;
        $this->id_help_request = $id_help_request;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): int
    {
        return $this->note;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function getIdHelpRequest(): int
    {
        return $this->id_help_request;
    }
}
