<?php
declare(strict_types=1);
namespace iutnc\deefy\audio\lists;

require_once 'vendor/autoload.php';

use iutnc\deefy\repository\DeefyRepository;
use Exception;
use iutnc\deefy\audio\tracks\AudioTrack as AudioTrack;
use iutnc\deefy\audio\tracks\AlbumTrack as AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack as PodcastTrack;
use iutnc\deefy\audio\lists\AudioList as AudioList;
class PlayList extends AudioList{

    public function __construct(String $nom, iterable $tab){
        parent::__construct($nom, $tab);
    }

    public function ajouterPiste(AudioTrack $piste):void{
        $this->list[] = $piste;
        $this->dureeTotale += $piste->duree;
        $this->nbPistes++;
    }

    public function supprimerPiste(int $indice):void{
        $this->list->unset($indice);
    }

    public function ajouterListe(AudioList $liste):void{
        $temp = [];
        foreach ($liste->list as $value) {
            if(!in_array($value, $this->list)) $this->list[] = $value;
        }
    }

    public function getTrackList():array{

        $tab=[];
        $df = DeefyRepository::getInstance();
        while($trc=$df->trackBD($this->nom)){
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
        $bd = DeefyRepository::getInstance();
        $data = $bd->playlistFind($id);

        if(sizeof($data)<=0){
            throw new Exception("Playlist inconnue");
        }
        $p = new PlayList($data[0]['nom'], []);
        $p->getTrackList();
        return $p;
    }
}