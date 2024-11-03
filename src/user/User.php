<?php
namespace iutnc\deefy\user;
use PDO;
use iutnc\deefy\audio\lists\Playlist as Playlist;
use iutnc\deefy\repository\DeefyRepository;
class User{
    private string $email;
    private string $password;
    private int $role;

    public function __construct(string $e, string $p, int $r){
        $this->email = $e;
        $this->password = $p;
        $this->role = $r;
    }

    public function getPlaylists(){
        $bd = DeefyRepository::getInstance();

        $query ="SELECT p.nom as nom, p.id as idp from user u inner join user2playlist u2 on u.id = u2.id_user
                            inner join playlist p on u2.id_pl = p.id
                            where u.email like ?";
        $prep = $bd->prepare($query);
        $prep->bindParam(1,$this->email);
        $prep->execute();

        $tab=[];
        while($data=$prep->fetch(PDO::FETCH_ASSOC)){
            $tab[$data['idp']] = new Playlist($data['nom'], []);
        }

        return $tab;
    }

}