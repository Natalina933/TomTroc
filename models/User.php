<?php

/**
 * Entité User : un utilisateur est défini par les champs
 * id, username, email, password, first_name, last_name, profile_picture, birthdate, phone_number, address, role, is_active, created_at, updated_at, last_login, activation_token, reset_token
 */
class User extends AbstractEntity
{
    protected int $id;
    private string $username;
    private string $email;
    private string $password;
    private ?string $firstName;
    private ?string $lastName;
    private ?string $profilePicture;
    private ?string $birthdate;
    private ?string $phoneNumber;
    private ?string $address;
    private string $role;
    private bool $isActive;
    private string $createdAt;
    private ?string $updatedAt;
    private ?string $lastLogin;
    private ?string $activationToken;
    private ?string $resetToken;

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
        $this->firstName = $data['first_name'] ?? null;
        $this->lastName = $data['last_name'] ?? null;
        $this->profilePicture = $data['profile_picture'] ?? null;
        $this->birthdate = $data['birthdate'] ?? null;
        $this->phoneNumber = $data['phone_number'] ?? null;
        $this->address = $data['address'] ?? null;
        $this->role = $data['role'];
        $this->isActive = (bool) $data['is_active'];
        $this->createdAt = $data['created_at'];
        $this->updatedAt = $data['updated_at'] ?? null;
        $this->lastLogin = $data['last_login'] ?? null;
        $this->activationToken = $data['activation_token'] ?? null;
        $this->resetToken = $data['reset_token'] ?? null;
    }

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
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }
    public function getLastName(): ?string
    {
        return $this->lastName;
    }
    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }
    public function getBirthdate(): ?string
    {
        return $this->birthdate;
    }
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }
    public function getAddress(): ?string
    {
        return $this->address;
    }
    public function getRole(): string
    {
        return $this->role;
    }
    public function isActive(): bool
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

    // Setters pour chaque propriété
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
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }
    public function setProfilePicture(?string $profilePicture): void
    {
        $this->profilePicture = $profilePicture;
    }
    public function setBirthdate(?string $birthdate): void
    {
        $this->birthdate = $birthdate;
    }
    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }
    public function setAddress(?string $address): void
    {
        $this->address = $address;
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
