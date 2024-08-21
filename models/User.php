<?php

/**
 * Entité User : un utilisateur est défini par les champs
 * id, username, email, password, profile_picture
 */
class User extends AbstractEntity
{
    protected int $id;
    private string $username;
    private string $email;
    private string $password;
    private ?string $profilePicture;

    /**
     * Constructeur pour initialiser l'objet User avec les données fournies.
     *
     * @param array $data Données pour initialiser l'utilisateur.
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->username = $data['username'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->profilePicture = $data['profile_picture'] ?? null;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setProfilePicture(?string $profilePicture): void
    {
        $this->profilePicture = $profilePicture;
    }
}
