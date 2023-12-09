<?php
//File php per l'inserimento di un nuovo libro nel database. Nota che l'entit&agrave; compratore é obbligatoria ma non tutti i compratori sono venditori
session_start(); //Avvio della sessione

include("config.php"); //Connessione al database

if ($_SERVER['REQUEST_METHOD'] === 'POST'){ //Se é stata ricevuta una richiesta di POST
    $err=0; //Variabile di errore settata a 0
    $titolo=""; //Stringa titolo, inizialmente vuota
    $autore=""; //Stringa autore, inizialmente vuota
    $descrizione=""; //Stringa descrizione, inizialmente vuota

    //Se i campi ottenuti tramite POST sono vuoti, setta la variabile di errore a -1 altrimenti salva il valore della variabile
    if(empty($_POST['titolo'])){
        $err=-1;
    }
    else $titolo = $_POST['titolo'];
    if(empty($_POST['autore'])){
        $err=-1;
    }
    else $autore = $_POST['autore'];
    if(empty($_POST['descrizione'])){
        $err=-1;
    }
    else $descrizione=$_POST['descrizione'];

    //Controllo per la gestione dell'immagine
    if(!empty($_FILES['file']['tmp_name']) && file_exists($_FILES['file']['tmp_name'])) {
        $image=addslashes(file_get_contents($_FILES['file']['tmp_name']));
    }
    else $err=2; //Se il FILE "file" é vuoto, aggiorna la variabile di errore
    
    $email=$_SESSION['email']; //Ottieni il valore della mail dalla relativa variabile di sessione

    //Query per ottenere l'id del compratore attualmente loggato
    $sql1="SELECT id FROM ricevente where email= '$email'";
    $insert1=$link->query($sql1); //Esecuzione della query
    $row = $insert1->fetch_assoc(); //Salvataggio della riga

    $id=$row['id']; //Salvataggio del campo 'id' della riga precedentemente salvata

    //Se non ci sono stati errori
    if($err==0){
        //Procedo con l'inserimento del libro nella relativa tabella con i campi stabiliti
        $descrizione=mysqli_real_escape_string($link,$descrizione); //Utilizzo della mysqli_real_escape_string per evitare che caratteri speciali terminino la query
        $titolo=mysqli_real_escape_string($link,$titolo);
        $autore=mysqli_real_escape_string($link,$autore);
        $sql = "INSERT INTO libri(titolo, autore, descrizione, copertina, id, idVC) VALUES('$titolo','$autore', '$descrizione', '$image', DEFAULT , '$id')";
        $insert=$link->query($sql); //Esecuzione della query
    
        if(!$insert) { //Se l'inserimento non é andato a buon fine, aggiorna la variabile di errore
            $err=1;
        }
    }

    //Query per ottenere l'id del venditore, attualmente compratore
    $sql="SELECT id FROM offerente WHERE id='$id'";
    $inst=$link->query($sql); //Esecuzione della query
    $row=$inst->fetch_assoc(); //Salvataggio della riga

    //Se la query ha resistito almeno un risultato
    if(empty($row)){
        //Query per l'aggiornamento della tabella offerente inserendo un nuovo offerente che ha appena caricato il libro
        $sql = "INSERT INTO offerente(id) VALUES('$id')";
        $insert=$link->query($sql); //Esecuzione della query

        if(!$insert) { //Se l'inserimento non é andato a buon fine, aggiorna la variabile di errore
            $err=1;
        }
    }
    
    //Controllo sulla variabile di errore e relativo invio del messaggio per la gestione del codice tramite Ajax
    if($err==0) echo "OK"; //Va tutto bene
    else if($err==1) echo "ERROR";//Generico errore
    else if($err=2) echo "IMAGE"; //Immagine mancante
    else echo "EMPTY"; //Campo vuoto
}

$link->close(); //Chiusura della connessione al database
?>