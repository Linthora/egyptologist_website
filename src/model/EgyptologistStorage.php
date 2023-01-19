<?php

/**
 * EgyptologistStorage interface used to define the methods used to manipulate a database of egyptologists
 */
interface EgyptologistStorage {
    
    /**
     * Returns the egytologist with the given id or null if no egytologist is found
     * @param $id the id of the egytologist
     * @return Egyptologist the egytologist with the given id or null if no egytologist with this id exists
     */
    public function read($id);

    /**
     * Returns an array of all the egytologists
     * @return array an array of all the egytologists
     */
    public function readAll();

    /**
     * Saves the given egytologist in the storage
     * @param Egyptologist $egyptologist the egytologist to save
     * @return int the id of the saved egytologist
     */
    public function save(Egyptologist $egyptologist);

    /**
     * Updates the egytologist with the given id with the given egytologist
     * @param $id the id of the egytologist to update
     * @param Egyptologist $egyptologist the egytologist to update
     * @return bool true if the egytologist was updated, false otherwise
     */
    public function update($id, Egyptologist $egyptologist);

    /**
     * Deletes the egytologist with the given id
     * @param $id the id of the egytologist to delete
     * @return bool true if the egytologist was deleted, false otherwise
     */
    public function delete($id);

    /**
     * Adds the given image to the egytologist with the given id
     * @param $id the id of the egytologist to add the image to
     * @param $image the image path to add
     * @return bool true if the image was added, false otherwise
     */
    public function addImage($id);

    /**
     * Deletes the image of the egytologist with the given id
     * @param $id the id of the egytologist to delete the image from
     * @return bool true if the image was deleted, false otherwise
     */
    public function deleteImage($id);
}

?>