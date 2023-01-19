<?php

/**
 * Conctroller class to manage all the actions of the application
 */
class Controller {

    /**
     * The view used by the controller
     */
    protected $view;

    /**
     * The storage used by the controller
     */
    protected $egypologistStorage;

    /**
     * The 2 possible methods to sort the egyptologists
     */
    protected const sortBy = ["birthyear", "name"];

    /**
     * Constructor of the controller
     * @param EgyptologistStorage $egyptologistStorage the storage to use for our application
     * @param View $view the view to use for our application
     */
    public function __construct(EgyptologistStorage $egyptologistStorage, View $view) {
        $this->view = $view;
        $this->egypologistStorage = $egyptologistStorage;
    }

    /**
     * Method to show the home page of the application
     */
    public function showHome() {
        $this->view->makeHomePage();
    }

    /**
     * Method used to show one egyptologist information if the id is valid
     */
    public function showInformation($id) {
        //$this->view->makeDebugPage($this->egypologistStorage->read($id));
        if($this->egypologistStorage->read($id) != null) {
            $this->view->makeEgyptologistPage($this->egypologistStorage->read($id), $id);
        } else {
            $this->view->makeUnknowEgyptologistPage();
        }
    }

    /**
     * Method used to show the About page
     */
    public function showAbout() {
        $this->view->makeAboutPage();
    }   

    /**
     * Method used to show the list of egyptologists
     * @param array $data Associative array containing the data to filter or sort the list if asked.
     */
    public function showList($data=null) {
        $list = null;
        
        $search = false;
        $sort = null;

        if($data != null && key_exists("sort", $data) && in_array($data["sort"], self::sortBy)) {
            $sort = $data["sort"]; 
            // previously I used usort but thinking this through I remembered that I could just sort directly in the database
            // You can still find the usort version in EgyptianStorageFile and EgyptianStorageStub (I updated them so that the controller can used them same way)    
        } 

        $list = $this->egypologistStorage->readAll($sort);

        if($data != null && key_exists("research", $data) && $data["research"] != "") {
            $list = array_filter($list, function($egyptologist) use ($data) {
                return preg_match("/" . $data["research"] . "/i", $egyptologist->getName()) || preg_match("/" . $data["research"] . "/i", $egyptologist->getBirthyear()) || preg_match("/" . $data["research"] . "/i", $egyptologist->getDeathyear());
            });
            $search = true;
        }

        $this->view->makeListPage($list, $search, $sort);
    }

    /**
     * Method used to show the form to create a new egyptologist
     */
    public function showNewForm() {
        $builder = $this->newEgyptologist();
        $this->view->makeNewFormPage($builder);
    }

    /**
     * Method used to save the new egyptologist in the storage if the data is valid.
     * If the data is not valid, the form is shown again with the errors.
     * If the data is valid, the user is redirected to the page of the newly created egyptologist.
     * @param array $data Associative array containing the given data to create the new egyptologist.
     */
    public function saveNewEgyptologist(array $data) {
        $data[EgyptologistBuilder::getImageRef()] = 0; // we don't want to be susceptible to a malicious user trying to reach other files (peraps it is less a risk now that we use a boolean to tell if the egytologist has an image or not)

        $builder = new EgyptologistBuilder($data);

        if($builder->isValid()) {
            $egyptologist = $builder->createEgyptologist();
            $id = $this->egypologistStorage->save($egyptologist);
            unset($_SESSION['currentNewEgyptologist']);
            $this->view->displayCreationSuccess($id);
        } else {
            $_SESSION['currentNewEgyptologist'] = $builder;
            $this->view->displayCreationFailure();
        }
    }

    /**
     * Method used to show the form to edit an egyptologist if the id is valid
     * @param int $id the id of the egyptologist to edit
     */
    public function showEgyptologistUpdatePage($id) {
        $egyptologist = $this->egypologistStorage->read($id);
        if($this->egypologistStorage->read($id) != null) {
            $builder = $this->changingEgyptologist($egyptologist, $id);
            $this->view->makeEgyptologistUpdatePage($this->egypologistStorage->read($id), $builder, $id);
        } else {
            $this->view->makeErrorPage("Error: invalid id");
        }
    }

    /**
     * Method used to save the changes of an egyptologist if the data is valid.
     * If the data is not valid, the form is shown again with the errors.
     * If the data is valid, the user is redirected to the page of the edited egyptologist.
     * @param array $data Associative array containing the given data to edit the egyptologist.
     * @param int $id the id of the egyptologist to edit
     */
    public function updateEgyptologist($id, array $data) {
        $egyptologist = $this->egypologistStorage->read($id);
        if($egyptologist != null) {
            $data[EgyptologistBuilder::getImageRef()] = $egyptologist->getImage();
            $builder = new EgyptologistBuilder($data);
            if($builder->isValid()) {
                $egyptologist = $builder->createEgyptologist();
                if($this->egypologistStorage->update($id, $egyptologist) === true) {
                    unset($_SESSION['currentChangingEgyptologist' . $id]);
                    $this->view->displayUpdateSuccess($id);
                } else {
                    $this->view->displayUpdateFailure($id);
                }
            } else {
                $_SESSION['currentChangingEgyptologist' . $id] = $builder;
                $this->view->displayUpdateFailure($id);
            }
        } else {
            $this->view->makeErrorPage("Error: invalid id");
        }
    }

    /**
     * Method used to get the builder of a new egyptologist based on the data in the session if it exists.
     * If it doesn't exist, a new builder is created.
     */
    public function newEgyptologist() {
        if(key_exists('currentNewEgyptologist', $_SESSION)) {
            return $_SESSION['currentNewEgyptologist'];
        }
        return new EgyptologistBuilder(array(
            EgyptologistBuilder::getNameRef() => '',
            EgyptologistBuilder::getDiscoveryRef() => '',
            EgyptologistBuilder::getBirthYearRef() => '',
            EgyptologistBuilder::getDeathYearRef() => '',
        ));
    }

    /**
     * Method used to get the builder of a edited egyptologist based on the data in the session if it exists.
     * If it doesn't exist, a new builder is created based on given egyptologist.
     * @param Egyptologist $egyptologist the egyptologist to edit
     * @param int $id the id of the egyptologist we are editing
     */
    public function changingEgyptologist($egyptologist, $id) {
        // we use id to make sure we store the right build for the right egyptologist
        if(key_exists('currentChangingEgyptologist' .$id, $_SESSION)) {
            return $_SESSION['currentChangingEgyptologist'.$id];
        }
        return new EgyptologistBuilder( array(
            EgyptologistBuilder::getNameRef() => $egyptologist->getName(),
            EgyptologistBuilder::getDiscoveryRef() => $egyptologist->getDiscovery(),
            EgyptologistBuilder::getBirthYearRef() => $egyptologist->getBirthYear(),
            EgyptologistBuilder::getDeathYearRef() => $egyptologist->getDeathYear(),
        ));
    }

    /**
     * Method used to show the debug page with given data
     * @param $variable the data to display
     */
    public function showDebugPage($variable) {
        $this->view->makeDebugPage($variable);
    }

    /**
     * Method used to ask confirmation to delete an egyptologist
     * @param int $id the id of the egyptologist to delete
     */
    public function showEgyptologistDeletionPage($id) {
        // verifies if the id is valid
        if($this->egypologistStorage->read($id) != null) {
            $this->view->makeEgyptologistDeletionPage($this->egypologistStorage->read($id), $id);
        } else {
            $this->view->makeErrorPage("Error: invalid id");
        }
    }

    /**
     * Method used to delete an egyptologist
     * If the deletion is successful, the user is redirected to the list of egyptologists with a success message.
     * Otherwise, the user is redirected to the egyptologist page with an error message.
     * @param int $id the id of the egyptologist to delete
     */
    public function deleteEgyptologistConfirmation($id) {
        if($this->egypologistStorage->read($id) != null) {
            $egyptologist = $this->egypologistStorage->read($id);
            if($this->egypologistStorage->delete($id) === true) {
                $this->view->displayDeletionSuccess($egyptologist->getName());
            } else {
                $this->view->displayDeletionFailure($id, $egyptologist->getName());
            }
        } else {
            $this->view->makeErrorPage("Error: invalid id");
        }
    }

    /**
     * Method used to show the error page with given message
     * @param string $message the message to display
     */
    public function showError($error) {
        $this->view->makeErrorPage($error);
    }
    
    /**
     * Method used to upload an image for an egyptologist if the data is valid.
     * If the data is not valid, we redirect to the egyptologist page with an error message.
     * @param int $id the id of the egyptologist to upload the image for
     * @param array $FILE Associative array containing the given data to upload the image.
     */
    public function uploadEgyptologist($id, $FILE) {
        if($this->egypologistStorage->read($id) == null) {
            $this->view->makeErrorPage("Error: invalid id");
        }

        $file = $FILE['fileToUpload'];

        if($file['error'] != 0) {
            $this->view->displayUploadFailure($id);
            return;
        }

        $fileType = exif_imagetype($file['tmp_name']);
        
        if($fileType == IMAGETYPE_JPEG || $fileType == IMAGETYPE_PNG || $fileType == IMAGETYPE_JPEG2000) {
            
            if($file['size'] < 1000000) {
                $image = new Imagick($file['tmp_name']);
                $image->resizeImage(200, 200, Imagick::FILTER_LANCZOS, 1);

                if(file_exists('upload/' . $id . '.jpg')) {
                    unlink('upload/' . $id . '.jpg');
                }

                if($image->writeImage('upload/' . $id . '.jpg')) {
                    $this->egypologistStorage->addImage($id);
                    $this->view->displayUploadSuccess($id);
                } else {
                    $this->view->displayUploadFailure($id);
                }
            } else {
                $this->view->displayUploadFailure($id);
            }
        } else {
            $this->view->displayUploadFailure($id);
        }
    }

    /**
     * Method used to delete an image for an egyptologist.
     * If the deletion is successful, the user is redirected to the egyptologist page with a success message.
     * Otherwise, the user is redirected to the egyptologist page with an error message.
     * @param int $id the id of the egyptologist to delete the image for
     */
    public function deleteEgyptologistImage($id) {
        $egyptologist = $this->egypologistStorage->read($id);
        if($egyptologist == null) {
            $this->view->makeErrorPage("Error: invalid id");
        }

        if($egyptologist->getImage() == 1) {
            if(file_exists('upload/' . $id . '.jpg')) {
                unlink('upload/' . $id . '.jpg');
                $this->egypologistStorage->deleteImage($id);
                $this->view->displayDeleteImageSuccess($id);
            } else {
                $this->view->displayDeleteImageFailure($id);
            }
        } else {
            $this->view->displayDeleteImageFailure($id);
        }       

    }

}