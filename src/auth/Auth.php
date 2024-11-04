<?php

namespace iutnc\deefy\auth;
use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\exception\AuthException;
use PDO;
class Auth{

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
        $_SESSION['user']['email']=$e;
        $_SESSION['user']['role']=$data['role'];
        return true;
    }
    
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
        }
        return $res;
    }
    public static function checkAccess(int $id):bool{
        $res=false;
        
        $bd = ConnectionFactory::makeConnection();
        $query = "SELECT u.email as email from user u inner join user2playlist p on u.id = p.id_user where id_pl = ? ";
        $prep = $bd->prepare($query);
        $prep->bindParam(1,$id);
        $bool = $prep->execute();
        $d = $prep->fetchall(PDO::FETCH_ASSOC);
        if($bool && sizeof($d)>0){
            if($d[0]['email'] === $_SESSION['user']['id']||$_SESSION['user']['role']===100){
                $res=true;
            }
        }
        return $res;
    }

}
