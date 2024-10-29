<?php declare (strict_types=1);

namespace iutnc\deefy\audio\tracks;




class PodcastTrack extends AudioTrack {

    // Attributs
    protected string $auteur;
    protected string $date;


    public function __construct(string $titre, string $nomFichierAudio) {
        parent::__construct($titre, $nomFichierAudio);
        $this->auteur = "";
        $this->date = "";
    }

    public function setAuteur(string $val) : void {
        $this->auteur = $val;
    } 

    public function setDate(string $val) : void {
        $this->date = $val;
    } 

}