<?php

error_reporting(E_ALL);

/**
 * Router class used to route all the requests to the right action in the controller
 */
class Router {

    /**
     * Main method of the router
     * @param EgyptologistStorage $storage the storage to use for our application
     */
    public function main(EgyptologistStorage $egyptologistStorage) {
        
        
        session_start();
        
        // create the view that will be used by the controller with given feedback if any
        $view = new View($this, key_exists('feedback', $_SESSION) ? $_SESSION['feedback'] : "");
        unset($_SESSION['feedback']);
        
        $controller = new Controller($egyptologistStorage, $view);

        $isShow = false;
    
        if(key_exists("PATH_INFO", $_SERVER)) {
            $path = $_SERVER["PATH_INFO"];
            $pathTab = explode("/", $path);
                
            switch($pathTab[1]) {
                case "egyptologist":
                    if(count($pathTab) == 3) {
                        if(key_exists("cancel", $_POST) && key_exists("currentChangingEgyptologist".$pathTab[2], $_SESSION)) {
                            unset($_SESSION['currentChangingEgyptologist'.$pathTab[2]]);
                        }
                        $controller->showInformation($pathTab[2]);
                    }
                    else if($pathTab[3] == "delete") {
                        // check if post or get method
                        if($_SERVER["REQUEST_METHOD"] == "GET") {
                            $controller->showEgyptologistDeletionPage($pathTab[2]);
                        } else if($_SERVER["REQUEST_METHOD"] == "POST") {
                            $controller->deleteEgyptologistConfirmation($pathTab[2]);
                        }
                    }
                    else if($pathTab[3] == "update") {
                        if($_SERVER["REQUEST_METHOD"] == "GET") {
                            $controller->showEgyptologistUpdatePage($pathTab[2]);
                        } else if($_SERVER["REQUEST_METHOD"] == "POST") {
                            $controller->updateEgyptologist($pathTab[2], $_POST);
                        }
                    }
                    else if($pathTab[3] == "upload") {
                        if($_SERVER["REQUEST_METHOD"] == "POST") {
                            $controller->uploadEgyptologist($pathTab[2], $_FILES);
                        }
                    }
                    else if($pathTab[3] == "deleteImage") {
                        if($_SERVER["REQUEST_METHOD"] == "POST") {
                            $controller->deleteEgyptologistImage($pathTab[2]);
                        }
                    }
                    else {
                        $controller->showInformation($pathTab[2]);
                    }
                    $isShow = true;
                    break;
                case "new":
                    if($_SERVER["REQUEST_METHOD"] == "POST") {
                        $controller->saveNewEgyptologist($_POST);
                    }
                    else if ($_SERVER["REQUEST_METHOD"] == "GET") {
                        
                        $controller->showNewForm();
                    }
                    $isShow = true;
                    break;
                case "list":
                    if($_SERVER["REQUEST_METHOD"] == "POST") {
                        $controller->showList($_POST);
                    }
                    else {
                        $controller->showList();
                    }
                    $isShow = true;
                    break;
                case "about":
                    $controller->showAbout();
                    $isShow = true;
                    break;
                default:
                    break;
            }
        }
        if(!$isShow) {
            if(key_exists("cancel", $_POST)) {
                unset($_SESSION['currentNewEgyptologist']);
            }
            $controller->showHome();
        }
     
        $view->render();
    }

    /**
     * Method used to redirect to given url and sets the feedback in the session to show after the redirection
     * @param string $url the url to redirect to
     * @param string $feedback the feedback to show after the redirection
     */
    public function POSTredirect($url, $feedback) {
        header("Location: " . $url, true, 303);
        $_SESSION['feedback'] = $feedback;
    }

    /**
     * Method used to build the url to reach home page
     */
    public function getHomeURL() {
        $https = key_exists("HTTPS", $_SERVER) && $_SERVER["HTTPS"] == "on";
        return ($https ? "https" : "http") . '://' . $_SERVER["HTTP_HOST"] . preg_split('/index\.php/', $_SERVER['PHP_SELF'])[0] . 'index.php';
    }

    /**
     * Method used to build the url to reach given egyptologist
     * @param int $id the id of the egyptologist
     */
    public function getEgyptologistURL($id) {
        $https = key_exists("HTTPS", $_SERVER) && $_SERVER["HTTPS"] == "on";
        return ($https ? "https" : "http") . '://' . $_SERVER["HTTP_HOST"] . preg_split('/index\.php/', $_SERVER['PHP_SELF'])[0] . 'index.php/egyptologist/' . $id;
    }

    /**
     * Method used to build the url for creating a new egyptologist
     */
    public function getEgyptologistNewURL() {
        $https = key_exists("HTTPS", $_SERVER) && $_SERVER["HTTPS"] == "on";
        return ($https ? "https" : "http") . '://' . $_SERVER["HTTP_HOST"] . preg_split('/index\.php/', $_SERVER['PHP_SELF'])[0] . 'index.php/new';
    }

    /**
     * Method used to build the url to reach the list of egyptologists
     */
    public function getListURL() {
        $https = key_exists("HTTPS", $_SERVER) && $_SERVER["HTTPS"] == "on";
        return ($https ? "https" : "http") . '://' . $_SERVER["HTTP_HOST"] . preg_split('/index\.php/', $_SERVER['PHP_SELF'])[0] . 'index.php/list';
    }

    /**
     * Method used to build the url to reach deletion page or to delete given egyptologist.
     * @param int $id the id of the egyptologist
     */
    public function getDeleteURL($id) {
        $https = key_exists("HTTPS", $_SERVER) && $_SERVER["HTTPS"] == "on";
        return ($https ? "https" : "http") . '://' . $_SERVER["HTTP_HOST"] . preg_split('/index\.php/', $_SERVER['PHP_SELF'])[0] . 'index.php/egyptologist/' . $id . '/delete';
    }

    /**
     * Method used to build the url to reach updating page or to update given egyptologist.
     * @param int $id the id of the egyptologist
     */
    public function getUpdateURL($id) {
        return $this->getEgyptologistURL($id) . '/update';
        //$https = key_exists("HTTPS", $_SERVER) && $_SERVER["HTTPS"] == "on";
        //return ($https ? "https" : "http") . '://' . $_SERVER["HTTP_HOST"] . preg_split('/index\.php/', $_SERVER['PHP_SELF'])[0] . 'index.php/egyptologist/' . $id . '/update';
    }

    /**
     * Method used to build the url to reach the About page
     */
    public function getAboutURL() {
        $https = key_exists("HTTPS", $_SERVER) && $_SERVER["HTTPS"] == "on";
        return ($https ? "https" : "http") . '://' . $_SERVER["HTTP_HOST"] . preg_split('/index\.php/', $_SERVER['PHP_SELF'])[0] . 'index.php/about';
    }

    /**
     * Method used to build the url to reach the upload page or to upload an image for given egyptologist.
     * @param int $id the id of the egyptologist
     */
    public function getUploadURL($id) {
        return $this->getEgyptologistURL($id) . '/upload';
    }

    /**
     * Method used to build the url to reach the image of given egyptologist.
     * @param int $id the id of the egyptologist
     */
    public function getImageURL($id) {
        $https = key_exists("HTTPS", $_SERVER) && $_SERVER["HTTPS"] == "on";
        return ($https ? "https" : "http") . '://' . $_SERVER["HTTP_HOST"] . preg_split('/index\.php/', $_SERVER['PHP_SELF'])[0] . 'upload/' . $id . '.jpg';
    }

    /**
     * Method used to build the url to delete the image of given egyptologist.
     * @param int $id the id of the egyptologist
     */
    public function getDeleteImageURL($id) {
        $https = key_exists("HTTPS", $_SERVER) && $_SERVER["HTTPS"] == "on";
        return ($https ? "https" : "http") . '://' . $_SERVER["HTTP_HOST"] . preg_split('/index\.php/', $_SERVER['PHP_SELF'])[0] . 'index.php/egyptologist/' . $id . '/deleteImage';
    }
}
?>
