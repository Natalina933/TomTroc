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

        while ($book = $result->fetch()) {
            $books[] = new Book($book);
        }
        return $books;
    }
    // SELECT b.*,u.username as name FROM book b JOIN user u ON b.user_id=u.id WHERE b.id = 5
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
        }

        $result = $this->db->query($sql, $params);
        $books = [];
        while ($book = $result->fetch()) {
            $books[] = new Book($book);
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
        while ($book = $result->fetch()) {
            $books[] = new Book($book);
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
        if ($book->getId() == -1) {
            $this->addBook($book);
        } else {
            $this->editBook($book);
        }
    }

    public function addBook(Book $book): void
    {
        $sql = "INSERT INTO book (user_id, title, author, description, img, available) 
            VALUES (:userId, :title, :author, :description, :img, :available)";
        $this->db->query($sql, [
            'userId' => $book->getUserId(),
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'description' => $book->getDescription(),
            'img' => $book->getImg(),
            'available' => $book->isAvailable()
        ]);
    }

    public function editBook(Book $book): void
    {
        $sql = "UPDATE book SET title = :title, author = :author, description = :description, 
            img = :img, available = :available, user_id = :userId, 
            WHERE id = :id";
        $this->db->query($sql, [
            'id' => $book->getId(),
            'userId' => $book->getUserId(),
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'description' => $book->getDescription(),
            'img' => $book->getImg(),
            'available' => $book->isAvailable()
        ]);
    }

    public function getBookWithSellerInfo(int $bookId): ?array
    {
        $sql = "
            SELECT 
                book.id AS book_id, 
                book.title AS book_title, 
                book.author AS book_author, 
                book.img AS book_img, 
                book.available AS book_availability, 
                user.username AS seller_username, 
                user.profilePicture AS seller_profilePicture
            FROM 
                book 
            INNER JOIN 
                user 
            ON 
                book.user_id = user.id
            WHERE 
                book.id = :bookId
        ";

        $stmt = $this->db->query($sql, [
            ':bookId' => $bookId,
        ]);

        $bookData = [];  // Création d'un tableau pour stocker les données du livre et du vendeur

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $bookData[] = [
                'id' => $row['book_id'],
                'title' => $row['book_title'],
                'author' => $row['book_author'],
                'img' => $row['book_img'],
                'is_available' => $row['book_availability'],
                'seller' => [
                    'username' => $row['seller_username'],
                    'profilePicture' => $row['seller_profilePicture'],
                ],
            ];
        }

        // Retourne soit un tableau avec les informations, soit null si aucun résultat n'a été trouvé
        return !empty($bookData) ? $bookData[0] : null;
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
        $sql = "SELECT COUNT(*) as total_books FROM book";
        $params = [];

        // Ajout de la condition pour l'ID de l'utilisateur
        $criteria = ['user_id' => $userId];

        if (!empty($criteria)) {
            $conditions = [];
            foreach ($criteria as $key => $value) {
                $conditions[] = "$key = :$key";
                $params[$key] = $value;
            }
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        // Exécution de la requête
        $stmt = $this->db->query($sql, $params);
        $result = $stmt->fetch();

        // Retourner le nombre total de livres
        return $result['total_books'];
    }
}
