<?php

/**
 * Entité User : un utilisateur est défini par les champs
 * id, username, login, password, profilePicture, isAvailable, role, is_active, created_at, updated_at
 */

class User extends AbstractEntity
{
    protected int $id;
    private string $username;
    private string $email;
    private string $password;
    private string $profilePicture;
    private string $role;
    private bool $isActive;
    private string $createdAt;
    private ?string $updatedAt;


    // Getters pour chaque propriété
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
        return $this->profilePicture ?: "/assets/img/users/profile-default.svg";
    }
    public function getRole(): string
    {
        return $this->role;
    }
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }


    // Setters pour chaque propriété
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function setProfilePicture(?string $profilePicture): void
    {
        $this->profilePicture = $profilePicture;
    }
    public function setRole(string $role): void
    {
        $this->role = $role;
    }
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
    public function setUpdatedAt(?string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
