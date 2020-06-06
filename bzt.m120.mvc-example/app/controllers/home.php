 
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

        $this->goto('home', 'abmelden');
    }


    public function replacemail($oldmail, $newmail) {
        
        // read the entire file
        $str = file_get_contents('newsletter-list.txt');
        
        // replace old mail with new mail
        $str=str_replace($oldmail, $newmail, $str);
        
        // rewrite the file
        file_put_contents('newsletter-list.txt', $str);

        // go back to newsletter
        $this->goto('home', 'newsletter');
    }

    
    public function newsletter() {

        // check if form was sent
        if(isset($_POST['mail'])){
            $file = fopen('newsletter-list.txt', 'a+');

            // loop all lines and compare with form inputs
            $write = true;
            while(!feof($file)){

                // split line in parts
                $l = explode(';', fgets($file));

                // check if line is identical
                if($l[0] == $_POST['mail'] && $l[1] == $_POST['vorname'] && $l[2] == $_POST['nachname'] && $l[3] == $_POST['strasse'] && trim($l[4]) == $_POST['ort']){
                    $write = false; // dont write again
                    echo 'Diese Person wurde bereits erfasst!';
                    break;
                } 
                // check if just mail is different
                elseif($l[1] == $_POST['vorname'] && $l[2] == $_POST['nachname'] && $l[3] == $_POST['strasse'] && trim($l[4]) == $_POST['ort']) {
                    $write = false; // ask if mail should be replaced
                    echo '
                    Die gleiche Adresse mit einer anderen mail ist bereits vorhanden. Möchten Sie die Mail ersetzen?
                    <br>
                    <a href="/PHPAufgabe/bzt.m120.mvc-example/public/home/replacmail/'.$l[0].'/'.$_POST['mail'].'">Ja, überschreiben</a>';
                    break;
                }
            }

            // write new line
            if($write == 23){
                fwrite($file, $_POST['mail'].';');
                fwrite($file, $_POST['vorname'].";");
                fwrite($file, $_POST['nachname'].";");
                fwrite($file, $_POST['strasse'].";");
                fwrite($file, $_POST['ort']."\n");
                echo $_POST['mail'].' wurde registiert';
            }
        }

        // render view
        $this->view('home/newsletter');
    }

    public function delmail($oldmail) {
        $contents = file_get_contents('newsletter-list.txt');
        $contents = str_replace($oldmail, '', $contents);
        file_put_contents('newsletter-list.txt', $contents);
    }

    public function abmelden() {
        // check if form was sent
        if(isset($_POST['mail'])){
            $file = fopen('newsletter-list.txt', 'a+');

            // loop all lines and compare with form inputs
            $write = true;
            while(!feof($file)){

                // split line in parts
                $l = explode(';', fgets($file));

                // check if line is identical
                // e-mail aus txt file "löschen"
                if($l[0] == $_POST['mail']){
                    $write = false;
                    echo '
                    Wirklich abmelden?
                    <br>
                    <a href="/PHPAufgabe/bzt.m120.mvc-example/public/home/delmail/'.$l[0].'/'.$_POST['mail'].'">Ja, abmelden</a>';
                    break;
                }
                elseif($l[0] != $_POST['mail']){
                    $this->goto('home', 'newsletter');
                    echo 'Diese Mail ist noch nicht angemeldet';
                    /*echo '
                    Diese Mail ist noch nicht angemeldet
                    <br>
                    <a href="/PHPAufgabe/bzt.m120.mvc-example/public/home/newsletter/'.$l[0].'/'.$_POST['mail'].'">Weiterleiten</a>';*/
                }
            }
        }
        // render view
        $this->view('home/abmelden');
    }
}

