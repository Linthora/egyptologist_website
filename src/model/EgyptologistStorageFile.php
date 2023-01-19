<?php

class EgyptologistStorageFile implements EgyptologistStorage {

   protected ObjectFileDB $bd;
   
    public function __construct(string $file) {
        $this->bdFile = new ObjectFileDB($file);
    }


    public function read($id) {
        if($this->bdFile->exists($id)) {
            return $this->bdFile->fetch($id);
        }
        return null;
    }

    public function readAll($method = null) {
        $list = $this->bdFile->fetchAll();
        if($method != null && $method == "name") {
            usort($list, function($a, $b) {
                return strcmp($a->getName(), $b->getName());
            });
        } else if($method != null && $method == "birthyear") {
            usort($list, function($a, $b) {
                return strcmp($a->getBirthyear(), $b->getBirthyear());
            });
        }
        return $list;
    }

    public function update($id, Egyptologist $egyptologist) {
        if($this->bdFile->exists($id)) {
            $this->bdFile->update($id, $egyptologist);
            return true;
        } else {
            return false;
        }
    }

    public function delete($id) {
        if($this->bdFile->exists($id)) {
            $this->bdFile->delete($id);
            return true;
        } else {
            return false;
        }
    }

    public function reinit() {
        $this->bdFile->deleteAll();
        $this->bdFile->insert(new Egyptologist("Jean-François Champollion", "the Rosetta Stone", 1790, 1832));
        $this->bdFile->insert(new Egyptologist("Auguste Mariette", "the Rosetta Stone", 1821, 1881));
        $this->bdFile->insert(new Egyptologist("James Henry Breasted", "the Rosetta Stone", 1865, 1935));
        $this->bdFile->insert(new Egyptologist("Flinders Petrie", "the Rosetta Stone", 1853, 1942));
        $this->bdFile->insert(new Egyptologist("Howard Carter", "the Rosetta Stone", 1874, 1939));
        $this->bdFile->insert(new Egyptologist("Yves Duhoux", "the Rosetta Stone", 1948, 2019));
    }

    public function save(Egyptologist $egyptologist) {
        return $this->bdFile->insert($egyptologist);
    }

    public function addImage($id) {
        $egyptologist = $this->read($id);
        $res = new Egyptologist($egyptologist->getName(), $egyptologist->getDiscovery(), $egyptologist->getBirthYear(), $egyptologist->getDeathYear(), 1);
        return $this->update($id, $res);
    }

    public function deleteImage($id) {
        $egyptologist = $this->read($id);
        $res = new Egyptologist($egyptologist->getName(), $egyptologist->getDiscovery(), $egyptologist->getBirthYear(), $egyptologist->getDeathYear(), 0);
        return $this->update($id, $res);
    }

}




?>