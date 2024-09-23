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
    /**
     * Recherche des livres par titre ou auteur.
     * @param string $query : terme de recherche.
     * @return array : un tableau d'objets Book.
     */
    public function searchBooks(string $query): array
    {
        $sql = "SELECT * FROM book WHERE title LIKE :query OR author LIKE :query";
        $result = $this->db->query($sql, ['query' => '%' . $query . '%']);
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
            $this->updateBook($book);
        }
    }

    /**
     * Ajoute un book.
     * @param Book $book : book à ajouter.
     * @return void
     */
    public function addBook(Book $book): void
    {
        $sql = "INSERT INTO book (id_user, title, description, date_creation) VALUES (:id_user, :title, :description, NOW())";
        $this->db->query($sql, [
            'id_user' => $book->getUserId(),
            'title' => $book->getTitle(),
            'description' => $book->getDescription(),
        ]);
    }

    /**
     * Modifie un book.
     * @param Book $book : le book à modifier.
     * @return void
     */
    public function updateBook(Book $book): void
    {
        $sql = "UPDATE book SET title = :title, description = :description, date_update = NOW() WHERE id = :id";
        $this->db->query($sql, [
            'title' => $book->getTitle(),
            'description' => $book->getDescription(),
            'id' => $book->getId(),
        ]);
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
        $sql = "SELECT COUNT(*) as book_count FROM books WHERE user_id = :user_id";

        // Préparation de la requête
        $stmt = $this->db->query($sql);

        // Exécution de la requête avec l'ID de l'utilisateur
        $stmt->execute(['user_id' => $userId]);

        // Récupération du résultat
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Retourne le nombre de livres (ou 0 s'il n'y a pas de livres)
        return $result['book_count'] ?? 0;
    }
}
