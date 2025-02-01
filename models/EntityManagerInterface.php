<?php
/**
 * Interface qui définit les méthodes CRUD de base pour la gestion des entités.
 * Toutes les classes qui implémentent cette interface doivent fournir
 * les fonctionnalités pour récupérer, créer, mettre à jour et supprimer des entités.
 */

interface EntityManagerInterface
{
    public function getById(int $id): ?AbstractEntity;
    public function create(AbstractEntity $entity): bool;
    public function update(AbstractEntity $entity): bool;
    public function delete(int $id): bool;
}
