<?php

class EgyptologistStorageStub implements EgyptologistStorage {
    
    protected $egyptologists;
    protected $id;

    public function __construct() {
        $this->egyptologists = array(
            "Champollion" => new Egyptologist("Jean-François Champollion", "the Rosetta Stone", 1790, 1832),
            "Lepsius" => new Egyptologist("Wilhelm Lepsius", "the Turin papyrus", 1810, 1884),
            "Petrie" => new Egyptologist("Flinders Petrie", "the tomb of Tutankhamun", 1853, 1942),
            "Maspero" => new Egyptologist("Gaston Maspero", "the tomb of Seti I", 1842, 1916),
            "Borchardt" => new Egyptologist("Ludwig Borchardt", "the tomb of Seti I", 1854, 1929),
            "Meyer" => new Egyptologist("Eduard Meyer", "the tomb of Seti I", 1855, 1930),
            "Mariette" => new Egyptologist("Auguste Mariette", "the tomb of Seti I", 1822, 1881),
        );
        $this->id = count($this->egyptologists);
    }

    public function read($id) {
        //return $this->egyptologists[$id];
        if(!key_exists($id, $this->egyptologists)) {
            return null;
        }
        return $this->egyptologists[$id];
        
    }

    public function readAll($method = null) {
        $list = $this->egyptologists;
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
        //return $this->egyptologists;
    }

    public function update($id, Egyptologist $egyptologist) {
        if(key_exists($id, $this->egyptologists)) {
            $this->egyptologists[$id] = $egyptologist;
            return true;
        } else {
            return false;
        }
    }

    public function delete($id) {
        if(key_exists($id, $this->egyptologists)) {
            unset($this->egyptologists[$id]);
            return true;
        } else {
            return false;
        }
    }

    public function reinit() {
        $this->egyptologists = array(
            new Egyptologist("Jean-François Champollion", "the Rosetta Stone", 1790, 1832),
            new Egyptologist("Wilhelm Lepsius", "the Turin papyrus", 1810, 1884),
            new Egyptologist("Flinders Petrie", "the tomb of Tutankhamun", 1853, 1942),
            new Egyptologist("Gaston Maspero", "the tomb of Seti I", 1842, 1916),
            new Egyptologist("Ludwig Borchardt", "the tomb of Seti I", 1854, 1929),
            new Egyptologist("Eduard Meyer", "the tomb of Seti I", 1855, 1930),
            new Egyptologist("Auguste Mariette", "the tomb of Seti I", 1822, 1881),
        );
    }

    public function save(Egyptologist $egyptologist) {
        $this->egyptologists[$this->id] = $egyptologist;
        //$this->id++;
        return $this->id++;
    }

    public function addImage($id) {
        if(key_exists($id, $this->egyptologists)) {
            $egyptologist = $this->egyptologists[$id];
            $res = new Egyptologist($egyptologist->getName(), $egyptologist->getDiscovery(), $egyptologist->getBirthyear(), $egyptologist->getDeathyear(), 1);
            $this->egyptologists[$id] = $res;
            return true;
        } else {
            return false;
        }
    }

    public function deleteImage($id) {
        if(key_exists($id, $this->egyptologists)) {
            $egyptologist = $this->egyptologists[$id];
            $res = new Egyptologist($egyptologist->getName(), $egyptologist->getDiscovery(), $egyptologist->getBirthyear(), $egyptologist->getDeathyear(), 0);
            $this->egyptologists[$id] = $res;
            return true;
        } else {
            return false;
        }
    }

}

?>