<?php
	//questo file serve per l'invio di una mail al venditore (in locale)
    session_start();// inizio sessione 
    
    require_once "config.php";  // connessione al database

    if ($_SERVER['REQUEST_METHOD'] === 'GET'){ // se &egrave; avvenuta una richiesta di GET:
        $err=0; //...la variabile per la gestione degli errori err &egrave; settata a 0
        $idVC=-100; //...si setta il codice del venditore a -100 perché &egrave; un valore inesistente sul database
        $id=-100; //...si setta il codice del libro a -100 perché &egrave; un valore inesistente sul database
		
        // se tutte le richieste di GET non vanno a buon fine, allora la variabile err si setta ad 1
        // altrimenti si salva il valore ottenuto dalla GET in delle variabili
        if(!isset($_GET['idVC'])){
            $err=1;
        }
        else $idVC=$_GET['idVC'];

        if(!isset($_GET['id'])){
            $err=1;
        }
        else $id=$_GET['id'];

        if(!isset($_GET['title'])){
            $err=1;
        }
        else $titolo=$_GET['title'];

        if($err==0){ // se non ci sono stati errori viene eseguito il seguente codice
            // si ricava l'email del venditore (a cui si vuole inviare una mail) che ha venduto il libro cliccato
            $sql="SELECT ricevente.email AS mail
            FROM libri INNER JOIN offerente ON libri.idVC=offerente.id INNER JOIN ricevente ON offerente.id=ricevente.id
            WHERE libri.idVC='$idVC' AND libri.id='$id'";
            $insert=$link->query($sql); // in insert si salva il risultato della query

            if(!$insert) $err=1; // se la query non va a buon fine l'errore si setta ad 1

            $row=$insert->fetch_assoc(); // si usa fetch_assoc perché il risultato della query sar&agrave; unico
			
            // Costruzione del corpo della mail:
            $to=$row['mail']; //destinatario della mail (quindi mail del venditore)
            $oggetto="Richiesta libro: ".$titolo; // oggetto della mail
            $msg="Ciao, vorrei delle informazioni riguardo ".$titolo.". \nAspetto sue notizie, cordiali saluti."; // contenuto della mail
            $intestazioni .= "From: {$_SESSION['email']} <{$_SESSION['email']}>\r\n";  //mittente della mail 
            
            if(!mail($to, $oggetto, $msg, $intestazioni)) $err=1; //Invio e verifica della mail
        }
        
		// invio dati da processare per AJAX
        if($err==0) echo "OK"; // se non ci sono errori err=0 e si invia "OK"
        else if($err==1) echo "ERROR"; //...altrimenti l'err &egrave; 1 e si invia "ERROR"
        else echo "Problema";//echo $mail->ErrorInfo; //...se c'&egrave; un errore generico si invia "Problema"
    }

    mysqli_close($link); // chiusura connessione al database
?>