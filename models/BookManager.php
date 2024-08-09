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
    public function getAllbooks(): array
    {
        $sql = "SELECT * FROM book";
        $result = $this->db->query($sql);
        $books = [];

        while ($book = $result->fetch()) {
            $books[] = new Book($book);
        }
        return $books;
    }
    public function getBooks(array $criteria=[], array $orders = [], int $limit = 0): array
    {
        $sql = "SELECT * FROM book";
        foreach ($criteria as $key => $value) {
            $sql .= " WHERE $key = :$value";
            if (count($criteria) > 1) {
                $sql .= " AND ";
            }
        }
        foreach ($orders as $key => $value) {
            $sql .= " ORDER BY $key $value";
        }
        if ($limit > 0) {
            $sql .= " LIMIT $limit";
        }
        $result = $this->db->query($sql, $criteria);
        $books = [];
        while ($book = $result->fetch()) {
            $books[] = new Book($book);
        }
        return $books;
    }
    /**
     * Récupère un book par son id.
     * @param int $id : l'id de l'book.
     * @return book|null : un objet book ou null si l'book n'existe pas.
     */
    public function getbookById(int $id, $shouldIncrementViews = false): ?book
    {
        $sql = "SELECT * FROM book WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        $book = $result->fetch();
        if ($book) {
            if ($shouldIncrementViews) {
                $this->incrementViews($id);
            }
            return new book($book);
        }
        return null;
    }

    public function incrementViews(int $idbook): void
    {
        $sql = "UPDATE book SET nbre_vues = nbre_vues + 1 WHERE id = :id";
        $this->db->query($sql, ['id' => $idbook]);
    }
    // Méthode pour mettre à jour le nombre de commentaires pour tous les books
    public function updateCommentCountForbooks()
    {
        // Récupérer tous les books
        $books = $this->getAllbooks();

        // Pour chaque book, récupérer le nombre de commentaires et mettre à jour nbre_comment
        foreach ($books as $book) {
            $idbook = $book->getId(); // getId() retourne l'id de l'book

            $this->updatebook($book); // Méthode à créer pour sauvegarder les modifications dans la base de données
        }
    }
    /**
     * Ajoute ou modifie un book.
     * On sait si l'book est un nouvel book car son id sera -1.
     * @param book $book : l'book à ajouter ou modifier.
     * @return void
     */
    public function addOrUpdatebook(book $book): void
    {
        if ($book->getId() == -1) {
            $this->addbook($book);
        } else {
            $this->updatebook($book);
        }
    }

    /**
     * Ajoute un book.
     * @param book $book : book à ajouter.
     * @return void
     */
    public function addbook(book $book): void
    {
        $sql = "INSERT INTO book (id_user, title, description, date_creation) VALUES (:id_user, :title, :description, NOW())";
        $this->db->query($sql, [
            'id_user' => $book->getUserId(),
            'title' => $book->getTitle(),
            'description' => $book->getDescription()
        ]);
    }

    /**
     * Modifie un book.
     * @param book $book : l'book à modifier.
     * @return void
     */
    public function updatebook(book $book): void
    {
        $sql = "UPDATE book SET title = :title, description = :description, date_update = NOW() WHERE id = :id";
        $this->db->query($sql, [
            'title' => $book->getTitle(),
            // 'description' => $book->getdet(),
            'id' => $book->getId()
        ]);
    }

    /**
     * Supprime un book.
     * @param int $id : l'id de l'book à supprimer.
     * @return void
     */
    public function deletebook(int $id): void
    {
        $sql = "DELETE FROM book WHERE id = :id";
        $this->db->query($sql, ['id' => $id]);
    }
}
