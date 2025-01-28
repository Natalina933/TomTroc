<?php

/**
 * Classe qui gère les books.
 */
class bookManager extends AbstractEntityManager
{
    private string $uploadDir = 'assets/img/books/';
    /**
     * Récupère tous les books.
     * @return array : un tableau d'objets book.
     */
    public function getAllBooks(): array
    {
        $sql = "SELECT * FROM book";
        $result = $this->db->query($sql);
        $books = [];

        while ($bookData = $result->fetch(PDO::FETCH_ASSOC)) {
            $books[] = new Book($bookData);
        }
        return $books;
    }
    /**
     * Récupère les books selon les critères, les ordres et la limite spécifiés.
     * @param array $criteria : Critères de filtrage.
     * @param array $orders : Ordres de tri.
     * @param int $limit : Limite de résultats.
     * @return array : un tableau d'objets book.
     */
    public function getBooks(array $criteria = [], array $orders = [], int $limit = 0): array
    {
        $sql = "SELECT * FROM book";
        $params = [];

        if (!empty($criteria)) {
            $conditions = [];
            foreach ($criteria as $key => $value) {
                $conditions[] = "$key = :$key";
                $params[$key] = $value;
            }
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        // Tri par date de création pour récupérer les derniers livres ajoutés
        $sql .= " ORDER BY createdAt DESC";

        // Limiter les résultats aux 4 derniers livres
        $sql .= " LIMIT 4"; // Limite à 4 livres

        $result = $this->db->query($sql, $params);
        $books = [];
        while ($bookData = $result->fetch(PDO::FETCH_ASSOC)) {
            $books[] = new Book($bookData);
        }

        return $books;
    }

    public function getBookById(int $id): ?Book
    {
        $sql = "SELECT * FROM book WHERE id = :id";
        $params = [':id' => $id];
        $result = $this->db->query($sql, $params);
        $bookData = $result->fetch(PDO::FETCH_ASSOC);
        error_log("Résultat de getBookById pour ID $id : " . print_r($bookData, true));

        return $bookData ? new Book($bookData) : null;
    }

    public function getAllBooksByUserId(int $userId): array
    {
        $sql = "SELECT * FROM book WHERE user_id = " . (int)$userId;
        $result = $this->db->query($sql);
        $books = [];
        while ($bookData = $result->fetch(PDO::FETCH_ASSOC)) {
            $books[] = new Book($bookData);
        }
        return $books;
    }
    /**
     * Ajoute ou modifie un book.
     * @param Book $book : le book à ajouter ou modifier.
     * @return void
     */
    public function addOrUpdateBook(Book $book): void
    {

        if ($book->getId() === null || $book->getId() == -1) {
            $this->addBook($book);
        } else {
            $this->editBook($book);
        }
    }

    public function addBook(Book $book): bool
    {
        try {
            $sql = "INSERT INTO book (user_id, title, author, img, description, available, createdAt, updatedAt) 
            VALUES (:user_id, :title, :author, :img, :description, :available, :createdAt, :updatedAt)";
            $params = [
                ':user_id' => $book->getUserId(),
                ':title' => $book->getTitle(),
                ':author' => $book->getAuthor(),
                ':img' => $book->getImg(),
                ':description' => $book->getDescription(),
                ':available' => $book->isAvailable() ? 1 : 0,
                ':createdAt' => date('Y-m-d H:i:s'),
                ':updatedAt' => date('Y-m-d H:i:s')
            ];

            error_log("Paramètres de la requête : " . print_r($params, true));

            $result = $this->db->query($sql, $params);

            if ($result === false) {
                throw new Exception("Erreur lors de l'insertion du livre dans la base de données.");
            }

            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout du livre : " . $e->getMessage());
            throw new Exception("Erreur lors de l'ajout du livre : " . $e->getMessage());
        }
    }
    public function editBook(Book $book): bool
    {
        try {
            // Validation des données avant l'exécution de la requête
            if (empty($book->getTitle()) || empty($book->getAuthor())) {
                throw new InvalidArgumentException('Le titre et l\'auteur du livre doivent être renseignés.');
            }

            // Requête SQL pour mettre à jour le livre
            $sql = "UPDATE book SET
                title = :title,
                author = :author,
                description = :description,
                img = :img,
                available = :available,
                updatedAt = :updatedAt
                WHERE id = :id";

            // Paramètres de la requête
            $params = [
                ':title' => $book->getTitle(),
                ':author' => $book->getAuthor(),
                ':description' => $book->getDescription(),
                ':img' => $book->getImg(),
                ':available' => $book->isAvailable(),
                ':updatedAt' => $book->getUpdatedAt() ? $book->getUpdatedAt()->format('Y-m-d H:i:s') : null,
                ':id' => $book->getId()
            ];

            // Exécution de la requête
            return $this->db->query($sql, $params) !== false;
        } catch (InvalidArgumentException $e) {
            // Erreur de validation des données
            error_log("Erreur de validation des données du livre : " . $e->getMessage());
            throw $e; // Relancer l'exception pour gérer au niveau supérieur
        } catch (PDOException $e) {
            // Erreur lors de l'exécution de la requête SQL
            error_log("Erreur SQL lors de la mise à jour du livre (ID: " . $book->getId() . ") : " . $e->getMessage());
            throw new Exception("Erreur lors de la mise à jour du livre : " . $e->getMessage());
        } catch (Exception $e) {
            // Toute autre exception générique
            error_log("Erreur générale lors de la mise à jour du livre (ID: " . $book->getId() . ") : " . $e->getMessage());
            throw new Exception("Erreur générale lors de la mise à jour du livre : " . $e->getMessage());
        }
    }
    public function updateBookImage(int $bookId, string $newImagePath): bool
    {
        try {
            // Récupérer l'ancien nom d'image
            $oldImageQuery = "SELECT img FROM book WHERE id = :id";
            $oldImageResult = $this->db->query($oldImageQuery, [':id' => $bookId]);
            $oldImage = $oldImageResult->fetchColumn();

            // Mettre à jour l'enregistrement avec la nouvelle image
            $updateQuery = "UPDATE book SET img = :newImage, updatedAt = :updatedAt WHERE id = :id";
            $params = [
                ':newImage' => $newImagePath,
                ':updatedAt' => date('Y-m-d H:i:s'),
                ':id' => $bookId
            ];
            $result = $this->db->query($updateQuery, $params);

            // Supprimer l'ancienne image si elle existe
            if ($oldImage && $oldImage !== $newImagePath) {
                $oldImagePath = __DIR__ . '/assets/img/books/' . basename($oldImage);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            return $result !== false;
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de l'image du livre : " . $e->getMessage());
            return false;
        }
    }


    public function countUserBooks(int $userId): int
    {
        $sql = "SELECT COUNT(*) as total_books FROM book WHERE user_id = :userId";
        $stmt = $this->db->query($sql, ['userId' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total_books'];
    }
    public function getBookWithSellerInfo(int $bookId): ?array
    {
        $sql = "SELECT  b.id AS book_id, 
                        b.title AS book_title, 
                        b.author AS book_author, 
                        b.img AS book_img, 
                        b.available AS book_availability, 
                        u.username AS seller_username, 
                        u.profilePicture AS seller_profilePicture
                FROM book b
                INNER JOIN user u ON b.user_id = u.id
                WHERE b.id = " . (int)$bookId;

        $result = $this->db->query($sql);
        $bookData = $result->fetch(PDO::FETCH_ASSOC);

        if (!$bookData) {
            return null;
        }

        return [
            'id' => $bookData['book_id'],
            'title' => $bookData['book_title'],
            'author' => $bookData['book_author'],
            'img' => $bookData['book_img'],
            'is_available' => $bookData['book_availability'],
            'seller' => [
                'username' => $bookData['seller_username'],
                'profilePicture' => $bookData['seller_profilePicture'],
            ],
        ];
    }


    /**
     * Supprime un book.
     * @param int $id : l'id du book à supprimer.
     * @return void
     */
    public function deleteBook($bookIds): bool
    {
        if (!is_array($bookIds)) {
            $bookIds = [$bookIds];
        }

        // Sécurisation des IDs
        $bookIds = array_map('intval', $bookIds);
        $idList = implode(',', $bookIds);

        $sql = "DELETE FROM book WHERE id IN ($idList)";

        try {
            $result = $this->db->query($sql);
            return $result !== false && $result->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression du/des livre(s) : " . $e->getMessage());
            return false;
        }
    }
}
