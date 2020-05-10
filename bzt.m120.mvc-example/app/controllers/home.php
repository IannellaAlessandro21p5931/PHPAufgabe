<?php

class Home extends Controller {

    protected $user;
    public function __construct() {
        $this->user = $this->model('User');
    }

    public function index($name = '') {
        
        $user = $this->user;
        $user->name = $name;
        $this->view('home/index', ['name' => $user->name]);

        $this->goto('home', 'newsletter');
    }


    
    public function newsletter(){
    //schauen ob ein post vorhanden 
    if(isset($_POST['mail'])){
        $file = fopen('list.txt', 'a+');

        //schauen ob mail schon registriert
        $write = true;
        while(!feof($file)){
            if(trim(fgets($file)) == $_POST['mail']) {
                $write = false;
            }
        }

        // fehler meldung oder mail in file schreiben
        if($write == true){
            fwrite($file, "E-Mail: ".$_POST['mail']."\n");
            fwrite($file, "Vorname: ".$_POST['vorname']."\n");
            fwrite($file, "Nachname: ".$_POST['nachname']."\n");
            fwrite($file, "Strasse: ".$_POST['strasse']."\n");
            fwrite($file, "Ort: ".$_POST['ort']."\n");
            echo $_POST['mail'].' wurde für Sie erfasst!';
        }else{
            echo $_POST['mail'].' wurde bereits erfasst!';
        }

    }

    $this->view('home/newsletter');
    }
}