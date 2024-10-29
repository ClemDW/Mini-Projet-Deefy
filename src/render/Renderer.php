<?php declare (strict_types=1);

namespace iutnc\deefy\render;

interface Renderer {

    // Attributs
    const COMPACT = 1;
    const LONG = 2;

    public function render(int $selector = Renderer::COMPACT) : string;


}