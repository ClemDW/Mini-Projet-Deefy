<?php

namespace iutnc\deefy\auth;
use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\exception\AuthException;
use iutnc\deefy\repository\DeefyRepository;
use PDO;
class Auth {

    public static function authenticate(string $e, string $p) : bool{
        $d = DeefyRepository::getInstance();

        // regarde si l'email existe
        $data = $d->checkEmail($e);

        //si pas présent -> false
        if (empty($data)){
            echo '<p>Compte inexistant. Veuillez vous inscrire</p>';
            return false;
        }

        // check password ->exception
        if (!password_verify($p, $data['passwd'])) throw new AuthException("Mot de passe Incorrect");
        $_SESSION['user']['email']=$e;
        $_SESSION['user']['role']=$data['role'];

        echo '<p>Vous êtes connecté !</p>';

        return true;

    }

    public static function register(string $e, string $p) : bool {

        $d = DeefyRepository::getInstance();
        $res = false;
        // vérif si l'email existe dans la bd
        if($d->userExists($e)){
            echo '<p>Compte existant. Veuillez vous connecter</p>';
            return false;
        }

        // sinon
        else {
            //on hash le passwd
            $hash = password_hash($p, PASSWORD_DEFAULT);
            //on insère
            $d->insertionUser($e, $hash, 1);
            echo '<p>Compte crée</p>';
        }

        return $res;
    }

}