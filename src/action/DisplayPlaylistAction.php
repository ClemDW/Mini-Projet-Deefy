<?php
namespace iutnc\deefy\action;
use Exception;
use iutnc\deefy\auth\Auth;
use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;

class DisplayPlaylistAction extends Action {
    
    public function __construct(){
        parent::__construct();
    }

    public function execute() : string{
        $res="";
        $sa = DeefyRepository::getInstance();
        if(isset($_GET['idp'])){
            if($sa->checkAccess(intval($_GET['idp']))){
                $p = Playlist::find(intval($_GET['idp']));
                $r  = new AudioListRenderer($p);
                $res = $r->render();
            }else{
                try{
                    $p = Playlist::find(intval($_GET['idp']));
                    $res = "Accès refusé";
                }catch(Exception $e){
                    $res = "Playlist avec id {$_GET['idp']} n'éxiste pas";
                }
            }
        }else{
            if($this->http_method === "GET"){
                $res='<form method="post" action="?action=display-playlist">
                    <input type="number" name="idp" placeholder="id" autofocus>
                    <input type="submit" name="connex" value="Chercher">
                    </form>';
            }else{
                $id = filter_var($_POST['idp'], FILTER_SANITIZE_NUMBER_INT);
                $res=<<<aff
                <a href="?action=display-playlist&idp=$id">-> Afficher PlayList</a>    
                aff;
            }
        }
        return $res;
    }
}