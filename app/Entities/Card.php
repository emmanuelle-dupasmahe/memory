<?php
// Fichier : app/Entities/Card.php

namespace App\Entities;

class Card {
    private int $boardId;          // ID unique sur le plateau de jeu (position 0 à 11)
    private int $typeId;           // ID du personnage (référence à la table 'cartes' BDD)
    private string $personnageName; // Nom du personnage (ex: 'snoopy')
    private string $imagePath;      // Chemin de l'image recto
    private bool $isFlipped = false;
    private bool $isMatched = false;

    public function __construct(int $boardId, int $typeId, string $name, string $imagePath) {
        $this->boardId = $boardId;
        $this->typeId = $typeId;
        $this->personnageName = $name;
        $this->imagePath = $imagePath;
    }

    // --- Mutateurs (Actions) ---
    public function flip(): void {
        if (!$this->isMatched) {
            $this->isFlipped = true;
        }
    }

    public function unflip(): void {
        if (!$this->isMatched) {
            $this->isFlipped = false;
        }
    }

    public function match(): void {
        $this->isMatched = true;
        $this->isFlipped = true; // Reste visible une fois trouvée
    }

    // --- Accesseurs (Getters) ---
    public function getBoardId(): int { return $this->boardId; }
    public function getTypeId(): int { return $this->typeId; } // L'ID qui doit correspondre pour la paire
    public function getPersonnageName(): string { return $this->personnageName; }
    public function getImagePath(): string { return $this->imagePath; }
    public function isFlipped(): bool { return $this->isFlipped; }
    public function isMatched(): bool { return $this->isMatched; }

    // Pour l'affichage dans la Vue, nous allons créer un tableau simple
    public function toArray(): array {
        return [
            'boardId' => $this->boardId,
            'typeId' => $this->typeId,
            'name' => $this->personnageName,
            'imagePath' => $this->imagePath,
            'isFlipped' => $this->isFlipped,
            'isMatched' => $this->isMatched,
        ];
    }
}