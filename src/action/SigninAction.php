<?php
namespace iutnc\deefy\action;
use iutnc\deefy\auth\Auth;
use iutnc\deefy\repository\DeefyRepository;
use PDO;
use \iutnc\deefy\exception\AuthException;
class SigninAction extends Action {

    public function __construct(){
        parent::__construct();
    }

    public function execute() : string{

        $dr = DeefyRepository::getInstance();

        $res="";
        if($this->http_method == "GET"){
            $res='<form method="post" action="?action=register">
                <input type="email" name="email" placeholder="email" autofocus>
                <input type="text" name="password" placeholder="mot de passe">
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

                //on recupère les playlists de l'utilisateur
                $t =  $dr->getPlaylists($e);
                $res=<<<start
                    <h3>Connexion réussie pour $e</h3>
                    <h3>Playlists de l'utilisateur : </h3>
                    <p>$t</p>
                start;

                //boucle qui affiche les playlists de l'utilisateur
                //un peu de la force brute mais on stock pas l'id de la playliste donc on doit aller le chercher pour chaque palylsit
                foreach ($t as $k => $value) {
                    $nom = $value->__get("nom");
                    $query ="SELECT id from playlist p where p.nom like ?";
                    $playlists = $this->pdo->prepare($query);
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

    public function checkAccess(int $id):bool{
        $res=false;

        $query = "SELECT u.email as email from user u inner join user2playlist p on u.id = p.id_user where id_pl = ? ";
        $prep = $this->pdo->prepare($query);
        $prep->bindParam(1,$id);
        $bool = $prep->execute();
        $d = $prep->fetchall(PDO::FETCH_ASSOC);
        if($bool && sizeof($d)>0){
            if($d[0]['email'] === $_SESSION['user']['id']||$_SESSION['user']['role']===100){
                $res=true;
            }
        }
        return $res;
    }

}