<?php
class UserManager extends AbstractEntityManager
{
    /**
     * Récupère un utilisateur par son ID.
     * 
     * @param int $id
     * @return User|null
     */
    public function getUserById(int $id): ?User
    {
        $sql = "SELECT * FROM user WHERE id = ?";
        $stmt = $this->db->query($sql);
        $stmt->execute([$id]); // Bind param for ? placeholder
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

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
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($user) => new User($user), $users);
    }

    /**
     * Crée un nouvel utilisateur.
     * 
     * @param User $user
     * @return bool
     */
    public function createUser(User $user): bool
    {
        $sql = "INSERT INTO user (name, email, password, photo) VALUES (:name, :email, :password, :photo)";
        $stmt = $this->db->query($sql);
        return $stmt->execute([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => password_hash($user->getPassword(), PASSWORD_BCRYPT),
            'photo' => $user->getPhoto()
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
        $sql = "UPDATE user SET name = :name, email = :email, photo = :photo WHERE id = :id";
        $stmt = $this->db->query($sql);
        return $stmt->execute([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'photo' => $user->getPhoto(),
            'id' => $user->getId()
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
}
