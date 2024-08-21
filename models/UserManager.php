<?php
class UserManager extends AbstractEntityManager
{
    /**
     * Récupère un user par son login.
     * @param string $login
     * @return ?User
     */
    public function getUserByLogin(string $login): ?User
    {
        $sql = "SELECT * FROM user WHERE login = :login";
        $result = $this->db->query($sql, ['login' => $login]);
        $user = $result->fetch();
        if ($user) {
            return new User($user);
        }
        return null;
    }
    public function createUser(string $username, string $email, string $password): User
    {
        // Ajouter le code pour insérer un utilisateur dans la base de données
        // Par exemple :
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $created_at = date('Y-m-d H:i:s');

        $sql = "INSERT INTO user (username, email, password, created_at) 
            VALUES (:username, :email, :password, :created_at)";

        $stmt = $this->db->query($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':created_at', $created_at);

        // Exécute la requête d'insertion
        $stmt->execute();

        // // Obtenir l'ID de la dernière insertion
        // $lastInsertId = $this->db->lastInsertId();

        // Créer un nouvel utilisateur avec l'ID généré
        $user = new User([
            'pseudo' => $username,
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'created_at' => $created_at,
        ]);

        return $user;
    }
    /**
     * Récupère un utilisateur par son login.
     * @param string $login Le login de l'utilisateur.
     * @return ?User Retourne un objet User si trouvé, sinon null.
     */
    public function getUserByUsername(string $username): ?User
    {
        $sql = "SELECT * FROM user WHERE username = :username";
        $stmt = $this->db->query($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return new User($user);
        }
        return null;
    }

    /**
     * Inscrit un nouvel utilisateur.
     * @param User $user L'utilisateur à inscrire.
     * @return bool Retourne vrai si l'inscription est réussie, sinon faux.
     */
    public function registerUser(User $user): bool
    {
        $sql = "INSERT INTO user (username, email, password, profile_picture, role) 
                VALUES (:username, :email, :password,  :profile_picture, :role)";

        $stmt = $this->db->query($sql);
        $stmt->bindParam(':username', $user->getUsername(), PDO::PARAM_STR);
        $stmt->bindParam(':email', $user->getEmail(), PDO::PARAM_STR);
        $stmt->bindParam(':password', $user->getPassword(), PDO::PARAM_STR);
        $stmt->bindParam(':profile_picture', $user->getProfilePicture(), PDO::PARAM_STR);

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
                email = :email,
                user
                profile_picture = :profile_picture,
                WHERE username = :username";

        $stmt = $this->db->query($sql);
        $stmt->bindParam(':username', $user->getUsername(), PDO::PARAM_STR); // Assurez-vous que vous avez l'identifiant unique pour mettre à jour
        $stmt->bindParam(':email', $user->getEmail(), PDO::PARAM_STR);
        $stmt->bindParam(':profile_picture', $user->getProfilePicture(), PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function getUserById(int $id): ?User
    {
        $sql = "SELECT * FROM user WHERE id = :id";
        $stmt = $this->db->query($sql, ['id' => $id]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $userData ? new User($userData) : null;
    }
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
        $result = $this->db->query($sql, $params);

        return $result->rowCount() > 0;
    }
}
