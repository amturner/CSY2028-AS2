<?php
namespace CSY2028;
class DatabaseTable { 
    private $pdo;
    private $table;
    private $primaryKey;
    private $className;
    private $classArguments;

    public function __construct($pdo, $table, $primaryKey, $className = 'stdclass', $classArguments = []) {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
        $this->className = $className;
        $this->classArguments = $classArguments;
    }

    // Function to retrieve a single record from the specified database table.
    public function retrieveRecord($field, $value) {
        $stmt = $this->pdo->prepare('SELECT * FROM ' . $this->table . ' WHERE ' . $field . ' = :value;');

        $stmt->setFetchMode(\PDO::FETCH_CLASS, $this->className, $this->classArguments);

        $criteria = [
            'value' => $value
        ];
        $stmt->execute($criteria);
    
        return $stmt->fetchAll();
    }

    // Function to retrieve all records from the specified database table.
    public function retrieveAllRecords() {
        $stmt = $this->pdo->prepare('SELECT * FROM ' . $this->table . ';');

        $stmt->setFetchMode(\PDO::FETCH_CLASS, $this->className, $this->classArguments);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Function to insert a new record into the specified database table.
    public function insertRecord($record) {
        $keys = array_keys($record);

        $values = implode(', ', $keys);
        $placeholderValues = implode(', :', $keys);
    
        $stmt = $this->pdo->prepare('INSERT INTO ' . $this->table . ' (' . $values . ') VALUES (:' . $placeholderValues . ');');

        $stmt->execute($record);
    }

    // Function to update an existing record in the specified database table.
    public function updateRecord($record) {

    }

    // Function to delete a record from the specified database table.
    public function deleteRecord($id) {
        $stmt = $this->pdo->prepare('DELETE FROM ' . $this->table . ' WHERE ' . $this->primaryKey . ' = :id');

        $criteria = [
            'id' => $id
        ];
        $stmt->execute($criteria);
    }

    // Function to save a record to the specified database table.
    public function save($record) {
        try {
            $this->insertRecord($record); // Insert new record.
        }
        catch (PDOException $e) {
            $this->updateRecord($record); // Update existing record.
        }
    }
}
?>