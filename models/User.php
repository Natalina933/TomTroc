<?php

/**
 * Entité User, un utilisateur est défini par les champs
 * id, username, email, password, first_name, last_name, profile_picture, birthdate, phone_number, address, role, is_active, created_at, updated_at, last_login, activation_token, reset_token
 */
class User extends AbstractEntity
{
    protected int $id;
    private string $username = "";
    private string $email = "";
    private string $password = "";
    private ?string $firstName = null;
    private ?string $lastName = null;
    private ?string $profilePicture = null;
    private ?DateTime $birthdate = null;
    private ?string $phoneNumber = null;
    private ?string $address = null;
    private string $role = "user";
    private bool $isActive = true;
    private ?DateTime $createdAt = null;
    private ?DateTime $updatedAt = null;
    private ?DateTime $lastLogin = null;
    private ?string $activationToken = null;
    private ?string $resetToken = null;

    // Getter et Setter pour id
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    // Getter et Setter pour username
    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        if (empty($username)) {
            throw new InvalidArgumentException('Le nom d\'utilisateur ne peut pas être vide.');
        }
        $this->username = $username;
    }

    // Getter et Setter pour email
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('L\'adresse email n\'est pas valide.');
        }
        $this->email = $email;
    }

    // Getter et Setter pour password
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        if (empty($password)) {
            throw new InvalidArgumentException('Le mot de passe ne peut pas être vide.');
        }
        $this->password = $password;
    }

    // Getter et Setter pour first_name
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    // Getter et Setter pour last_name
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    // Getter et Setter pour profile_picture
    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): void
    {
        $this->profilePicture = $profilePicture;
    }

    // Getter et Setter pour birthdate
    public function getBirthdate(): ?DateTime
    {
        return $this->birthdate;
    }

    public function setBirthdate(?string $birthdate, string $format = 'Y-m-d'): void
    {
        if ($birthdate) {
            $date = DateTime::createFromFormat($format, $birthdate);
            if (!$date) {
                throw new InvalidArgumentException('Format de date invalide pour birthdate.');
            }
            $this->birthdate = $date;
        } else {
            $this->birthdate = null;
        }
    }

    // Getter et Setter pour phone_number
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    // Getter et Setter pour address
    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    // Getter et Setter pour role
    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        if (!in_array($role, ['user', 'admin', 'moderator'])) {
            throw new InvalidArgumentException('Rôle non valide.');
        }
        $this->role = $role;
    }

    // Getter et Setter pour is_active
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    // Getter et Setter pour created_at
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string|DateTime $createdAt, string $format = 'Y-m-d H:i:s'): void
    {
        if (is_string($createdAt)) {
            $createdAt = DateTime::createFromFormat($format, $createdAt);
            if (!$createdAt) {
                throw new InvalidArgumentException('Format de date invalide pour created_at.');
            }
        }
        $this->createdAt = $createdAt;
    }

    // Getter et Setter pour updated_at
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(string|DateTime|null $updatedAt, string $format = 'Y-m-d H:i:s'): void
    {
        if ($updatedAt === null) {
            $this->updatedAt = null;
        } elseif (is_string($updatedAt)) {
            $updatedAt = DateTime::createFromFormat($format, $updatedAt);
            if (!$updatedAt) {
                throw new InvalidArgumentException('Format de date invalide pour updated_at.');
            }
        } else {
            $this->updatedAt = $updatedAt;
        }
    }

    // Getter et Setter pour last_login
    public function getLastLogin(): ?DateTime
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?string $lastLogin, string $format = 'Y-m-d H:i:s'): void
    {
        if ($lastLogin) {
            $date = DateTime::createFromFormat($format, $lastLogin);
            if (!$date) {
                throw new InvalidArgumentException('Format de date invalide pour last_login.');
            }
            $this->lastLogin = $date;
        } else {
            $this->lastLogin = null;
        }
    }

    // Getter et Setter pour activation_token
    public function getActivationToken(): ?string
    {
        return $this->activationToken;
    }

    public function setActivationToken(?string $activationToken): void
    {
        $this->activationToken = $activationToken;
    }

    // Getter et Setter pour reset_token
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): void
    {
        $this->resetToken = $resetToken;
    }
}
