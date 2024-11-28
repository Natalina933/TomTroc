<?php

/**
 * Classe qui gère les books.
 */
class bookManager extends AbstractEntityManager
{
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

        if (!empty($orders)) {
            $orderConditions = [];
            foreach ($orders as $key => $value) {
                $orderConditions[] = "$key $value";
            }
            $sql .= " ORDER BY " . implode(", ", $orderConditions);
        }

        if ($limit > 0) {
            $sql .= " LIMIT $limit";
            $params[':limit'] = $limit;
        }

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

        $stmt = $this->db->query($sql, ['id' => $id]);

        $bookData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $bookData ? new Book($bookData) : null;
    }

    public function getAllBooksByUserId(int $userId): array
    {
        $sql = "SELECT * FROM book WHERE user_id = :id_user";
        $result = $this->db->query($sql, ['id_user' => $userId]);
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

        if ($book->getId() == -1 || $book->getId() === null) {
            $this->addBook($book); // Ajout d'un nouveau livre
        } else {
            $this->editBook($book); // Modification du livre existant
        }
    }

    public function addBook(Book $book): bool
    {

        try {
            $sql = "INSERT INTO book (user_id, title, author, description, img, available, date_creation) 
                VALUES (:user_id, :title, :author, :description, :img, :available, NOW())";
            $result = $this->db->query($sql, [
                'user_id' => $book->getUserId(),
                'title' => $book->getTitle(),
                'author' => $book->getAuthor(),
                'description' => $book->getDescription(),
                'img' => $book->getImg(),
                'available' => $book->isAvailable()
            ]);
            if ($result === false) {
                var_dump("Erreur SQL lors de l'ajout du livre.");
            }
            return $result !== false; // Retourne true si l'insertion a réussi
        } catch (Exception $e) {

            var_dump("Erreur SQL: " . $e->getMessage());
            error_log($e->getMessage());
            return false;
        }
    }


    public function editBook(Book $book): bool
    {

        $sql = "UPDATE book SET title = :title, author = :author, description = :description, 
        img = :img, available = :available, user_id = :userId 
        WHERE id = :id";
        $result = $this->db->query($sql, [
            'id' => $book->getId(),
            'userId' => $book->getUserId(),
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'description' => $book->getDescription(),
            'img' => $book->getImg(),
            'available' => $book->isAvailable()
        ]);
        return $result !== false;
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
                WHERE b.id = :bookId";

        $stmt = $this->db->query($sql, [':bookId' => $bookId]);
        $bookData = $stmt->fetch(PDO::FETCH_ASSOC);

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
    public function deleteBook(int $id): void
    {
        $sql = "DELETE FROM book WHERE id = :id";
        $this->db->query($sql, ['id' => $id]);
    }
    public function countUserBooks(int $userId): int
    {
        // Préparation de la requête SQL pour compter les livres de l'utilisateur
        $sql = "SELECT COUNT(*) as total_books FROM book WHERE user_id = :userId";
        $stmt = $this->db->query($sql, ['userId' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total_books'];
    }
}
