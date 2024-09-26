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
        while ($user = $result->fetch()) {
            $users[] = new User($user);
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
        try {
            // Exécution directe de la requête SQL avec l'ID spécifié
            $sql = "SELECT * FROM user WHERE id = $id";
            $result = $this->db->query($sql, [":id" => $id]);
            $user = $result->fetch();
            // Vérification des résultats
            if ($user) {


                return new User($user);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la recherche de l'utilisateur : " . $e->getMessage());
        }
    }

    
    /**
     * Récupère un utilisateur par son email.
     * @param string $email
     * @return ?User
     */
    public function getUserByEmail(string $email): ?User
    {
        $sql = "SELECT * FROM user WHERE email = :email";
        $stmt = $this->db->query($sql, [':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        var_dump($user);

        return $user ? new User($user) : null;
    }

    /**
     * Crée un nouvel utilisateur.
     * @param string $username
     * @param string $email
     * @param string $password
     * @return ?User
     * @throws Exception
     */
    public function createUser(string $username,  string $email, string $password): ?User

    {
        try {
            // Vérification si l'utilisateur avec le email existe déjà
            if ($this->findExistingUser(['email' => $email])) {
                throw new Exception("Un utilisateur avec ce email existe déjà.");
            }
            // var_dump('toto');
            // Inscription de l'utilisateur
            $sql = "INSERT INTO user (username, email, password,  role, is_active)
                    VALUES (:username, :email, :password, :role, :is_active)";
            $stmt = $this->db->query($sql, [
                ':username' => $username,
                ':email' => $email,
                ':password' => password_hash($password, PASSWORD_DEFAULT),
                ':profilePicture'=> '/assets/img/users/profile-default.svg',
                ':role' => 'user',
                ':is_active' => 1,
                ':createdAt' => date('Y-m-d H:i:s'),
                ':updatedAt' => date('Y-m-d H:i:s')
            ]);
            $stmt->execute();
            // var_dump('toto');
            // die;

            // Récupérer l'utilisateur créé après insertion
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
        $stmt = $this->db->query($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ? new User($user) : null;
    }


    /**
     * Met à jour les informations d'un utilisateur.
     * @param User $user L'utilisateur avec les nouvelles informations.
     * @return bool Retourne vrai si la mise à jour est réussie, sinon faux.
     */
    public function editUser(User $user): bool
    {
        // Requête SQL pour mettre à jour les informations de l'utilisateur
        $sql = "UPDATE user 
            SET username = :username, email = :email, password = :password, profilePicture = :profilePicture, role = :role 
            WHERE id = :id";

        // Préparation de la requête
        $stmt = $this->db->query($sql);

        // Liaison des paramètres avec les valeurs de l'objet User
        $stmt->bindParam(':username', $user->getUsername(), PDO::PARAM_STR);
        $stmt->bindParam(':email', $user->getemail(), PDO::PARAM_STR);
        $stmt->bindParam(':password', $user->getPassword(), PDO::PARAM_STR);
        $stmt->bindParam(':profilePicture', $user->getProfilePicture(), PDO::PARAM_STR);
        $stmt->bindParam(':role', $user->getRole(), PDO::PARAM_STR);
        $stmt->bindParam(':id', $user->getId(), PDO::PARAM_INT);

        // Exécution de la requête et retour du succès ou de l'échec
        return $stmt->execute();
    }
    public function emailExists($email, $userId = null): bool
    {
        // Vérification de l'ID utilisateur et email pour éviter les injections
        $email = htmlspecialchars($email, ENT_QUOTES);
        $userId = intval($userId);

        // Construction de la requête SQL
        $sql = "SELECT COUNT(*) FROM user WHERE email = '{$email}'";

        // Si un userId est fourni, on exclut cet utilisateur
        if ($userId !== null) {
            $sql .= " AND id != {$userId}";
        }

        // Exécution de la requête avec query()
        $stmt = $this->db->query($sql);

        // Retourner true si l'email existe déjà
        return $stmt->fetchColumn() > 0;
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
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        // var_dump($params, $sql);
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
        // Préparation de la requête SQL pour mettre à jour l'image de profil
        $sql = "UPDATE user SET profilePicture = :profilePicture WHERE id = :id";

        // Récupération de l'instance PDO via DBManager
        $db = DBManager::getInstance()->getPDO();

        // Préparation de la requête
        $stmt = $db->prepare($sql);

        // Liaison des paramètres
        $stmt->bindParam(':profilePicture', $profilePicturePath, PDO::PARAM_STR);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

        // Exécution de la requête et retour du succès ou de l'échec
        return $stmt->execute();
    }
    /**
     * Compte le nombre de livres associés à un utilisateur.
     * @param int $userId L'ID de l'utilisateur.
     * @return int Le nombre de livres appartenant à l'utilisateur.
     */
}
