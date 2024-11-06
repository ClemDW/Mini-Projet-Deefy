<?php
namespace iutnc\deefy\action;
use iutnc\deefy\auth\Auth;
use iutnc\deefy\repository\DeefyRepository;
use PDO;
use iutnc\deefy\action\Action;
use iutnc\deefy\exception\AuthException;
class SigninAction extends Action {

    public function __construct(){
        parent::__construct();
    }

    public function execute() : string{

        $dr = DeefyRepository::getInstance();

        $res="";
        if($this->http_method == "GET"){
            $res='<form method="POST" action="?action=connexion">
                <input type="email" name="email" placeholder="email" autofocus>
                <input type="password" name="password" placeholder="mot de passe">
                <input type="submit" name="connex" value="Se connecter">
                </form>';

        }else{
            $e = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $p =$_POST['password'];
            $bool = false;
            //on vérifie que l'utilisateur à bien rempli les champs
            try{
                $bool = Auth::authenticate($e, $p);
            }catch(AuthException $e){
                $res = "<p>Identifiant ou mot de passe invalide</p>";
            }

            if($bool){
                // On met l'utilisateur en session
                $_SESSION['user'] = $dr->selectIDUser($e);

                //on recupère les playlists de l'utilisateur
                $t = $dr->getPlaylists($e);
                $res=<<<start
                    <h3>Connexion réussie pour $e</h3>
                    <h3>Playlists de l'utilisateur : </h3>
                    <p>$t</p>
                start;

                //boucle qui affiche les playlists de l'utilisateur
                //un peu de la force brute mais on stock pas l'id de la playlist donc on doit aller le chercher pour chaque palylsit
                foreach ($t as $k => $value) {
                    $nom = $value->__get("nom");
                    while($play=$dr->selectIDPlaylist($nom)){
                        $res.= '<a href="?action=display-playlist&id='.$play['id'].'"> - '.$nom.'</a>';
                    }
                }
            }
        }
        return $res;
    }

}