<?php declare (strict_types=1);

namespace iutnc\deefy\render;

use iutnc\deefy\audio\lists\AudioList;


class AudioListRenderer implements Renderer {

    // Attributs
    public AudioList $audioList;



    public function __construct(AudioList $audioList) {
        $this->audioList = $audioList;
    }



    public function render(int $selector = Renderer::COMPACT) : string {
        $aff = "<html>\n\n\t<strong>{$this->audioList->nom}</strong> <br>";
        foreach ($this->audioList->listAudioTrack as $index => $val) {
            if (get_class($val) === 'iutnc\deefy\audio\tracks\AlbumTrack') {
                $albumTrackRenderer = new AlbumTrackRenderer($val);
                $aff = $aff . $albumTrackRenderer->render(1) . " <br>";
            }
            else if (get_class($val) === 'iutnc\deefy\audio\tracks\PodcastTrack') {
                $podcastTrackRenderer = new PodcastTrackRenderer($val);
                $aff = $aff . $podcastTrackRenderer->render(1) . " <br>";
            }
        }
        return $aff . "\n\n</html>";
    }
}