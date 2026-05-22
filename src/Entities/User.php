<?php

/**
 * Entité utilisateur (étudiant ou tuteur).
 * Aucune requête SQL ici — persistance via UserRepository.
 */
class User
{
    private ?int $id;
    private string $name;
    private string $email;
    private string $password;
    private string $role;

    public function __construct(
        ?int $id,
        string $name,
        string $email,
        string $password,
        string $role
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function isTutor(): bool
    {
        return $this->role === 'tutor';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }
}
