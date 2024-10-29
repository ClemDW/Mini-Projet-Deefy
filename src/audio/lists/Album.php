<?php declare (strict_types=1);

namespace iutnc\deefy\audio\lists;

class Album extends AudioList {

    // Attributs
    private string $artiste;
    private string $dateSortie;



    public function __construct(string $nom, array $list) {
        parent::__construct($nom, $list);
    }



    public function setArtiste(string $val) : void {
        $this->artiste = $val;
    }

    public function setDateSortie(string $val) : void {
        $this->dateSortie = $val;
    }

}