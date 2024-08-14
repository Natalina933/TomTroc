<?php

class UserManager extends AbstractEntityManager
{
    /**
     * Récupère un utilisateur par son ID.
     * 
     * @param int $id
     * @return User|null
     * */


    public function getUserById(int $id): ?User
    {
        $sql = "SELECT * FROM user WHERE id = ?";
        $stmt = $this->db->query($sql);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        $params = [':id' => $id];

        error_log("SQL Query: " . $sql);
        error_log("Parameters: " . print_r($params, true));
        return $userData ? new User($userData) : null;
    }

    /**
     * Récupère tous les utilisateurs.
     * 
     * @return User[]
     */
    public function getAllUsers(): array
    {
        $sql = "SELECT * FROM user";
        $stmt = $this->db->query($sql);
        $usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'mapToUser'], $usersData);
    }

    /**
     * Crée un nouvel utilisateur.
     * 
     * @param User $user
     * @return bool
     */
    public function createUser(User $user): bool
    {
        $sql = "INSERT INTO user (username, email, password, first_name, last_name, profile_picture, birthdate, phone_number, address, role, is_active, created_at, updated_at, last_login, activation_token, reset_token) 
                VALUES (:username, :email, :password, :first_name, :last_name, :profile_picture, :birthdate, :phone_number, :address, :role, :is_active, :created_at, :updated_at, :last_login, :activation_token, :reset_token)";
        $stmt = $this->db->query($sql);
        return $stmt->execute([
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => password_hash($user->getPassword(), PASSWORD_BCRYPT),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'profile_picture' => $user->getProfilePicture(),
            'birthdate' => $user->getBirthdate() ? $user->getBirthdate()->format('Y-m-d') : null,
            'phone_number' => $user->getPhoneNumber(),
            'address' => $user->getAddress(),
            'role' => $user->getRole(),
            'is_active' => $user->getIsActive(),
            'created_at' => $user->getCreatedAt() ? $user->getCreatedAt()->format('Y-m-d H:i:s') : null,
            'updated_at' => $user->getUpdatedAt() ? $user->getUpdatedAt()->format('Y-m-d H:i:s') : null,
            'last_login' => $user->getLastLogin() ? $user->getLastLogin()->format('Y-m-d H:i:s') : null,
            'activation_token' => $user->getActivationToken(),
            'reset_token' => $user->getResetToken()
        ]);
    }

    /**
     * Met à jour un utilisateur.
     * 
     * @param User $user
     * @return bool
     */
    public function updateUser(User $user): bool
    {
        $sql = "UPDATE user 
                SET username = :username, email = :email, first_name = :first_name, last_name = :last_name, profile_picture = :profile_picture, birthdate = :birthdate, phone_number = :phone_number, address = :address, role = :role, is_active = :is_active, updated_at = :updated_at, last_login = :last_login, activation_token = :activation_token, reset_token = :reset_token 
                WHERE id = :id";
        $stmt = $this->db->query($sql);
        return $stmt->execute([
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'profile_picture' => $user->getProfilePicture(),
            'birthdate' => $user->getBirthdate() ? $user->getBirthdate()->format('Y-m-d') : null,
            'phone_number' => $user->getPhoneNumber(),
            'address' => $user->getAddress(),
            'role' => $user->getRole(),
            'is_active' => $user->getIsActive(),
            'updated_at' => (new DateTime())->format('Y-m-d H:i:s'), // Mise à jour automatique du champ updated_at
            'last_login' => $user->getLastLogin() ? $user->getLastLogin()->format('Y-m-d H:i:s') : null,
            'activation_token' => $user->getActivationToken(),
            'reset_token' => $user->getResetToken()
        ]);
    }

    /**
     * Supprime un utilisateur par son ID.
     * 
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool
    {
        $sql = "DELETE FROM user WHERE id = :id";
        $stmt = $this->db->query($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Mappe un tableau de données utilisateur en objet User.
     * 
     * @param array $userData
     * @return User
     */
    private function mapToUser(array $userData): User
    {
        $user = new User();
        $user->setId($userData['id']);
        $user->setUsername($userData['username']);
        $user->setEmail($userData['email']);
        $user->setPassword($userData['password']); // Généralement, vous ne récupérerez pas le mot de passe en clair
        $user->setFirstName($userData['first_name']);
        $user->setLastName($userData['last_name']);
        $user->setProfilePicture($userData['profile_picture']);
        $user->setBirthdate($userData['birthdate'] ? new DateTime($userData['birthdate']) : null);
        $user->setPhoneNumber($userData['phone_number']);
        $user->setAddress($userData['address']);
        $user->setRole($userData['role']);
        $user->setIsActive((bool)$userData['is_active']);
        $user->setCreatedAt(new DateTime($userData['created_at']));
        $user->setUpdatedAt(new DateTime($userData['updated_at']));
        $user->setLastLogin($userData['last_login'] ? new DateTime($userData['last_login']) : null);
        $user->setActivationToken($userData['activation_token']);
        $user->setResetToken($userData['reset_token']);

        return $user;
    }
}
