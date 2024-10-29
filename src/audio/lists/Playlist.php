<?php declare (strict_types=1);

namespace iutnc\deefy\audio\lists;

use iutnc\deefy\audio\tracks\AudioTrack;

require_once('AudioList.php');

class Playlist extends AudioList {


    public function __construct(string $nom, array $list) {
        parent::__construct($nom, $list);
    }


    public function addTrack(AudioTrack $track) : bool {
        $estTrouve = false;
        foreach ($this->listAudioTrack as $key => $val) {
            if (($val->nomFichierAudio === $track->nomFichierAudio) && ($val->titre === $track->titre) && !$estTrouve) {
                  $estTrouve = true;
            }
        }
        if (!$estTrouve) {
            $this->listAudioTrack[] = $track; 
            $this->dureeTotale += $track->duree;
            $this->nbPistes++;
            return true;
        }
        return false;
    }


    public function suppTrack(int $indice) : bool {
        if (isset($this->listAudioTrack, $indice)) {
            $this->dureeTotale -= $this->listAudioTrack[$indice]->duree;
            $this->nbPistes--;
            unset($this->listAudioTrack[$indice]);
            return true;
        }
        return false;
    }


    public function addListTrack(array $list) : void {
        foreach ($list as $key1 => $val1) {
            $this->addTrack($val1);
        }
    }


    public function __toString() : string {
        return json_encode(get_object_vars($this));
    }


}