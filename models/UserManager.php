<?php

class UserManager extends AbstractEntityManager
{
    /**
     * Récupère un utilisateur par son login.
     * @param string $login
     * @return ?User
     */
    public function getUserByLogin(string $login): ?User
    {
        $sql = "SELECT * FROM user WHERE login = :login";
        $stmt = $this->db->query($sql, [':login' => $login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ? new User($user) : null;
    }

    /**
     * Crée un nouvel utilisateur.
     * @param string $username
     * @param string $login
     * @param string $password
     * @return ?User
     * @throws Exception
     */
    public function createUser(string $username, string $login, string $password): ?User
    {
        try {
            // Vérification si l'utilisateur avec le login existe déjà
            if ($this->findExistingUser(['login' => $login])) {
                throw new Exception("Un utilisateur avec ce login existe déjà.");
            }
            var_dump('toto');
            // Inscription de l'utilisateur
            $sql = "INSERT INTO user (username, login, password, profilePicture, is_available, role, is_active, created_at)
                    VALUES (:username, :login, :password, :profilePicture, :is_available, :role, :is_active, :created_at)";
            $stmt = $this->db->query($sql, [
                ':username' => $username,
                ':login' => $login,
                ':password' => password_hash($password, PASSWORD_DEFAULT),
                ':profilePicture' => null,
                ':is_available' => 1,
                ':role' => 'user',
                ':is_active' => 1,
                ':created_at' => date('Y-m-d H:i:s')
            ]);
            $stmt->execute();
            var_dump('toto');
            die;

            // Récupérer l'utilisateur créé après insertion
            return $this->getUserByLogin($login);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'inscription: " . $e->getMessage());
        }
    }

    /**
     * Récupère un utilisateur par son ID.
     * @param int $id
     * @return ?User
     */
    public function getUserById(int $id): ?User
    {
        try {
            // Récupérer l'utilisateur
            $sql = "SELECT * FROM user WHERE id = :id";
            $stmt = $this->db->query($sql, [':id' => $id]);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            return $data ? new User($data) : null;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la recherche de l'utilisateur: " . $e->getMessage());
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
        $stmt = $this->db->query($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ? new User($user) : null;
    }

    /**
     * Inscrit un nouvel utilisateur.
     * @param User $user L'utilisateur à inscrire.
     * @return bool Retourne vrai si l'inscription est réussie, sinon faux.
     */
    public function registerUser(User $user): bool
    {
        $sql = "INSERT INTO user (username, login, password, profilePicture, role) 
                VALUES (:username, :login, :password, :profilePicture, :role)";
        $stmt = $this->db->query($sql);
        $stmt->bindParam(':username', $user->getUsername(), PDO::PARAM_STR);
        $stmt->bindParam(':login', $user->getLogin(), PDO::PARAM_STR);
        $stmt->bindParam(':password', $user->getPassword(), PDO::PARAM_STR);
        $stmt->bindParam(':profilePicture', $user->getProfilePicture(), PDO::PARAM_STR);
        $stmt->bindParam(':role', $user->getRole(), PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Met à jour les informations d'un utilisateur.
     * @param User $user L'utilisateur avec les nouvelles informations.
     * @return bool Retourne vrai si la mise à jour est réussie, sinon faux.
     */
    public function updateUser(User $user): bool
    {
        $sql = "UPDATE user SET 
                username = :username,
                login = :login,
                password = :password,
                profilePicture = :profilePicture,
                role = :role,
                is_active = :is_active
                WHERE id = :id";
        $stmt = $this->db->query($sql);
        $stmt->bindParam(':id', $user->getId(), PDO::PARAM_INT);
        $stmt->bindParam(':username', $user->getUsername(), PDO::PARAM_STR);
        $stmt->bindParam(':login', $user->getLogin(), PDO::PARAM_STR);
        $stmt->bindParam(':password', $user->getPassword(), PDO::PARAM_STR);
        $stmt->bindParam(':profilePicture', $user->getProfilePicture(), PDO::PARAM_STR);
        $stmt->bindParam(':role', $user->getRole(), PDO::PARAM_STR);
        $stmt->bindParam(':is_active', $user->getIsActive(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Recherche un utilisateur existant par un critère.
     * @param array $criteria Les critères de recherche (clé-valeur).
     * @return bool Retourne vrai si un utilisateur est trouvé, sinon faux.
     */
    public function findExistingUser(array $criteria): bool
    {
        $sql = "SELECT * FROM user";
        $params = [];

        if (!empty($criteria)) {
            $conditions = [];
            foreach ($criteria as $key => $value) {
                $conditions[] = "$key = :$key";
                $params[$key] = $value;
            }
            $sql .= " WHERE " . implode(" OR ", $conditions);
        }
        var_dump($params, $sql);
        $stmt = $this->db->query($sql, $params);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
    /**
     * Met à jour la photo de profil d'un utilisateur.
     * @param int $userId L'ID de l'utilisateur.
     * @param string $profilePicturePath Le chemin du fichier de la nouvelle photo de profil.
     * @return bool Retourne vrai si la mise à jour est réussie, sinon faux.
     */
    public function updateProfilePicture($userId, $profilePicturePath): bool
    {

        // Requête SQL pour mettre à jour la photo de profil de l'utilisateur
        $sql = "UPDATE user SET profilePicture = :profilePicture WHERE id = :id";
        $stmt = $this->db->query($sql);
        // Lier les paramètres à la requête
        $stmt->bindParam(':profilePicture', $profilePicturePath);
        $stmt->bindParam(':id', $userId);
        // Exécution de la requête et retour du succès ou de l'échec
        return $stmt->execute();
    }
}
