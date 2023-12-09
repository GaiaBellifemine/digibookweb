<?php

session_start(); //Avvio della sessione per l'accesso alle variabili di sessione

require_once('php/config.php'); //Include una sola volta il file config.php per la connessione al db

$email=$_SESSION['email']; //Prelevo l'email dalla variabile di sessione relativa


/* Prima sql per ottenere id ricevente associato alla email dal database */
$sql0="SELECT ricevente.id AS id FROM ricevente WHERE ricevente.email='$email'";
$result=$link->query($sql0);
$row = mysqli_fetch_array($result);
$cid=$row['id']; //Salvataggio della riga id dela database nella variabile locale cid

/* 
    Seconda sql per ottenere i dati da inserire in tabella
    é; incluso anche l'id del libro lid per inviarlo tramite get al file php
*/
$sql2="SELECT libri.titolo, libri.autore, libri.descrizione, ricevente.username, libri.id AS lid
    FROM ricevente INNER JOIN offerente ON ricevente.id=offerente.id INNER JOIN libri ON offerente.id=libri.idVC INNER JOIN wishlist ON libri.id=wishlist.idLibri
    WHERE wishlist.idRicevente='$cid'
    LIMIT 9"; //LIMIT 9 per visualizzare solo 9 righe

$result = $link->query($sql2); //Esecuzione della seconda query
$tabella = " "; //Inizialmente la tabella é; vuota
$i=0; //Id univoco da associare a ciascun anchor <a href>
while($row = $result->fetch_assoc()) //Scorro tutte le righe del risultato dell'esecuzione della seconda query
{
    /* Concatenazione della tabella */
    $tabella.= "
        <form method='GET'>
        <tr>
            <th>".$row['titolo']."</th>
            <th>".$row['autore']."</th>
            <th>".$row['username']."</th>
            <!-- Campo tabella contenente anchor con attributi personalizzati necessari per l'esecuzione Ajax. onclick return false per non refreshare la pagina al click -->
            <th><a id='link$i' cid='".$cid."' lid='".$row['lid']."' href='' onclick='return false;'>Rimuovi</a></th>
        </tr>
        </form>";
        $i++; //Aggiornamento dell'indice univoco
}

$link->close(); //Chiusura della connessione al db

?>

<!DOCTYPE html> <!-- Specifico tipo di documento al browser -->
<html>
 	
    <head>
		<link rel="stylesheet" type="text/css" href="css/profile.css">  <!-- Link al css -->
 		<meta charset="UTF-8"> <!-- Tipo di codifica set di caratteri -->
 		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> <!-- Necessario per l'utilizzo di Ajax-->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">

        <!-- Inserimento della favicon per i vari dispositivi -->
        <link rel="apple-touch-icon" sizes="57x57" href="images/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="images/favicon//apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="images/favicon//apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="images/favicon//apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="images/favicon//apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="images/favicon//apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="images/favicon//apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="images/favicon//apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="images/favicon//apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="images/favicon//android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="images/favicon//favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="images/favicon//favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="images/favicon//favicon-16x16.png">
        <link rel="manifest" href="images/favicon//manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="images/favicon//ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

         <title>DigiBook</title> <!--Titolo della pagina-->
 	</head>

 	<body>
 		<div class="wrapper"> <!--Main div-->
 		 	<div class="box" style="overflow-y:scroll; overflow-x:scroll;"> <!--Div con barra verticale per scorrere-->
 		 		<div class="box-img"> <!--Div contenente l'immagine-->
 		 	 		<img src="prova.jpeg">
                    <form action="share.html">
                        <button type="submit" class="share">Condividi libro</button> <!-- Pulsante per la condivisione libro-->
                    </form>
 		 		</div>
 		 	    <div class="box-text">
                    <div class="nav">
                        <a class="active" href="homepage.php">HOMEPAGE</a> <!--Collegamento ad homepage.php-->
                    </div>
 				    <h1>Hai fatto l'accesso come: <?php echo $_SESSION["email"]; ?> </h1> <!--Utilizzo del php per ottenere l'email con cui si é; loggati-->
                    <h2>Questa é; la tua lista desideri</h2>
                    <table id="tID"> <!--Tabella wishlist-->
                        <thead>
                            <tr> <!-- Campi della tabella -->
                                <th><h2>Titolo</h2></th> 
                                <th><h2>Autore</h2></th> 
                                <th><h2>offerente</h2></th> 
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $tabella; ?> <!--Utilizzo del php per mostrare la tabella-->
                        </tbody>
                    </table>
                    <a href="wishlist.php">Vedi altri</a> <!--Collegamento a wishlist.php per la visualizzazione di ulteriori risultati-->
 		        </div>
 		    </div>
 		</div>


         <script type="text/javascript">

        var rowCount=document.getElementById("tID").rows.length; //Numero di righe associate alla tabella con id tID
    
        for(var i = 0; i < rowCount; i++){ //Per ciascun elemento della tabella
            $('#link' + i).on('click', {'idx': i}, function(e) { //Se é; stato cliccato il link con indice i
                    /* Ottengo i dati dagli attributi personalizzati dal link con indice i */
                    var cid= $(this).attr('cid');
                    var lid= $(this).attr('lid');
                    
                    $.ajax({
                        type: "get", //Specifico tipo di connessione GET: invio dei dati tramite url
                        url: "php/delete.php?cid="+cid+"&lid="+lid, //Specifico url con i dati da processare in background
                        /*Dati da processare tramite ajax*/
                        data: {
                            cid: cid,
                            lid: lid
                        },
                        cache: false, //Disabilita la memorizzazione temporanea di questa pagina per ottimizzare la richiesta ajax
                        success: function(data){ //Se la richiesta ajax é; andata a buon fine, inizia una funzione con i dati ottenuti
                            if(data=="OK") { //Se il php ha restituito OK
                                alert("Elemento rimosso dalla tua wishlist."); //Avviso l'utente della conferma eliminazione
                                window.location.reload(); //Aggiorna la pagina per aggiornare anche la tabella
                            }
                            else if(data=="ERROR") alert("Impossibile rimuovere l'elemento"); //Altrimenti se il php ha restituito ERROR, avvisa l'utente dell'errore
                            else alert(data); //Altrimenti generico errore
                        },
                        error: function(){
                            alert("Qualcosa non va"); //Generico errore
                        }
                    });
                });
            };
    </script>
    </body>    

</html>
 

     