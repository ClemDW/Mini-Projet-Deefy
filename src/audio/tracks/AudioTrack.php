<?php declare (strict_types=1);

namespace iutnc\deefy\audio\tracks;

use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;


abstract class AudioTrack {

    // Attributs
    protected string $titre;
    protected string $genre;
    protected int $duree;
    protected string $nomFichierAudio; 


    public function __construct(string $titre, string $nomFichierAudio) {
        $this->titre = $titre;
        $this->nomFichierAudio = $nomFichierAudio;
        $this->duree = 0;
        $this->genre = "";
    }



    public function __toString() : string {
        return json_encode(get_object_vars($this));
    }


    public function __get(string $attr) : mixed {
        if (property_exists($this, $attr)) {
            return $this->$attr;
        }
        else {
            throw new InvalidPropertyNameException("Invalid property name : $attr");
        }
    }

    public function setGenre(string $val) : void {
        $this->genre = $val;
    } 

    public function setDuree(int $val) : void {
        if ($val > 0) {
            $this->duree = $val;
        }
        else {
            throw new InvalidPropertyValueException("Invalid value : number < 0");
        }
    }

}