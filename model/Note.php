<?php

require_once "framework/Model.php";

class Note extends Model { //should be abstract
    
    public function __construct(public int $id, public string $title, public int $owner, public string $created_at, public ?string $edited_at, public string $pinned, public string $archived, public int $weight)
    {}

    // Méthodes GET
    public function getId(): int {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getOwner(): int {
        return $this->owner;
    }

    public function getCreatedAt(): string {
        return $this->created_at;
    }

    public function getEditedAt(): ?string {
        return $this->edited_at;
    }

    public function getPinned(): string {
        return $this->pinned;
    }

    public function getArchived(): string {
        return $this->archived;
    }

    public function getWeight(): int {
        return $this->weight;
    }

    // Méthodes SET
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function setOwner(int $owner): void {
        $this->owner = $owner;
    }

    public function setCreatedAt(string $created_at): void {
        $this->created_at = $created_at;
    }

    public function setEditedAt(?string $edited_at): void {
        $this->edited_at = $edited_at;
    }

    public function setPinned(string $pinned): void {
        $this->pinned = $pinned;
    }

    public function setArchived(string $archived): void {
        $this->archived = $archived;
    }

    public function setWeight(int $weight): void {
        $this->weight = $weight;
    }

    // Méthodes IS
    public function isPinned(): bool {
        return $this->pinned == 1;
    }

    public function isArchived(): bool {
        return $this->archived == 1;
    }

    public static function getAllNotesByUser(int $userId) : array {
        $data = self::execute("SELECT * FROM notes WHERE owner = :userId ORDER BY weight DESC", ["userId" => $userId])->fetchAll();
        $notes = [];
        foreach ($data as $row) {
            $notes[] = new Note(
                $row["id"],
                $row["title"],
                $row["owner"],
                $row["created_at"],
                $row["edited_at"],
                $row["pinned"],
                $row["archived"],
                $row["weight"]
            );
        }
        return $notes;
    }

    public static function getAllPinnedNotesByUser(int $userId) : array {
        $data = self::execute("SELECT * FROM notes WHERE owner = :userId and pinned = 1 and archived = 0 ORDER BY weight DESC", ["userId" => $userId])->fetchAll();
        $notes = [];
        foreach ($data as $row) {
            $notes[] = new Note(
                $row["id"],
                $row["title"],
                $row["owner"],
                $row["created_at"],
                $row["edited_at"],
                $row["pinned"],
                $row["archived"],
                $row["weight"]
            );
        }
        return $notes;
    }
    

    public static function getAllUnpinnedNotesByUser(int $userId) : array {
        $data = self::execute("SELECT * FROM notes WHERE owner = :userId and pinned = 0 and archived = 0 ORDER BY weight DESC", ["userId" => $userId])->fetchAll();
        $notes = [];
        foreach ($data as $row) {
            $notes[] = new Note(
                $row["id"],
                $row["title"],
                $row["owner"],
                $row["created_at"],
                $row["edited_at"],
                $row["pinned"],
                $row["archived"],
                $row["weight"]
            );
        }
        return $notes;
    }



    public static function getAllArchivedNotesByUser(int $userId) : array {
        $data = self::execute("SELECT * FROM notes WHERE owner = :userId AND archived = 1", ["userId" => $userId])->fetchAll();
        $notes = [];
    
        foreach ($data as $row) {
            $notes[] = new Note(
                $row["id"],
                $row["title"],
                $row["owner"],
                $row["created_at"],
                $row["edited_at"],
                $row["pinned"],
                $row["archived"],
                $row["weight"]
            );
        }
    
        return $notes;
    }
    
    public static function getAllSharedNotesEditorByUserId(int $userId) : array {
        $data = self::execute(
            "SELECT notes.id, notes.title, notes.owner, notes.created_at, notes.edited_at, notes.pinned, notes.archived, notes.weight 
            FROM note_shares JOIN notes on notes.id = note_shares.note 
            WHERE user = :userId AND editor = 1", 
            ["userId" => $userId])->fetchAll();
        $notes = [];
    
        foreach ($data as $row) {
            $notes[] = new Note(
                $row["id"],
                $row["title"],
                $row["owner"],
                $row["created_at"],
                $row["edited_at"],
                $row["pinned"],
                $row["archived"],
                $row["weight"]
            );
        }
    
        return $notes;
    }

    public static function getAllSharedBy(int $userId) : array {
        $data = self::execute(
            "SELECT DISTINCT owner FROM note_shares JOIN notes on notes.id = note_shares.note WHERE user = :userId", 
            ["userId" => $userId])->fetchAll();
        $sharedby = [];
    
        foreach ($data as $row) {
            $sharedby[] = $row["owner"];
        }
        return $sharedby;
    }

    public static function getNoteById(int $noteId) : Note|false {
        $query = self::execute("SELECT * FROM notes WHERE id = :noteId", ["noteId" => $noteId]);
        $data = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new Note(
                $data["id"],
                $data["title"],
                $data["owner"],
                $data["created_at"],
                $data["edited_at"],
                $data["pinned"],
                $data["archived"],
                $data["weight"]
            );
        }
    }

    public static function getTitleById(int $noteId) : string {
        $query = self::execute("SELECT title FROM notes WHERE id = :noteId", ["noteId" => $noteId]);
        $data = $query->fetch();
    
        if ($query->rowCount() === 0 || !$data) {
            // Aucune note trouvée avec l'ID spécifié
            return null;
        }
    
        return $data["title"];
    }
    
    public static function getWeightbyId(int $noteId) : int {
        $query = self::execute("SELECT weight FROM notes WHERE id = :noteId", ["noteId" => $noteId]);
        $data = $query->fetch();
    
        if ($query->rowCount() === 0 || !$data) {
            // Aucune note trouvée avec l'ID spécifié
            return false;
        }
    
        return $data["weight"];
    }

    public static function increaseWeight(int $noteId): void {
        self::execute("UPDATE notes SET weight = weight + 1 WHERE id = :noteId", ["noteId" => $noteId]);
    }

    public static function decreaseWeight(int $noteId): void {
        self::execute("UPDATE notes SET weight = weight - 1 WHERE id = :noteId", ["noteId" => $noteId]);
    }
    
    //public abstract function persist() : object|array;

    public static function validateTitle(string $title) : bool {
        return (strlen($title) >= 3 && strlen($title) <= 25);
    }

    //public abstract static function delete(int $id) : void;

    public static function increaseAllWeightBy1(int $id) : void { //Augmente le poids de toutes les nutes d'un user afin d'inserer une nouvelle note au poids de 1
        $notes = Note::getAllNotesByUser($id);
        foreach ($notes as $note){
            $note->setWeight($note->getWeight() + 1);
            $note = new Note($note->getId(),
                                    $note->getTitle(),
                                    $note->getOwner(),
                                    $note->getCreatedAt(),
                                    $note->getEditedAt(),
                                    $note->getPinned(),
                                    $note->getArchived(),
                                    $note->getWeight());
            $note->persist();
        }
    }

    public function persist() : Note|array {
        if ($this->id == NULL){
            $errors = $this->validate();
            if (empty($errors)){
                
                self::execute('INSERT INTO Notes(title, owner, edited_at, pinned, archived, weight) VALUES (:title, :owner, null, 0, 0, 1)', ['title' => $this->title, 'owner' => $this->owner]);
                $note = self::getNoteById(self::lastInsertId());
                $this->id = $note->id;
                $this->created_at = $note->created_at;
                //self::execute('INSERT INTO Text_Notes(content, id) VALUES (:content, :id)', ['content' => $this->content, 'id' => $this->id]);
                return $this;
            } else {
                return $errors;
            }
        } else {
            //throw new Exception("Pas rdy encore");//Modification
            // Mise à jour d'une note existante
            $errors = $this->validate();
            if (empty($errors)){
            // Mise à jour dans la table 'Notes'
                self::execute('UPDATE Notes SET weight = :weight WHERE id = :id', ['weight' => $this->weight, 'id' => $this->id]);
            
            // Mise à jour dans la table 'Text_Notes'
                //self::execute('UPDATE Text_Notes SET content = :content WHERE id = :id', ['content' => $this->content, 'id' => $this->id]);
            
                return $this;
            } else {
                return $errors;
            }
        }
    }

    public function validate() : array {
        $errors = [];
        if (!(strlen($this->title) >= 3 && strlen($this->title) <= 25)) {
            $errors[] = "Title length must be between 3 and 25.";
        }
        return $errors;
    }


    public static function delete(int $id): void {
        // Supprimer les enregistrements dans la table note_shares liés à la note
        self::execute("DELETE FROM note_shares WHERE note = :id", ["id" => $id]);
        
        // Supprimer les enregistrements dans la table note_shares liés à la note
        self::execute("DELETE FROM note_shares WHERE note = :id", ["id" => $id]);
    
        // Supprimer la note de la table text_notes
        self::execute("DELETE FROM text_notes WHERE id = :id", ["id" => $id]);
    
        // Supprimer la note de la table notes
        self::execute("DELETE FROM notes WHERE id = :id", ["id" => $id]);
    }


}
