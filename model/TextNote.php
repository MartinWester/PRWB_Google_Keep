<?php

require_once "framework/Model.php";

class TextNote extends Note {
    
    public function __construct(public int $id, public string $title, public int $owner, public string $created_at, public ?string $edited_at, public string $pinned, public string $archived, public int $weight, public string $content)
    {}

    public function persist() : TextNote|array {
        if ($this->id == NULL){
            $errors = $this->validate();
            if (empty($errors)){
                
                self::execute('INSERT INTO Notes(title, owner, edited_at, pinned, archived, weight) VALUES (:title, :owner, null, 0, 0, 1)', ['title' => $this->title, 'owner' => $this->owner]);
                $note = self::getNoteById(self::lastInsertId());
                $this->id = $note->id;
                $this->created_at = $note->created_at;
                self::execute('INSERT INTO Text_Notes(content, id) VALUES (:content, :id)', ['content' => $this->content, 'id' => $this->id]);
                return $this;
            } else {
                return $errors;
            }
        } else {
            throw new Exception("Pas rdy encore");//Modification
        }
    }

    public static function delete(int $id): void {
        // Supprimer les enregistrements dans la table note_shares liés à la note
        self::execute("DELETE FROM note_shares WHERE note = :id", ["id" => $id]);
    
        // Supprimer la note de la table text_notes
        self::execute("DELETE FROM text_notes WHERE id = :id", ["id" => $id]);
    
        // Supprimer la note de la table notes
        self::execute("DELETE FROM notes WHERE id = :id", ["id" => $id]);
    }
    
    

    public function validate() : array {
        $errors = [];
        if (!(strlen($this->title) >= 3 && strlen($this->title) <= 25)) {
            $errors[] = "Title length must be between 3 and 25.";
        }
        return $errors;
    }
}