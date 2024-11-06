<?php declare (strict_types=1);

namespace iutnc\deefy\render;

use Exception;
use iutnc\deefy\audio\tracks\PodcastTrack;

require_once 'Renderer.php';


class PodcastTrackRenderer implements Renderer {

    // Attributs
    public PodcastTrack $track;



    public function __construct(PodcastTrack $track) {
        $this->track = $track;
    }



    public function render(int $selector = Renderer::COMPACT) : string {
        switch ($selector) {

            case Renderer::COMPACT :
                return "
                    <div id='track'>
                    
                        <p><strong>{$this->track->titre}</strong> <br>
                        <strong>Auteur</strong> - {$this->track->auteur}</p> <br>
                        <audio src='audio/{$this->track->nomFichierAudio}' controls></audio><br>
                        <p>
                            <strong>Date</strong> - {$this->track->date}
                        </p>

                    </div>
                ";
                break;
            
                
            case Renderer::LONG :
                return "
                    <div id='track'>
                    
                        <p><strong>{$this->track->titre}</strong> <br>
                        <strong>Auteur</strong> - {$this->track->auteur}</p> <br>
                        <audio src='audio/{$this->track->nomFichierAudio}' controls></audio><br>
                        <p>
                            <strong>Genre</strong> - {$this->track->genre} <br>
                            <strong>Duree</strong> - {$this->track->duree} <br>
                            <strong>Date</strong> - {$this->track->date}
                        </p>

                    </div>";
                break;

            default:
                return "PodcastTrackRenderer : Selecteur inconnu";
                break;

        }
    }
}