<?php declare (strict_types=1);

namespace iutnc\deefy\audio\tracks;

require_once 'AudioTrack.php';


class AlbumTrack extends AudioTrack {

    // Attributs
    protected string $artiste;
    protected string $album;
    protected int $annee;
    protected int $numero;


    public function __construct(string $titre, string $nomFichierAudio, string $album, int $numero) {
        parent::__construct($titre, $nomFichierAudio);
        $this->album = $album;
        $this->numero = $numero;
        $this->artiste = "";
    }



    public function setArtiste($val) : void {
        $this->artiste = $val;
    }


    public function setAnnee($val) : void {
        $this->annee = $val;
    }


    public function setGenre($val) : void {
        $this->genre = $val;
    }


    public function setDuree($val) : void {
        $this->duree = $val;
    }

}