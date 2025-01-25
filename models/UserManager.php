<?php

class UserManager extends AbstractEntityManager
{
    /**
     * Récupère tous les utilisateurs.
     * @return array : un tableau d'objets User.
     */
    public function getAllUsers(): array
    {
        $sql = "SELECT * FROM user";
        $result = $this->db->query($sql);
        $users = [];
        while ($userData = $result->fetch(PDO::FETCH_ASSOC)) {
            $users[] = new User($userData);
        }
        return $users;
    }
    /**
     * Récupère un utilisateur par son ID.
     * @param int $id
     * @return ?User
     */
    public function getUserById(int $id): ?User
    {
        $sql = "SELECT * FROM user WHERE id = :id";
        try {
            $result = $this->db->query($sql, [":id" => $id]);
            $userData = $result->fetch(PDO::FETCH_ASSOC);
            return $userData ? new User($userData) : null;
        } catch (PDOException $e) {
            error_log("Erreur lors de la recherche de l'utilisateur : " . $e->getMessage());
            return null;
        }
    }
/**
     * Récupère le chemin de la photo de profil d'un utilisateur.
     * @param string|null $profilePicturePath
     * @return string
     */
    public function getProfilePicture(?string $profilePicturePath): string
    {
        $defaultImage = '/assets/img/users/profile-default.svg';
        if (empty($profilePicturePath)) {
            return $defaultImage;
        }
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . $profilePicturePath;
        return file_exists($fullPath) ? $profilePicturePath : $defaultImage;
    }

    /**
     * Récupère un utilisateur par son ID avec sa photo de profil.
     * @param int $id
     * @return ?User
     */
    public function getUserByIdWithProfilePicture(int $id): ?User
    {
        $user = $this->getUserById($id);
        if ($user) {
            $profilePicture = $this->getProfilePicture($user->getProfilePicture());
            $user->setProfilePicture($profilePicture);
        }
        return $user;
    }
    /**
     * Récupère un utilisateur par son email.
     * @param string $email
     * @return ?User
     */
    public function getUserByEmail(string $email): ?User
    {
        $sql = "SELECT * FROM user WHERE email = :email";
        try {
            $stmt = $this->db->query($sql, [':email' => $email]);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            return $userData ? new User($userData) : null;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'utilisateur : " . $e->getMessage());
            return null;
        }
    }


    /**
     * Crée un nouvel utilisateur.
     * @param string $username
     * @param string $email
     * @param string $password
     * @return ?User
     * @throws Exception
     */
    public function createUser(string $username, string $email, string $password): User
    {
        try {
            // Vérification si l'utilisateur avec le email existe déjà
            if ($this->findExistingUser(['email' => $email])) {
                throw new Exception("Un utilisateur avec ce email existe déjà.");
            }
    
            // Préparation des paramètres
            $params = [
                ':username' => $username,
                ':email' => $email,
                ':password' => password_hash($password, PASSWORD_DEFAULT),
                ':profilePicture' => '/assets/img/users/profile-default.svg',
                ':role' => 'user',
                ':is_active' => 1,
                ':created_at' => date('Y-m-d H:i:s'),
                ':updated_at' => date('Y-m-d H:i:s')
            ];
    
            // Inscription de l'utilisateur
            $sql = "INSERT INTO user (username, email, password, profilePicture, role, is_active, created_at, updated_at)
                    VALUES (:username, :email, :password, :profilePicture, :role, :is_active, :created_at, :updated_at)";
    
            $this->db->query($sql, $params);
    
            return $this->getUserByEmail($email);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'inscription: " . $e->getMessage());
        }
    }

    /**
     * Récupère un utilisateur par son nom d'utilisateur.
     * @param string $username
     * @return ?User
     */
    public function getUserByUsername(string $username): ?User
    {
        $sql = "SELECT * FROM user WHERE username = :username";
        try {
            $stmt = $this->db->query($sql, [':username' => $username]);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            return $userData ? new User($userData) : null;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'utilisateur : " . $e->getMessage());
            return null;
        }
    }

    /**
     * Met à jour les informations d'un utilisateur.
     * @param User $user L'utilisateur avec les nouvelles informations.
     * @return bool Retourne vrai si la mise à jour est réussie, sinon faux.
     */
    public function editUser(User $user): bool
    {

        // Construction de la requête SQL avec les données échappées
        $sql = "UPDATE user SET username = :username, email = :email";
        $params = [
            ':username' => $user->getUsername(),
            ':email' => $user->getEmail(),
            ':id' => $user->getId()
        ];
        // Mettre à jour le mot de passe uniquement s'il a été modifié
        if (!empty($user->getPassword())) {
            $sql .= ", password = :password";
            $params[':password'] = $user->getPassword();
        }

        $sql .= " WHERE id = :id";

        try {
            return $this->db->query($sql, $params) !== false;
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de l'utilisateur : " . $e->getMessage());
            return false;
        }
    }

    public function emailExists($email, $userId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM user WHERE email = :email";
        $params = [':email' => $email];

        if ($userId !== null) {
            $sql .= " AND id != :userId";
            $params[':userId'] = $userId;
        }

        try {
            $stmt = $this->db->query($sql, $params);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification de l'email : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Recherche un utilisateur existant par un critère.
     * @param array $criteria Les critères de recherche (clé-valeur).
     * @return bool Retourne vrai si un utilisateur est trouvé, sinon faux.
     */
    public function findExistingUser(array $criteria): bool
    {
        $sql = "SELECT COUNT(*) FROM user WHERE ";
        $conditions = [];
        $params = [];

        foreach ($criteria as $key => $value) {
            $conditions[] = "$key = :$key";
            $params[":$key"] = $value;
        }

        $sql .= implode(" AND ", $conditions);

        try {
            $stmt = $this->db->query($sql, $params);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la recherche d'un utilisateur existant : " . $e->getMessage());
            return false;
        }
    }
    /**
     * Met à jour la photo de profil d'un utilisateur.
     * @param int $userId L'ID de l'utilisateur.
     * @param string $profilePicturePath Le chemin du fichier de la nouvelle photo de profil.
     * @return bool Retourne vrai si la mise à jour est réussie, sinon faux.
     */
    public function updateProfilePicture($userId, $profilePicturePath): bool
    {
        // Préparation de la requête SQL pour mettre à jour l'image de profil
        $sql = "UPDATE user SET profilePicture = :profilePicture WHERE id = :id";

        try {
            return $this->db->query($sql, [
                ':profilePicture' => $profilePicturePath,
                ':id' => $userId
            ]) !== false;
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de la photo de profil : " . $e->getMessage());
            return false;
        }
    }
}
