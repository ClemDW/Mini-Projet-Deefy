<?php

namespace iutnc\deefy\action;

use \iutnc\deefy\action\Action;

class DefaultAction extends Action {

    public function execute() : string{

        $res = "Page d'accueil";

        return $res;

    }

}