<?php
declare(strict_types=1);
namespace iutnc\deefy\audio\lists;

require_once 'vendor/autoload.php';
use PDO;
use Exception;
use iutnc\deefy\audio\tracks\AudioTrack as AudioTrack;
use iutnc\deefy\audio\tracks\AlbumTrack as AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack as PodcastTrack;
use iutnc\deefy\audio\lists\AudioList as AudioList;
use iutnc\deefy\db\ConnectionFactory;
class PlayList extends AudioList{

    public function __construct(String $nom, iterable $tab){
        parent::__construct($nom, $tab);
    }

    public function ajouterPiste(AudioTrack $piste):void{
        $this->list[] = $piste;
        $this->dureeTotale += $piste->duree;
        $this->nbPiste++;
    }

    public function suprimerPiste(int $indice):void{
        $this->list->unset($indice);
    }

    public function ajouterListe(AudioList $liste):void{
        $temp = [];
        foreach ($liste->list as $value) {
            if(!in_array($value, $this->list)) $this->list[] = $value;
        }
    }

    public function getTrackList():array{
        $bd = \iutnc\deefy\db\ConnectionFactory::makeConnection();
        $query ="SELECT * from playlist p inner join playlist2track p2 on p2.id_track = p.id inner join track t on p2.id_track = t.id  where p.nom like ?";
        $track = $bd->prepare($query);
        $track -> bindParam(1, $this->nom);
        $track -> execute();
    
        $tab=[];
        while($trc=$track->fetch(PDO::FETCH_ASSOC)){
            $t = null;
            if($trc['type']==="A"){
                $t = new AlbumTrack($trc['titre'], $trc['filename']);
                $t->__set("artiste",$trc['artiste_album']);
                $t->__set("genre", $trc['genre']);
                $t->__set("duree",$trc['duree'] );
                $t->__set("annee", strval($trc['annee_album']));
                $t->__set("album", $trc['titre_album']);
                $t->__set("numPiste", $trc['numero_album']);
            }else{
                $t = new PodcastTrack($trc['titre'], $trc['filename']);
                $t->__set("artiste",$trc['auteur_podcast']);
                $t->__set("genre", $trc['genre']);
                $t->__set("duree",$trc['duree'] );
                $t->__set("annee", $trc['date_posdcast']);
            }
            $this->ajouterPiste($t);
            $tab[]=$t;
        }
        return $tab;
    }

    public static function find(int $id):mixed{
        $bd = ConnectionFactory::makeConnection();
        $query ="SELECT nom from playlist where id = ?";
        $prep = $bd->prepare($query);
        $prep->bindParam(1,$id);
        $prep->execute();
        $data = $prep->fetchall(PDO::FETCH_ASSOC);
        
        if(sizeof($data)<=0){
            throw new Exception("Playliste inconnue");
        }
        $p = new PlayList($data[0]['nom'], []);
        $p->getTrackList();
        return $p;
    }

}