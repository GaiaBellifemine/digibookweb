<?php
    //questa pagina serve per eliminare un libro dalla wishlist

    session_start();
    
    require_once "config.php"; // per la connessione al database
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET'){ // se é; stata ricevuta una richiesta di GET, allora:
        $email=$_SESSION['email']; // ...viene ricavata l'email
        $err=0; // variabile per il controllo sugli errori
        
        /*se il GET non é; avvenuto correttamente per tutti i campi, allora la variabile err viene settata ad 1;
        altrimenti ricava la variabile dal GET*/ 
        if(!isset($_GET['cid'])){ 
            $err=1;
        }
        else $cid=$_GET['cid'];

        if(!isset($_GET['lid'])){
            $err=1;
        }
        else $lid=$_GET['lid']; 

        // cancella dalla wishlist il libro che ha idCompratore e idLibri coincidenti con quelli estrapolati dalla GET
        // in questo modo si individuano il compratore corretto e il libro corretto da eliminare
        $sql="DELETE FROM wishlist
                WHERE idRicevente='$cid' AND idLibri='$lid'";
        $insert=$link->query($sql); // risultato della query 

        if(!$insert) $err=1; // se la query non va a buon fine, la variabile di errore viene settata ad 1
        
        // i messaggi inviati vengono processati da AJAX
        if($err==0) echo "OK"; // se non ci sono stati errori (err=0) allora si invia un messaggio di "OK"
        else if($err==1) echo "ERROR"; //...altrimenti, se ci sono stati errori (err=1), si invia un messaggio di "ERROR"
        else echo "ERRORE";//... altrimenti si invia un generico "ERRORE"
    }

    mysqli_close($link); // chiusura connessione al database
?>