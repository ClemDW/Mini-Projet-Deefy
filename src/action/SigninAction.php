<?php
namespace iutnc\deefy\action;
use PDO;
use iutnc\deefy\render\AudioListRenderer as AudioListRenderer;
class SigninAction extends Action {
    
    public function __construct(){
        parent::__construct();
    }

    public function execute() : string{
        $res="";
        if($this->http_method == "GET"){
            $res='<form method="post" action="?action=sign-in">
                <input type="email" name="email" placeholder="email" autofocus>
                <input type="text" name="password" placeholder="mot de passe">
                <input type="submit" name="connex" value="Connexion">
                </form>';
        }else{
            $e = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $p =$_POST['password'];
            $bool = false;
            //on vérifie que l'utilisateur à bien rempli les champs 
            try{
                $bool = \iutnc\deefy\auth\Auth::authenticate($e, $p);
            }catch(\iutnc\deefy\exception\AuthException $e){
                $res = "<p>Identifiant ou mot de passe invalide</p>";
            }

            if($bool){

                //on recupère les playlists de l'utilisateur
                $u = new \iutnc\deefy\user\User($e, $p,1);
                $t =  $u->getPlaylists();
                $res=<<<start
                    <h3>Connexion réussite pour $e</h3>
                    <h3>Playlists de l'utilisateur : </h3>
                start;


                $bd = DeefyRepository::getInstance();
                //boucle qui affiche les playlists de l'utilisateur
                //un peu de la force brute mais on stock pas l'id de la playliste donc on doit aller le chercher pour chaque palylsit
                foreach ($t as $k => $value) {
                    $nom = $value->__get("nom");
                    $query ="SELECT id from playlist p where p.nom like ?";
                    $playlists = $bd->prepare($query);
                    $playlists -> bindParam(1, $nom);
                    $playlists -> execute();
            
                    while($play=$playlists->fetch(PDO::FETCH_ASSOC)){
                        $res.= '<a href="?action=display-playlist&id='.$play['id'].'"> - '.$nom.'</a>';                    
                    }                    
                }
            }
        }
        return $res;
    }
}