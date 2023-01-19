<?php

/**
 * EgyptologistStorage implmentation using a MySQL database
 */
class EgyptologistStorageMySQL implements EgyptologistStorage {

    /**
     * The PDO object used to connect to the database
     */
    protected PDO $pdo;

    /** All the prepared queries that are to be used in this class */
    protected const queryInsert = "INSERT INTO egyptologists (name, discovery, birth_year, death_year) VALUES (:name, :discovery, :birth_year, :death_year)";
    protected const queryUpdate = "UPDATE egyptologists SET name = :name, discovery = :discovery, birth_year = :birth_year, death_year = :death_year WHERE id = :id";
    protected const queryDelete = "DELETE FROM egyptologists WHERE id = :id";
    protected const querySelect = "SELECT * FROM egyptologists WHERE id = :id";
    protected const querySelectAll = "SELECT * FROM egyptologists";
    protected const querySelectAllSortedByName = "SELECT * FROM egyptologists ORDER BY name";
    protected const querySelectAllSortedByBirthYear = "SELECT * FROM egyptologists ORDER BY birth_year";
    protected const queryAddImage = "UPDATE egyptologists SET image = 1 WHERE id = :id";
    protected const queryDeleteImage = "UPDATE egyptologists SET image = 0 WHERE id = :id";

    /** All the reference that we need to manipulate our queries */
    protected const idQ = ":id"; 
    protected const nameQ = ":name";
    protected const discoveryQ = ":discovery";
    protected const birthYearQ = ":birth_year";
    protected const deathYearQ = ":death_year";
    protected const imageQ = ":image";

    /**
     * Constructor of the EgyptologistStorageMySQL class
     * @param PDO $pdo the PDO object used to connect to the database
     */
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;        
        // à voir si on garde cette ligne suivante: better safe than sorry
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /** We have already stated documentation for the following function in the EgyptologistStorage interface */

    public function read($id) {
        $stmt = $this->pdo->prepare($this::querySelect);
        $stmt->execute(array($this::idQ => $id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            return (new EgyptologistBuilder($row))->createEgyptologist();
        } else {
            return null;
        }
    }

    public function readAll($method=null) {
        if($method != null && $method == "name") {
            $stmt = $this->pdo->prepare($this::querySelectAllSortedByName);
        } else if($method != null && $method == "birthyear") {
            $stmt = $this->pdo->prepare($this::querySelectAllSortedByBirthYear);
        } else {
            $stmt = $this->pdo->prepare($this::querySelectAll);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $egyptologists = array();
        
        foreach($rows as $row) {
            $egyptologists[$row["id"]] = (new EgyptologistBuilder($row))->createEgyptologist();
        }

        return $egyptologists;
    }

    public function save(Egyptologist $egyptologist) {
        $stmt = $this->pdo->prepare($this::queryInsert);
        $stmt->execute(array(
            $this::nameQ => $egyptologist->getName(),
            $this::discoveryQ => $egyptologist->getDiscovery(),
            $this::birthYearQ => $egyptologist->getBirthYear(),
            $this::deathYearQ => $egyptologist->getDeathYear(),
        ));
        return $this->pdo->lastInsertId();
    }

    public function delete($id) {
        if($this->read($id) != null) {
            $stmt = $this->pdo->prepare($this::queryDelete);
            $stmt->execute(array($this::idQ => $id));
            return true;
        } else {
            return false;
        }
    }

    public function update($id, Egyptologist $egyptologist) {
        if($this->read($id) != null) {
            $stmt = $this->pdo->prepare($this::queryUpdate);
            $stmt->execute(array(
                $this::idQ => $id,
                $this::nameQ => $egyptologist->getName(),
                $this::discoveryQ => $egyptologist->getDiscovery(),
                $this::birthYearQ => $egyptologist->getBirthYear(),
                $this::deathYearQ => $egyptologist->getDeathYear()
            ));
            return true;
        } else {
            return false;
        }
    }

    public function addImage($id) {
        //see later if we need to check if the image exists (perhaps done already in the controller)
        if($this->read($id) != null) {
            $stmt = $this->pdo->prepare($this::queryAddImage);
            $stmt->execute(array(
                $this::idQ => $id,
                //$this::imageQ => $image
            ));
            return true;
        } else {
            return false;
        }
    }

    public function deleteImage($id) {
        if($this->read($id) != null) {
            $stmt = $this->pdo->prepare($this::queryDeleteImage);
            $stmt->execute(array(
                $this::idQ => $id,
            ));
            return true;
        } else {
            return false;
        }
    }

    public function reinit() {

        $this->pdo->exec("DELETE FROM egyptologists");
        
        // doing some cleaning to prevent problems related to the images
        $files = glob('upload/*'); 
        foreach($files as $file){ 
            if(is_file($file))
            unlink($file);
        }

        $this->save(new Egyptologist("Howard Carter","the Tomb of Tutankhamun",1874,1939));
        $id = $this->save(new Egyptologist("Jean-François Champollion","a working method to translate hieroglyphs.",1790,1832));
        $this->addImage($id);
        
        copy("image/champo.jpg", "upload/$id.jpg");

        $this->save(new Egyptologist("Auguste Mariette","the Serapeum of Saqqara.",1808,1894));
        $this->save(new Egyptologist("Hussein Bassir", "the valley of Golden Mummies", 1973, 3001));
        $this->save(new Egyptologist("Sarah Parcak", "17 new pyramids using satellite imaging.", 1979, 3001));
        $this->save(new Egyptologist("Charles Edwin Wilbour", "the Elephantine Papyri", 1833,1896));
        $this->save(new Egyptologist("Günter Dreyer", "the burial site of the kin (U-j), the earliest known large royal tomb of old Egypt.", 1943, 2019));
    }

}