<?php

/**
 * Entité User : un utilisateur est défini par les champs
 * id, username, login, password, profile_picture, isAvailable, role, is_active, created_at, updated_at, last_login, activation_token, reset_token
 */
class User extends AbstractEntity
{
    private int $id;
    private string $username;
    private string $login;
    private string $password;
    private ?string $profilePicture;
    // private bool $isAvailable;
    private string $role;
    private bool $isActive;
    private string $createdAt;
    private ?string $updatedAt;
    // private ?string $lastLogin;
    // private ?string $activationToken;
    // private ?string $resetToken;


    // Getters pour chaque propriété
    public function getId(): int
    {
        return $this->id;
    }
    public function getUsername(): string
    {
        return $this->username;
    }
    public function getLogin(): string
    {
        return $this->login;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
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
    public function getLastLogin(): ?string
    {
        return $this->lastLogin;
    }
    public function getActivationToken(): ?string
    {
        return $this->activationToken;
    }
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }
    public function getIsAvailable(): bool
    {
        return $this->isAvailable;
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
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
    public function setProfilePicture(?string $profilePicture): void
    {
        $this->profilePicture = $profilePicture;
    }
    public function setIsAvailable(bool $isAvailable): void
    {
        $this->isAvailable = $isAvailable;
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
    public function setLastLogin(?string $lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }
    public function setActivationToken(?string $activationToken): void
    {
        $this->activationToken = $activationToken;
    }
    public function setResetToken(?string $resetToken): void
    {
        $this->resetToken = $resetToken;
    }
}
