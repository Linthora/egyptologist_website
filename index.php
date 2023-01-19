<?php

/**
 *  we indicate that the paths of the files we include will be relative to the src directory.
 */
set_include_path("./src");

/* Inclusion des classes utilisées dans ce fichier */
require_once("view/View.php");
require_once("Router.php");
require_once("controller/Controller.php");
require_once("model/Egyptologist.php");
require_once("model/EgyptologistBuilder.php");
require_once("model/EgyptologistStorage.php");

//require_once("model/EgyptologistStorageStub.php");
//require_once("lib/ObjectFileBD.php");
//require_once("model/EgyptologistStorageFile.php");

require_once("model/EgyptologistStorageMySQL.php");
require_once("/users/21914304/private/mysql_config.php");

//$router = new Router(new EgyptologistStorageStub());
//$storage = new EgyptologistStorageStub();

//$storage = new EgyptologistStorageFile("/users/21914304/tmp/egyptologists.txt");

$pdo = new PDO("mysql:host=" . MYSQL_HOST . ";port=". MYSQL_PORT .";dbname=" . MYSQL_DB . ";charset=utf8", MYSQL_USER, MYSQL_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$storage = new EgyptologistStorageMySQL($pdo);
//$storage->reinit(); // used to reinit the sql database as well
$router = new Router();
$router->main($storage);
?>
