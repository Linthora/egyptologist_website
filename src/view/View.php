<?php

/**
 * View class used to display the pages (or redirect in some cases)
 */
class View {
    
    /**
     * The content of the view built by the various methods of this class
     */
    protected $content;

    /**
     * The title of the page
     */
    protected $title;

    /**
     * The router used to build the urls
     */
    protected $router;

    /**
     * The menu of the pages
     */
    protected $menu;

    /**
     * The feedback to show to the user (if any)
     */
    protected $feedback;

    /**
     * The constructor of the view
     * @param Router $router the router used to build the urls
     * @param string $feedback the feedback to show to the user (if any)
     */
    public function __construct(Router $router, String $feedback) {
        $this->feedback = $feedback;
        $this->router = $router;
        $this->menu = array(
            "Home" => $this->router->getHomeURL(),
            "List" => $this->router->getListURL(),
            "New" => $this->router->getEgyptologistNewURL(),
            "À propos" => $this->router->getAboutURL()
        );
    }

    /**
     * Method used to render the view
     */
    public function render() {
        include "template.php";
    }

    /**
     * Method used to show the home page
     */
    public function makeHomePage() {
        $this->title = "Welcome to the Egyptologists enthusiasts website !";
        $this->content = 
            '<p>This website is dedicated to all the Egyptologists enthusiasts.</p>
            <p>Please check the list of our favorite egyptologists! <a href="'.$this->router->getListURL().'">List of egyptologists</a></p>
            <p>Or peraps will you add your own favorite egyptologist to the list! <a href="'.$this->router->getEgyptologistNewURL().'">Create a new egyptologist</a></p>';
    }

    /**
     * Method used to show given egyptologist
     * @param Egyptologist $egyptologist the egyptologist to show
     * @param $id the id of the egyptologist
     * @param $updating boolean indicating if the page is used to update the egyptologist currently
     */
    public function makeEgyptologistPage($egyptologist, $id, $updating=false) {
        $this->egyptologist = $egyptologist;
        $this->title = $egyptologist->getName();
        $this->content = '
            <p>Name: '.$egyptologist->getName().'<p>
            <p>Born: '.$egyptologist->getBirthYear().'<br>
            Died: '. (($egyptologist->getDeathYear() == 3001) ? "Still alive" : ($egyptologist->getDeathYear())).'</p>
            <p>Discovery: '.$egyptologist->getDiscovery().'</p>
        ';

        
        if($egyptologist->getImage() == 1) {
            //check if the image exists
            if(file_exists('upload/'.$id.'.jpg')) {
                $this->content .= '<figure><img src="'.$this->router->getImageURL($id).'" alt="image of '.$this->egyptologist->getName() .'">
                <figcaption>Image of '.$this->egyptologist->getName() .'</figcaption></figure>';
                // button to delete the image
                $this->content .= 
                '<form action="' . $this->router->getDeleteImageURL($id) .'" method="POST">
                    <input type="submit" value="Delete Image">
                </form><br><br>';
            } else {
                $this->content .= '<p>Image not found</p>';
            }
        } else {
            $this->content .= '<p>No image available for '.$egyptologist->getName().'</p>';
        }

        
        if(!$updating) {
            $this->content .= 
            '<form action="' . $this->router->getDeleteURL($id). '" method="GET">
                <input type="submit" value="Delete this egyptologist">
            </form><br>';

            $this->content .= 
                '<form action="' . $this->router->getUpdateURL($id) .'" method="GET">
                    <input type="submit" value="Update this egyptologist">
                </form><br>';

            $this->content .= 
            '<br><br><form action="' . $this->router->getListURL() .'" method="GET">
                <input type="submit" value="Back to the list">
            </form>';
        
            // button to add/change the image
            $this->content .= 
            '<br><br><form action="' . $this->router->getUploadURL($id) .'" method="POST" enctype="multipart/form-data">
                <input type="file" name="fileToUpload" id="fileToUpload">
                <input type="submit" value="Upload Image" name="submit">(jpg or png only. Max size: 1Mo. Image will be resized to 200x200px)
            </form>';

        }

    }

    /**
     * Method used to show users that he asked for a non existing egyptologist
     */
    public function makeUnknowEgyptologistPage() {
        $this->title = "Unknown";
        $this->content = "No egyptologist found with this id";   
    }

    /**
     * Method used to show the list of egyptologists
     * @param array $egyptologists the list of egyptologists to show
     * @param bool $search used to indicate if the list is filtered by a search
     * @param string $sort the sort used to sort the list if any
     */
    public function makeListPage(array $egyptologistTab, bool $search, $sort) {
        $this->title = "List of Egyptologists";
        $this->content = "";

        if($search) {
            if(count($egyptologistTab) == 0) {
                $this->router->POSTredirect($this->router->getListURL(), '<p style="color:red; font-weight:bold;"> No egyptologist found with this research. Please try again with other words or dates.</p>');
            } else {
                $this->content .= "<p>Search results:</p>";
            }
        } else if ($sort != null) {
            $this->content .= "<p>Sorted by " . $sort . ":</p>";
        } else {
            $this->content = '<p>List of Egyptologists:</p>';
        }


        $this->content .= "<ul>";
        
        foreach($egyptologistTab as $id => $egyptologist) {
            $this->content .= "<li><a href=\"" . $this->router->getEgyptologistURL($id) . "\">" . $egyptologist->getName() . " (" . $egyptologist->getBirthYear() ." - ". (($egyptologist->getDeathYear() == 3001) ? "Still alive" : ($egyptologist->getDeathYear()))  . ")</a></li>";
        }
        $this->content .= "</ul>";

        // if search, add a button to go back to the list
        if($search) {
            $this->content .= 
                '<form action="' . $this->router->getListURL() .'" method="GET">
                    <input type="submit" value="Back to the list">
                </form><br><br>';
        }

        // add button
        $this->content .= 
            '<form action="' . $this->router->getEgyptologistNewURL() .'" method="GET">
                <input type="submit" value="Add your own favorite egyptologist">
            </form><br><br>';

        // search bar
        $this->content .= 
            '<form action="' . $this->router->getListURL() .'" method="POST">
                <p><u>Searh for egyptologists by name or year of birth/death:</u></p>
                <input type="text" name="research" placeholder="Enter your search">
                <input type="submit" value="Search">
            </form><br><br>';

        // sort propositions
        $this->content .=
            '<form action="' . $this->router->getListURL() .'" method="POST">
                <p><u>Sort egyptologists by name or year of birth:</u></p>
                <select name="sort">
                    <option value="name">Name</option>
                    <option value="birthyear">Year of birth</option>
                </select>
                <input type="submit" value="Sort">
            </form><br>';
    }

    /**
     * Method used to show given data to debug
     */
    public function makeDebugPage($variable) {
        $this->title = 'Debug';
        $this->content = '<pre>'.htmlspecialchars(var_export($variable, true)).'</pre>';
    
    }

    /**
     * Method used to show the page to add a new egyptologist
     * @param EgyptologistBuilder $egyptologistBuilder the builder used to create the egyptologist (empty or with errors and remaining data of last try)
     */
    public function makeNewFormPage($builder) {
        $this->title = "Make a new egyptologist page!";

        $this->addForm($this->router->getEgyptologistNewURL(), $builder, $this->router->getHomeURL());
    }

    /**
     * Method used to show an error page with given message
     * @param string $message the message to show
     */
    public function makeErrorPage($message) {
        $this->title = "Error";
        $this->content = $message;
     
    }

    /**
     * Method used to get confirmation from the user before deleting an egyptologist
     * @param Egyptologist $egyptologist the egyptologist to delete
     * @param $id the id of the egyptologist to delete
     */
    public function makeEgyptologistDeletionPage($egyptologist, $id) {
        $this->title = "Delete egyptologist";
        $this->content = "<p>Are you sure you want to delete " . $egyptologist->getName() . " ?</p>";

        $this->content .= '<form action="' . $this->router->getDeleteURL($id) . '" method="POST">
            <input type="submit" value="Yes">
        </form>';
        $this->content .= '<br><form action="' . $this->router->getEgyptologistURL($id) . '" method="POST">
            <input type="submit" value="No">
        </form>';
    }

    /*
    // Method previously used to show confirmation of deletion. Now using a redirection to the list of egyptologists with feedback of the deletion.
    public function makeEgyptologistDeletionConfirmationPage($egyptologist) {
        $this->title = "Delete egyptologist";
        $this->content = "<p>" . $egyptologist->getName() . " has been deleted.</p>";
        $this->content .= "<p><a href=\"" . $this->router->getListURL() . "\">Back to the list</a></p>";
        
    }*/

    /**
     * Method used to show the page to edit an egyptologist
     * @param Egyptologist $egyptologist the egyptologist to edit
     * @param EgyptologistBuilder $egyptologistBuilder the builder used to create the egyptologist (empty or with errors and remaining data of last try)
     * @param $id the id of the egyptologist to edit
     */
    public function makeEgyptologistUpdatePage($egyptologist, $builder, $id) {
        $this->makeEgyptologistPage($egyptologist, $id, true);
        $this->title = "Update egyptologist: " . $this->title;
        $this->addForm($this->router->getUpdateURL($id), $builder, $this->router->getEgyptologistURL($id));
    }

    /**
     * Method used to add a form to the content of the page with given parameters
     * @param string $actionURL the action of the form
     * @param EgyptologistBuilder $egyptologistBuilder the builder used (empty or with errors and remaining data of last try)
     * @param string $cancelURL the URL to go to if the user cancels the form
     */
    public function addForm($actionURL, $builder, $cancelURL ) {
        $error = $builder->getError();
        $this->content .= 
        '<form action="' . $actionURL . '" method="POST">
            <div class="fields"><div class="names">Name:</div> <input type="text" name="'. EgyptologistBuilder::getNameRef() .'" value="' . $builder->getName() . '"><div style="color:red;">'. $error[EgyptologistBuilder::getNameRef()] .'</div></div>
            <div class="fields"><div class="names">Discovery:</div> <textarea name="'. EgyptologistBuilder::getDiscoveryRef() . '" rows="1" cols="40">'. $builder->getDiscovery().'</textarea><div style="color:red;">'. $error[EgyptologistBuilder::getDiscoveryRef()] .'</div></div>
            <div class="fields"><div class="names">Birth:</div> <input type="text" name="'. EgyptologistBuilder::getBirthYearRef() .'" value="' . $builder->getBirthYear() . '"><div style="color:red;">'. $error[EgyptologistBuilder::getBirthYearRef()] .'</div></div>
            <div class="fields"><div class="names">Death (put \'----\' if still alive):</div> <input type="text" name="'. EgyptologistBuilder::getDeathYearRef() . '" value="' . $builder->getDeathYear() . '"><div style="color:red;">'. $error[EgyptologistBuilder::getDeathYearRef()] .'</div></div>
            <div><input type="submit" value="Save"></div>
        </form><br>';

        
        $this->content .= 
        '<form action="'.$cancelURL.'" method="POST">
            <div><input type="submit" value="Cancel" name="cancel"></div>
        </form><br>';
    }

    /**
     * Method used to redirect user to the newly created egyptologist page with a feedback of confirmation
     * @param $id the id of the newly created egyptologist
     */
    public function displayCreationSuccess($id) {
        $this->router->POSTredirect($this->router->getEgyptologistURL($id), '<p style="color:green; font-weight:bold;">New Egyptologist registered.</p>');
    }

    /**
     * Method used to redirect user to the creation page with a feedback of error
     */
    public function displayCreationFailure() {
        $this->router->POSTredirect($this->router->getEgyptologistNewURL(), '<p style="color:red; font-weight:bold;"> An error occured while trying to save the egyptologist. Please check the form and try again. </p>');
    }

    /**
     * Method used to redirect user to the newly updated egyptologist page with a feedback of confirmation
     * @param $id the id of the newly updated egyptologist
     */
    public function displayUpdateSuccess($id) {
        $this->router->POSTredirect($this->router->getEgyptologistURL($id), '<p style="color:green; font-weight:bold;">Egyptologist updated successfully.</p>');
    }

    /**
     * Method used to redirect user to the update page with a feedback of error
     * @param $id the id of the egyptologist to update
     */
    public function displayUpdateFailure($id) {
        $this->router->POSTredirect($this->router->getUpdateURL($id), '<p style="color:red; font-weight:bold;"> An error occured while trying to update the egyptologist.</p>');
    }

    /**
     * Method used to redirect user to the list of egyptologists with a feedback of confirmation for the deletion
     * @param string $name the name of the deleted egyptologist
     */
    public function displayDeletionSuccess($name) {
        $this->router->POSTredirect($this->router->getListURL(), '<p style="color:green; font-weight:bold;">Egyptologist ('. $name .') deleted successfully.</p>');
    }

    /**
     * Method used to redirect user to the egyptologist to delete with a feedback of error for the deletion
     * @param string $name the name of the egyptologist to delete
     */
    public function displayDeletionFailure($id, $name) {
        $this->router->POSTredirect($this->router->getEgyptologistURL($id), '<p style="color:red; font-weight:bold;"> An error occured while trying to delete: '. $name .'</p>');
    }

    /**
     * Method used to redirect user to the egyptologist which has been uploaded an image with a feedback of confirmation
     * @param $id the id of the egyptologist which has been uploaded an image
     */
    public function displayUploadSuccess($id) {
        $this->router->POSTredirect($this->router->getEgyptologistURL($id), '<p style="color:green; font-weight:bold;">Image uploaded successfully.</p>');
    }

    /**
     * Method used to redirect user to the egyptologist which has been uploaded an image with a feedback of error
     * @param $id the id of the egyptologist which has been uploaded an image
     */
    public function displayUploadFailure($id) {
        $this->router->POSTredirect($this->router->getEgyptologistURL($id), '<p style="color:red; font-weight:bold;">An error occured while trying to upload the image.</p>');
    }

    /**
     * Method used to redirect user to the egyptologist which has been deleted an image with a feedback of confirmation
     * @param $id the id of the egyptologist which has been deleted an image
     */
    public function displayDeleteImageSuccess($id) {
        $this->router->POSTredirect($this->router->getEgyptologistURL($id), '<p style="color:green; font-weight:bold;">Image deleted successfully.</p>');
    }

    /**
     * Method used to redirect user to the egyptologist which has been deleted an image with a feedback of error
     * @param $id the id of the egyptologist which has been deleted an image
     */
    public function displayDeleteImageFailure($id) {
        $this->router->POSTredirect($this->router->getEgyptologistURL($id), '<p style="color:red; font-weight:bold;">An error occured while trying to delete the image.</p>');
    }

    /**
     * Method used to make the About page with content as demanded
     */
    public function makeAboutPage() {
        $this->title = "À propos";
        $this->content = 
            '<p>Prénom, Nom: --Linthora--</p>
            <p>NUMETU: --NUMETU--</p>
            <p>Groupe: --</p>
            <p>Projet: Egyptologists enthusiasts website</p>
            <p>Objets: des egyptologues</p>
            <p>Année: 2022-2023</p>
            <br>
            <p>Réalisations de base: Tout a été réalisé comme demandé.</p>
            <br>
            <p>Compléments réalisés:</p>
            <ul>
                <li>Routage via le chemin virtuel (PATH_INFO).</li>
                <li>Possibilité de trier la liste des egyptologues par leur nom ou par année de naissance.</li>
                <li>Possibilité de filtrer la liste des egyptologues via un champ de recherche réalisé sur leur nom ou date de naissance ou de mort.</li>
                <li>Possibilité d\'illustrer un egyptologue avec une image.</li>
                <li>Possibilité de supprimer l\'image d\'un egyptologue.</li>
            </ul>
            <br>
            <p>Note pour les images: J\'ai pris parti de faire une copie via Imagick comme indiqué et donc de ne pas autoriser l\'utilisateur à choisir le nom des fichiers ni ses meta-données. Dans la base on peut donc réduire le champ image à un booléen. Ce qui assure également une sécurité supplémentaire en ne laissant pas la possiblité de faire remonter des fichiers autres que ceux prévu à un utilisateur malveillant.</p>
            <p>Il est possible de réaliser un reinit avec EgyptologistStorageMySQL.php. Tout en gardant récuprérant l\'image de base de Champolion.</p>
            <p>Un petit sommaire des méthodes pour les fichiers PHP principaux est disponible à la racine dans summary.txt.</p>
            ';
    }
}
