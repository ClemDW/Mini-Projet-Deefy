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

<<<<<<< HEAD
}
=======
    public function getPlaylists(){
        $bd = DeefyRepository::getInstance();
        $bd->getUserPlaylist($this->email, $this->$role); 
    }

}
>>>>>>> 281225a5f3c594494fb378b5390ccf638107bbad
