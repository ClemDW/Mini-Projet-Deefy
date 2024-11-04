<?php

namespace iutnc\deefy\auth;
use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\exception\AuthException;
use iutnc\deefy\repository\DeefyRepository;
use PDO;
class Auth {

<<<<<<< HEAD
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
=======
    public static function authenticate(string $e, string $p):bool{
        $bd = ConnectionFactory::makeConnection(); // TODO method getInfo(email) : hash(psw), role qui return liste vide si inexistant
        // tout mettre reposi
        $query = "select passwd, role from User where email = ? ";
        $prep = $bd->PDO->prepare($query);
        $prep->bindParam(1,$e);
        $bool = $prep->execute();
        $data =$prep->fetch(PDO::FETCH_ASSOC);
        $hash=$data['passwd'];
        if (!password_verify($p, $hash)&&$bool)throw new AuthException("Mot de passe Incorrect");
>>>>>>> 281225a5f3c594494fb378b5390ccf638107bbad
        $_SESSION['user']['email']=$e;
        $_SESSION['user']['role']=$data['role'];

        echo '<p>Vous êtes connecté !</p>';

        return true;

    }
<<<<<<< HEAD

    public static function register(string $e, string $p) : bool {

        $d = DeefyRepository::getInstance();
        $res = false;
        // vérif si l'email existe dans la bd
        if($d->userExists($e)){
            echo '<p>Compte existant. Veuillez vous connecter</p>';
            return false;
=======
    
    public static function register(string $e, string $p):String{
        $res = "Echec inscription";
        $minimumLength = 10;

        //verification compte
        $bd = ConnectionFactory::makeConnection();
        $query = "select passwd, role from User where email = ? ";
        $prep = $bd->prepare($query);
        $prep->bindParam(1,$e);
        $prep->execute();
        $d = $prep->fetchall(PDO::FETCH_ASSOC);
        if((strlen($p) >= $minimumLength)&&(sizeof($d)==0)){
            //hash the password
            $hash = password_hash($p, PASSWORD_DEFAULT,['cost'=>10]);
            
            //prepare the insert
            $insert = "INSERT into user (email, passwd) values(?,?)";
            $prep = $bd->prepare($insert);
            $prep->bindParam(1,$e);
            $prep->bindParam(2,$hash);
            $bool = $prep->execute();
            if($bool){
                $res = "inscription Reussite";
            }
>>>>>>> 281225a5f3c594494fb378b5390ccf638107bbad
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
