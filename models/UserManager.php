<?php
class UserManager extends AbstractEntityManager
{

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
        $sql = "INSERT INTO user (username, email, password, first_name, last_name, profile_picture, birthdate, phone_number, address, role) 
                VALUES (:username, :email, :password, :first_name, :last_name, :profile_picture, :birthdate, :phone_number, :address, :role)";

        $stmt = $this->db->query($sql);
        $stmt->bindParam(':username', $user->getUsername(), PDO::PARAM_STR);
        $stmt->bindParam(':email', $user->getEmail(), PDO::PARAM_STR);
        $stmt->bindParam(':password', $user->getPassword(), PDO::PARAM_STR);
        $stmt->bindParam(':first_name', $user->getFirstName(), PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $user->getLastName(), PDO::PARAM_STR);
        $stmt->bindParam(':profile_picture', $user->getProfilePicture(), PDO::PARAM_STR);
        $stmt->bindParam(':birthdate', $user->getBirthdate(), PDO::PARAM_STR);
        $stmt->bindParam(':phone_number', $user->getPhoneNumber(), PDO::PARAM_STR);
        $stmt->bindParam(':address', $user->getAddress(), PDO::PARAM_STR);
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
                email = :email,
                first_name = :first_name,
                last_name = :last_name,
                profile_picture = :profile_picture,
                birthdate = :birthdate,
                phone_number = :phone_number,
                address = :address
                WHERE username = :username";

        $stmt = $this->db->query($sql);
        $stmt->bindParam(':username', $user->getUsername(), PDO::PARAM_STR); // Assurez-vous que vous avez l'identifiant unique pour mettre à jour
        $stmt->bindParam(':email', $user->getEmail(), PDO::PARAM_STR);
        $stmt->bindParam(':first_name', $user->getFirstName(), PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $user->getLastName(), PDO::PARAM_STR);
        $stmt->bindParam(':profile_picture', $user->getProfilePicture(), PDO::PARAM_STR);
        $stmt->bindParam(':birthdate', $user->getBirthdate(), PDO::PARAM_STR);
        $stmt->bindParam(':phone_number', $user->getPhoneNumber(), PDO::PARAM_STR);
        $stmt->bindParam(':address', $user->getAddress(), PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function getUserById(int $id): ?User
    {
        $sql = "SELECT * FROM user WHERE id = :id";
        $stmt = $this->db->query($sql, ['id' => $id]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $userData ? new User($userData) : null;
    }
}
