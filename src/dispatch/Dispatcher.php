<?php

namespace iutnc\deefy\dispatch;

use \iutnc\deefy\action\DefaultAction;
use \iutnc\deefy\action\DisplayPlaylistAction;
use \iutnc\deefy\action\AddPlaylistAction;
use \iutnc\deefy\action\AddPodcastTrackAction;
use iutnc\deefy\action\SigninAction;
use \iutnc\deefy\action\AddUserAction;


class Dispatcher {

    /**
     * Méthode qui lance le dispatcher
     */
    public function run() : void {

        // On regarde (avec un switch) quelle action faire en récupérant l'action
        switch ($_GET['action']) {

            case "default" :
                $class = new DefaultAction();
                break;

            case "add-playlist" :
                $class = new AddPlaylistAction();
                break;

            case "add-track" :
                $class = new AddPodcastTrackAction();
                break;

            case "display-playlist" :
                $class = new DisplayPlaylistAction();
                break;

            case "add-user" :
                $class = new AddUserAction();
                break;

            case "connexion" :
                $class = new SigninAction();
                break;

            default :
                $class = new DisplayPlaylistAction();
                break;

        }

        // On affiche la page en executant la méthode execute d'une classe Action
        $this->renderPage($class->execute());

    }



    /**
     * Méthode qui ajoute le morceau de page à la page complète
     */
    private function renderPage(string $html) : void {

        echo <<<END

            <html>

                <center><h1> Spodeefy </h1></center>

                <div>
                    <form method="get">
                        <button name='action' value='default'> Page principale </button><br><br>
                        <button name='action' value='display-playlist'> Afficher la playlist </button><br><br>
                        <button name='action' value='add-playlist'> Ajouter une playlist </button><br><br>
                        <button name='action' value='add-track'> Ajouter une track </button><br><br>
                        <button name='action' value='add-user'> S'enregistrer </button><br><br>
                        <button name='action' value='connexion'> Se connecter </button><br><br>
                    </form>
                </div>

                <br>
                <br>

                $html

            </html>

        END;

    }


}