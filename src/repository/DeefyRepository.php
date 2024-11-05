<?php

namespace iutnc\deefy\repository;

use Exception;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\user\User;
use PDO;
use iutnc\deefy\audio\tracks\AlbumTrack;
use PDOException;

class DeefyRepository {

    private ?PDO $pdo;

    public static ?DeefyRepository $instance = null;
    public static array $config = [];

    private function __construct(array $config){

        $this->pdo = new PDO(
            $config['dsn'],
            $config['username'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]
        );

    }

    public static function setConfig(string $file) : void {

        // Création du tableau avec les paramètres de connection
        $conf = parse_ini_file($file);

        if ($conf === false) {
            throw new Exception("Error reading configuration file");
        }
        self::$config = [
            'dsn'=> "{$conf['driver']}:host={$conf['host']};dbname={$conf['database']}",
            'username'=> $conf['username'],
            'password'=> $conf['password']
        ];
    }

    public static function getInstance() : DeefyRepository {
        if (is_null(self::$instance)) {
            self::$instance = new self(self::$config); // ou new DeefyRepository(self::$config)
        }
        return self::$instance;
    }

    // Méthode de récupèration des playlists dans la base (retourne un tableau de playlists)
    /**
     * @return Playlist[]
     */
    public function findAllPlaylist(): array {
        $playlists = [];

        $sql = 'Select * from playlist';

        $statement = $this->pdo->prepare($sql);
        $statement->execute();

        foreach ($statement->fetchAll() as $row) {
            $playlist = new Playlist(
                $row['nom'],
                []
            );
            $playlists[] = $playlist;
        }

        return $playlists;
    }

    // Vérifie si un utilisateur avec cet e-mail existe déjà
    public function userExists($email) : bool {

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $count = $stmt->fetchColumn();
        return $count > 0;

    }

    // Methode pour se connecter
    public function login($email, $password){

        // Hachage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {

            if (!$this->userExists($email)) {

                // Insertion de l'utilisateur en base de données
                try {

                    $stmt = $this->pdo->prepare("INSERT INTO user (email, passwd, role) VALUES (?, ?, 0)");
                    $stmt->execute([$email, $hashedPassword]);
                    echo 'Enregistrement réussi'; // Enregistrement réussi
                    return true;

                } catch (PDOException $e) {
                    echo "Erreur lors de l'enregistrement : " . $e->getMessage();
                }

            } else {

                // Récupère les informations de l'utilisateur
                $stmt = $this->pdo->prepare("SELECT * FROM user WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Vérifie si l'utilisateur existe et si le mot de passe est correct
                if ($user && password_verify($password, $user['passwd'])) {
                    // Si la vérification est réussie
                    return true;

                }
            }
        }
        return false;
    }

    public function saveEmptyPlaylist(Playlist $playlist) : Playlist {

        $sql = 'INSERT INTO playlist(nom) VALUES(:nom)';

        $statement = $this->pdo->prepare($sql);
        $statement->execute(['nom' => $playlist->nom]);
        $playlist->setId($this->pdo->lastInsertId());

        return $playlist;

    }

    public function savePodcastTrack(PodcastTrack $track) : PodcastTrack {

        $sql = 'INSERT INTO podcast_track(titre, filename, type, auteur_podcast, date_podcast, duree, genre) 
            VALUES(:titre, :filename, "P", :auteur, :date, :duree, :genre)';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'titre' => $track->__get('titre'),
            'filename' => $track->__get('filename'),
            'auteur' => $track->__get('auteur'),
            'date' => $track->__get('date'),
            'duree' => $track->__get('duree'),
            'genre' => $track->__get('genre')
        ]);

        return $track;

    }

    public function saveAlbumTrack(AlbumTrack $track) : AlbumTrack {

        $sql = 'INSERT INTO podcast_track()
            VALUES()';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([

        ]);

        $track->setId($this->pdo->lastInsertId());

        return $track;
    }

    public function addTracktoPlaylist(int $track, int $playlist) : void {

        $sql = 'INSERT INTO playlist2track(id_pl, id_track) VALUES(:id_playlist, :id_track)';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'id_playlist' => $playlist,
            'id_track' => $track
        ]);

    }

    public function getPlaylists(string $email){

        $query ="SELECT p.nom as nom, p.id as idp from user u inner join user2playlist u2 on u.id = u2.id_user
                            inner join playlist p on u2.id_pl = p.id
                            where u.email like ?";
        $prep = $this->pdo->prepare($query);
        $prep->bindParam(1,$email);
        $prep->execute();

        while($data=$prep->fetch(PDO::FETCH_ASSOC)){
            $tab[$data['idp']] = new Playlist($data['nom'], []);
        }

        return $tab;
    }

    public function checkEmail(string $email) : array{

        $sql = "select passwd, role from User where email = ?";

        $prep = $this->pdo->prepare($sql);
        $prep->bindParam(1,$email);
        $prep->execute();

        $data = $prep->fetch(PDO::FETCH_ASSOC);

        if(empty($data)){
            $res = [];
        } else {
            $res = ["role"=>$data['role'], "passwd"=>$data['passwd']];
        }

        return $res;

    }

    public function insertionUser(string $email, string $passwd, string $role) {

        $message = "Utilisateur ajouté";
        echo "<script type='text/javascript'>alert('$message');</script>";
        $sql = "INSERT INTO user (email, passwd, role) values(?,?,?)";
        $prep = $this->pdo->prepare($sql);
        $prep->bindParam(1,$email);
        $prep->bindParam(2,$passwd);
        $prep->bindParam(3,$role);
        $prep->execute();

    }

}