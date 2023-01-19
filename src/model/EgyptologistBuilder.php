<?php

/**
 * Egyptologist builder class used to build egyptologists and assert their validity before building them.
 */
class EgyptologistBuilder {

    /**
     * Associative array containing the data to build the egyptologist.
     */
    protected array $data;

    /**
     * Array containing the errors that were seen during the validity check over our duilding data.
     */
    protected array $error;

    /**
     * Reference to the key used to store the name of the egyptologist.
     */
    protected const NAME_REF = "name";

    /**
     * Reference to the key used to store the birth year of the egyptologist.
     */
    protected const BIRTH_YEAR_REF = "birth_year";

    /**
     * Reference to the key used to store the death year of the egyptologist.
     */
    protected const DEATH_YEAR_REF = "death_year";

    /**
     * Reference to the key used to store the discovery of the egyptologist.
     */
    protected const DISCOVERY_REF = "discovery";

    /**
     * Reference to the key used to store the int boolean saying if the egyptologist has an image.
     */
    protected const IMAGE_REF = "image";

    /**
     * Constructor of the EgyptologistBuilder class.
     * @param array $data associative array containing the data to build the egyptologist.
     */
    public function __construct(array $data) {
        $this->data = $data;
        $this->data[self::IMAGE_REF] = $this->data[self::IMAGE_REF] ?? 0;
        $this->error = array(
                $this::NAME_REF => "",
                $this::BIRTH_YEAR_REF => "",
                $this::DEATH_YEAR_REF => "",
                $this::DISCOVERY_REF => "",
                $this::IMAGE_REF => ""
            );
    }

    /**
     * Creates an egyptologist from the data stored in the builder after a validity check.
     * @return Egyptologist|null the egyptologist created from the data stored in the builder or null if the validity check failed.
     */
    public function createEgyptologist() {
        if($this->isValid()) {
            $this->data[$this::NAME_REF] = htmlspecialchars($this->data["name"], ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8');
            $this->data[$this::DISCOVERY_REF] = htmlspecialchars($this->data["discovery"], ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8');
            $this->data[$this::BIRTH_YEAR_REF] = htmlspecialchars($this->data[$this::BIRTH_YEAR_REF], ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8');
            $this->data[$this::DEATH_YEAR_REF] = htmlspecialchars($this->data[$this::DEATH_YEAR_REF], ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8');
            $this->data[$this::IMAGE_REF] = htmlspecialchars($this->data[$this::IMAGE_REF], ENT_QUOTES|ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8');
            return new Egyptologist($this->data[$this::NAME_REF], $this->data[$this::DISCOVERY_REF], $this->data[$this::BIRTH_YEAR_REF], $this->data[$this::DEATH_YEAR_REF], $this->data[$this::IMAGE_REF]);
        } else {
            return null;
        }
    }

    /**
     * Checks the validity of the data stored in the builder.
     * @return bool true if the data is valid, false otherwise.
     */
    public function isValid() {
        
        if(key_exists($this::DEATH_YEAR_REF, $this->data) && $this->data[$this::DEATH_YEAR_REF] == '----') {
            $this->data[$this::DEATH_YEAR_REF] = 3001;
        }
        $tmperr = array(
            $this::NAME_REF => '<p color="red">',
            $this::BIRTH_YEAR_REF => '<p color="red">',
            $this::DEATH_YEAR_REF => '<p color="red">',
            $this::DISCOVERY_REF => '<p color="red">',
        );
        $passed = true;
        if(!key_exists($this::NAME_REF, $this->data) || $this->data[$this::NAME_REF] == "") {
            $this->error[$this::NAME_REF] .= "Missing name. ";
            $passed = false;
        }
        if(!key_exists($this::DISCOVERY_REF, $this->data) || $this->data[$this::DISCOVERY_REF] == "") {
            $tmperr[$this::DISCOVERY_REF] .= "Missing discovery. ";
            $passed = false;
        }
        if(!key_exists($this::BIRTH_YEAR_REF, $this->data) || $this->data[$this::BIRTH_YEAR_REF] == "") {
            $tmperr[$this::BIRTH_YEAR_REF] .= "Missing birth year. ";
            $passed = false;
        }
        if(!key_exists($this::DEATH_YEAR_REF, $this->data) || $this->data[$this::DEATH_YEAR_REF] == "") {
            $tmperr[$this::DEATH_YEAR_REF] .= "Missing death year. ";
            $passed = false;
        }
        if(key_exists($this::BIRTH_YEAR_REF, $this->data) && !is_numeric($this->data[$this::BIRTH_YEAR_REF])) {
            $tmperr[$this::BIRTH_YEAR_REF] .= "Birth year must be a number. ";
            $passed = false;
        }
        if(key_exists($this::DEATH_YEAR_REF, $this->data) && !is_numeric($this->data[$this::DEATH_YEAR_REF])) {
            $tmperr[$this::DEATH_YEAR_REF] .= "Death year must be a number. ";
            $passed = false;
        }
        if(key_exists($this::BIRTH_YEAR_REF, $this->data) && is_numeric($this->data[$this::BIRTH_YEAR_REF]) 
                && (intval($this->data[$this::BIRTH_YEAR_REF]) < -30 || intval($this->data[$this::BIRTH_YEAR_REF]) > 2021) ) {
            $tmperr[$this::BIRTH_YEAR_REF] .= "Birth year must be between -30 and 2021. ";
            $passed = false;
        }
        if(key_exists($this::DEATH_YEAR_REF, $this->data) && is_numeric($this->data[$this::DEATH_YEAR_REF])
                && (intval($this->data[$this::DEATH_YEAR_REF]) < -30 || intval($this->data[$this::DEATH_YEAR_REF]) > 3001) ) {
            $tmperr[$this::DEATH_YEAR_REF] .= "Death year must be between -30 and 3000. ";
            $passed = false;
        }
        if(key_exists($this::BIRTH_YEAR_REF, $this->data) && key_exists($this::DEATH_YEAR_REF, $this->data) 
                && is_numeric($this->data[$this::BIRTH_YEAR_REF]) && is_numeric($this->data[$this::DEATH_YEAR_REF]) 
                && (intval($this->data[$this::BIRTH_YEAR_REF]) > intval($this->data[$this::DEATH_YEAR_REF]))) {
            $tmperr[$this::BIRTH_YEAR_REF] .= "Birth year must be lesser than death year. ";
            $tmperr[$this::DEATH_YEAR_REF] .= "Death year must be greater than birth year. ";
            $passed = false;
        }

        if($this->data[$this::IMAGE_REF] != 1 && $this->data[$this::IMAGE_REF] != 0) {
            $this->error[$this::NAME_REF] .= "Don't you dare try to mess with the website."; // in NAME_REF cause it's highly improbable to happen and I'm too lazy to handle this case in the view
            $this->error[$this::IMAGE_REF] .= "Don't you dare try to mess with the website.";
            $passed = false;
        }

        if(!$passed) {
            foreach($tmperr as $key => $value) {
                if($value != '<p color="red">') {
                    $this->error[$key] .= $value . "</p>";
                }
            }
        }

        return $passed;
    }

    /**
     * Returns an associative array containg the error messages linked with the corresponding field.
     * @param string $field the field to get the error message for.
     * @return string the error message associated with the given field.
     */
    public function getError() {
        return $this->error;
    }

    /**
     * Tells us if there is any error stored in the builder.
     * @return bool true if there is at least one error, false otherwise.
     */
    public function noError() {
        $this->isValid();
        foreach($this->error as $key => $value) {
            if($value != "") {
                return false;
            }
        }
        return true;
    }

    /**
     * Returns the data stored in the builder.
     * @return array the data stored in the builder.
     */
    public function getData() {
        return $this->data;
    }

    /**
     * Returns the name stored in the builder.
     * @return string the name stored in the builder.
     */
    public function getName() {
        return $this->data[$this::NAME_REF];
    }

    /**
     * Returns the birth year stored in the builder.
     * @return int the birth year stored in the builder.
     */
    public function getBirthYear() {
        return $this->data[$this::BIRTH_YEAR_REF];
    }

    /**
     * Returns the death year stored in the builder.
     * @return int the death year stored in the builder.
     */
    public function getDeathYear() {
        return $this->data[$this::DEATH_YEAR_REF];
    }

    /**
     * Returns the discovery stored in the builder.
     * @return string the discovery stored in the builder.
     */
    public function getDiscovery() {
        return $this->data[$this::DISCOVERY_REF];
    }

    /**
     * Returns the int(boolean) value saying if the egyptologist has an image or not stored in the builder.
     * @return int the int(boolean) value saying if the egyptologist has an image or not stored in the builder.
     */
    public function getImage() {
        return $this->data[$this::IMAGE_REF];
    }

    /**
     * Returns the reference to the name field to use.
     */
    public static function getNameRef() {
        return self::NAME_REF;
    }

    /**
     * Returns the reference to the birth year field to use.
     */
    public static function getBirthYearRef() {
        return self::BIRTH_YEAR_REF;
    }

    /**
     * Returns the reference to the death year field to use.
     */
    public static function getDeathYearRef() {
        return self::DEATH_YEAR_REF;
    }

    /**
     * Returns the reference to the discovery field to use.
     */
    public static function getDiscoveryRef() {
        return self::DISCOVERY_REF;
    }

    /**
     * Returns the reference to the image field to use.
     */
    public static function getImageRef() {
        return self::IMAGE_REF;
    }
    
}


?>