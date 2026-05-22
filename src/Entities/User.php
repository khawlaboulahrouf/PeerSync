<?php

/**
 * Entité utilisateur (étudiant ou tuteur).
 * Aucune requête SQL ici — persistance via UserRepository.
 */
class User
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $role;

    public function __construct($id, $name, $email, $password, $role)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function isTutor()
    {
        return $this->role === 'tutor';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }
}
