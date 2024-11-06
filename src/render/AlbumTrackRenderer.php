<?php declare (strict_types=1);

namespace iutnc\deefy\render;

use iutnc\deefy\audio\tracks\AlbumTrack;

require_once 'Renderer.php';


class AlbumTrackRenderer implements Renderer {

    // Attributs
    public AlbumTrack $track;



    public function __construct(AlbumTrack $track) {
        $this->track = $track;
    }



    public function render(int $selector = Renderer::COMPACT) : string {
        switch ($selector) {

            case Renderer::COMPACT :
                return "
                    <div id='track'>
                    
                        <p><strong>{$this->track->titre}</strong> <br>
                        <strong>Artiste</strong> - {$this->track->artiste}</p> <br>
                        <audio src='audio/{$this->track->nomFichierAudio}' controls></audio><br>
                        <p>
                            <strong>Album</strong> - {$this->track->album} <br>
                            <strong>Duree</strong> - {$this->track->duree}s
                        </p>

                    </div>";
                break;
            
                
            case Renderer::LONG :
                return "
                    <div id='track'>
                    
                        <p>{$this->track->titre} <br>
                        <strong>Artiste</strong> - {$this->track->artiste}</p> <br>
                        <audio src='audio/{$this->track->nomFichierAudio}' controls></audio><br>
                        <p>
                            <strong>Album</strong> - {$this->track->album} <br>
                            <strong>Annee</strong> - {$this->track->annee} <br>
                            <strong>Numero</strong> - {$this->track->numero} <br>
                            <strong>Genre</strong> - {$this->track->genre} <br>
                            <strong>Duree</strong> - {$this->track->duree}s
                        </p>

                    </div>";
                break;

            default:
                return "AlbumTrackRenderer : Selecteur inconnu";
                break;

        }
    }
}