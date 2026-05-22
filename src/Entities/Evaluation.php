<?php

/**
 * Évaluation d'un ticket résolu — note obligatoire entre 1 et 5.
 */
class Evaluation
{
    private $id;
    private $note;
    private $commentaire;
    private $id_help_request;

    public function __construct($note, $commentaire, $id_help_request, $id = null)
    {
        if (!is_numeric($note) || $note < 1 || $note > 5) {
            throw new Exception('La note doit être comprise entre 1 et 5.');
        }
        $this->id = $id;
        $this->note = (int) $note;
        $this->commentaire = $commentaire;
        $this->id_help_request = $id_help_request;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNote()
    {
        return $this->note;
    }

    public function getCommentaire()
    {
        return $this->commentaire;
    }

    public function getIdHelpRequest()
    {
        return $this->id_help_request;
    }
}
