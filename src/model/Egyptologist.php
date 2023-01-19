<?php

/**
 * Egyptologist class representing an egyptologist object
 */
class Egyptologist {

    /**
     * The name of the egyptologist
     */
    protected $name;

    /**
     * The major discovery of the egyptologist
     */
    protected $discovery;

    /**
     * The birth year of the egyptologist
     */
    protected $birthyear;

    /**
     * The death year of the egyptologist
     */
    protected $deathyear;

    /**
     * An int (more like a boolean but simplier to say int for MySQL database) indicating if the egyptologist possesses an image
     */
    protected $image;

    /**
     * Constructor of the Egyptologist class
     * @param string $name the name of the egyptologist
     * @param string $discovery the major discovery of the egyptologist
     * @param int $birthyear the birth year of the egyptologist
     * @param int $deathyear the death year of the egyptologist
     * @param int $image a boolean indicating if the egyptologist possesses an image
     */
    public function __construct(string $name, string $discovery, int $birthyear, int $deathyear, int $image=0) {
        $this->name = $name;
        $this->discovery = $discovery;
        $this->birthyear = $birthyear;
        $this->deathyear = $deathyear;
        $this->image = $image;
    }

    /**
     * Getter for the name of the egyptologist
     * @return string the name of the egyptologist
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Getter for the major discovery of the egyptologist
     * @return string the major discovery of the egyptologist
     */
    public function getDiscovery() {
        return $this->discovery;
    }

    /**
     * Getter for the birth year of the egyptologist
     * @return int the birth year of the egyptologist
     */
    public function getBirthyear() {
        return $this->birthyear;
    }

    /**
     * Getter for the death year of the egyptologist
     * @return int the death year of the egyptologist
     */
    public function getDeathyear() {
        return $this->deathyear;
    }

    /**
     * Getter for the image of the egyptologist
     * @return int a boolean indicating if the egyptologist possesses an image
     */
    public function getImage() {
        return $this->image;
    }

}