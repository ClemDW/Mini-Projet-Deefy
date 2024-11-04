<?php
namespace iutnc\deefy\action;
class AddUserAction extends Action {
    
    public function __construct(){
        parent::__construct();
    }

    public function execute() : string{
        $res = "";
        if($this->http_method == "GET"){
            $res = '<form method="post" action="?action=add-user">
                <input type="email" name="email" placeholder="email" autofocus>
                <input type="text" name="passwd1" placeholder="password 1">
                <input type="text" name="passwd2" placeholder="password 2">
                <input type="submit" name="connex" value="Inscription">
                </form>';
        }else{
            $e = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $p1= $_POST['passwd1'];
            $p2 = $_POST['passwd2'];
            if($p1 === $p2){
                $res = "<p>".\iutnc\deefy\auth\Auth::register($e, $p1)."</p>";
            }else{
                $res = '<p>Mot de passe 1 et 2 différents</p>
                <form method="post" action="?action=add-user">
                <input type="email" name="email" placeholder="email" autofocus>
                <input type="text" name="passwd1" placeholder="password 1">
                <input type="text" name="passwd2" placeholder="password 2">
                <input type="submit" name="connex" value="Inscription">
                </form>';
            }
        }
        return $res;
    }
}