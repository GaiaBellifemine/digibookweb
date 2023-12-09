<?php

    //per inserire un libro in wishlist

    session_start(); //avvio della sessione
    
    require_once "config.php"; // avvio connessione al database
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET'){ // se é; stata ricevuta una richiesta di GET, allora:
        $titolo=""; // si inizializza titolo
        $email=$_SESSION['email']; // si ricava la mail dalla variabile di sessione
        $err=0; // si pone la variabile per l'errore a zero

        /*se il GET non é; avvenuto correttamente per tutti i campi, allora la variabile err viene settata ad 1;
        altrimenti ricava la variabile dal GET*/ 
        if(!isset($_GET['title'])){
            $err=1;
        }
        else $titolo=$_GET['title'];

        // query per inserire il libro cliccato dall'utente dentro la sua wishlist
        $sql="INSERT INTO wishlist(idRicevente, idLibri)
                SELECT ricevente.id, libri.id FROM libri, ricevente
                WHERE libri.titolo='$titolo' AND ricevente.email='$email'";
        $insert=$link->query($sql); // salvataggio risultato query

        if(!$insert) $err=1; // se la query non é; andata a buon fine, err viene settato ad 1

        // i messaggi inviati vengono processati da AJAX
        if($err==0) echo "OK"; // se non ci sono stati errori (err=0) allora si invia un messaggio di "OK"
        else echo "ERROR"; //... altrimenti si invia un generico "ERROR"
    }

    mysqli_close($link); //chiusura connessione al database
?>