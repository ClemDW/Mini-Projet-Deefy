<?php
namespace iutnc\deefy\action;
use iutnc\deefy\audio\tracks\PodcastTrack as PodcastTrack;
use iutnc\deefy\render\AudioListRenderer as AudioListRenderer;
class  AddPodcasttrackAction extends Action {
    
    public function __construct(){
        parent::__construct();
    }

    public function execute() : string{
        if($this->http_method == "GET"){    
            $res = <<<addT
                <form method="post" action="?action=add-podcasttrack" enctype="multipart/form-data">
                    <input type="text" name="nom" placeholder="nom" autofocus>
                    <input type="text" name="artiste" placeholder="artiste">
                    <input type="text" name="genre" placeholder="genre">
                    <input type="number" name="duree" placeholder="duree">
                    <input type="date" name="date" placeholder="date">
                    <input type="file" name="file" accept="audio/mpeg"/>
                    <input type="submit" name="ajouter" value="Ajouter Track">
                </form>
                addT;
        }else{
            $l = unserialize($_SESSION['user']['playlist']);
            if($_FILES['file']['type'] === 'audio/mpeg'){
                $upload_dir ='audio/';
                $tmp = $_FILES['file']['tmp_name'];
                echo $tmp;
                if (($_FILES['file']['error'] === UPLOAD_ERR_OK) &&
                    ($_FILES['file']['type'] === 'audio/mpeg') ) {
                    $dest = $upload_dir.$_FILES['file']['name'];
                    move_uploaded_file($tmp, $dest);
                }
                $t = new PodcastTrack(filter_var($_POST['nom'], FILTER_SANITIZE_STRING), "audio/".$_FILES['file']['name']);
                $t->__set("artiste", filter_var($_POST['artiste'], FILTER_SANITIZE_STRING));
                $t->__set("genre", filter_var($_POST['genre'], FILTER_SANITIZE_STRING));
                $t->__set("duree", intval(filter_var($_POST['duree'], FILTER_SANITIZE_NUMBER_INT)));
                $t->__set("annee", filter_var($_POST['date'], FILTER_SANITIZE_STRING));
    
                $l->ajouterPiste($t);
                $_SESSION['user']['playlist'] = serialize($l);
            }
    
    
            $r = new AudioListRenderer($l);
            $res= <<<addT
                {$r->render()}
                <a href="?action=add-podcasttrack" class = "boutton">Ajouter encore une piste</a>
                addT;
        }
        return $res;
    }
}