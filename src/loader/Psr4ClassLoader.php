<?php declare (strict_types=1);

namespace iutnc\deefy\loader;

class Psr4ClassLoader {

    // Attributs
    private string $prefixeNameSpace, $racine;


    /**
     * Constructeur
     */
    public function __construct(string $prefixeNameSpace, string $racine) {
        $this->prefixeNameSpace = $prefixeNameSpace;
        $this->racine = $racine;
    }


    /**
     * Méthode loadClass qui charge la classe donnée en paramètre
     */
    public function loadClass(string $nomClass) {
        // On retire le prefixe du namespace du nom de la classe
        $cheminFichierSansPrefixe = str_replace($this->prefixeNameSpace, "", $nomClass);
        // On ajoute la racine
        $cheminFichier = $this->racine . "/" . $cheminFichierSansPrefixe;
        // On remplace les DIRECTORY_SEPARATOR par des / et on ajoute l'extension .php
        $cheminFichier = str_replace(DIRECTORY_SEPARATOR, "/", $cheminFichier) . ".php";
        // Si le fichier existe, on lance la fonction require_once sur ce fichier
        if (is_file($cheminFichier)) require_once($cheminFichier);
    }


    /**
     * Méthode register qui enregistre l'autoloader
     */
    public function register() {
        spl_autoload_register(function (string $nomClass) { $this->loadClass($nomClass); });
    }

}