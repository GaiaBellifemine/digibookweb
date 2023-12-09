<?php

session_start(); //Avvio l'inizio della sessione per poter accedere alle variabili di sessione

include("php/config.php"); //Necessario per la connessione al db

$cerca = $_POST['cerca']; //Dato acquisito tramite POST da homepage.php, riga 77
$email=$_SESSION['email']; //email utente attualmente loggato, ottenuta tramite variabile di sessione inizializzata in php/login.php, riga 53


/* 
    Query di ricerca nel database, restituisce una vista sui dati da visualizzare nelle card dei libri.
    Nella clausola where verifica che l'email sia dell'utente corretto (che ha venduto il libro) e che la parola chiave di ricerca sia quella inserita.
    Nella clausola select sono inseriti anche gli id per permettere di inviarli tramite richiesta GET ai file php email ed insert
*/
$sql = "SELECT libri.titolo, libri.copertina, libri.id AS lid, ricevente.id, libri.idVC 
        FROM libri, ricevente
        WHERE ricevente.email='$email' AND libri.titolo='$cerca'";
$result = $link->query($sql); //Contenuto del risultato dell'esecuzione query
$tabella = " "; //Inizialmente la tabella che mostra i libri cercati é; vuota

$i=0; //Indice che assegna un id univoco a ciascun anchor <a href> della tabella, inizialmente posto a 0

while($row = $result->fetch_assoc()) //Si scorre ciascuna riga di result
{
    /*Si concatenano gli elementi in tabella da mostrare*/
    $tabella.= "
            <div class='cards'>
                <div class='image'>
                    <img src='data:image/gif;base64,".base64_encode($row['copertina'])."'/> <!--Visualizza l'immagine (blob) del libro codificata in base64-->
                </div>
                <div class='title'>
                    <h1>".$row['titolo']."</h1>
                </div>
                <div class='wish'>
                    <!--Ciascun anchor ha un id univoco, il primo tag si riferisce a WishList, il secondo alla richiesta mail-->
                    <!--Vengono assegnati anche dei parametri personalizzati per permettere la richiesta asincrona ajax-->
                    <a id='a$i' titolo='".$row['titolo']."' href='' onclick='return false;'><button class='wh'>Wishlist</button></a>
                    <a id='link$i' lid='".$row['lid']."' vid='".$row['idVC']."' titolo='".$row['titolo']."' href='' onclick='return false;'><button class='wh'>Richiedi</button></a>
                </div>
            </div>
        ";
    $i++; //Aggiorno gli id dei tag
}

$link->close(); //CHiusura della connessione al db

?>

<!DOCTYPE html> <!--Informazione sul tipo di documento per il browser-->
<html>
    <head>
        <meta name="viewport" content="with=device-width, initial=scale=1.0"> <!--Adatta le dimensioni all'area di visualizzazione-->
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> <!--Perché la pagina é; caricata con HTTPS ma la richiesta Ajax é; considerata non sicura-->
        <title>DigiBook</title> <!--Titolo della pagina mostrata-->
        <link rel="stylesheet" href="css/homepage.css"> <!--Collegamento al css-->
        <link rel="preconnect" href="https://fonts.gstatic.com"> <!--Collegamento ai font da utilizzare-->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet"> <!--Font utilizzato-->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"/> <!--Contenuto icone font-awesome da cdn (accesso link)--> 
        <link rel="stylesheet" 
            href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" 
            integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" 
            crossorigin="anonymous"> <!--Directory cloud dove sono presenti i font-->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> <!--Preparazione all'utilizzo di ajax-->

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
    </head>
    <body>
        <section class="header"> <!--Header della pagina: Banner, barra navigazione, barra di ricerca-->
            <nav>
                <a href="homepage.php"><img src="images/banner.png"></a> <!--Logo digibook con reference a homepage.php-->
                <div class="nav-links" id="navLinks"> <!--Div per i link della barra di navigazione-->
                    <i class="fa fa-times" id="hide"></i> <!--Icona per nascondere il menu laterale (solo in modalit&agrave; responsive)-->
                    <ul> <!--Lista link navbar-->
                        <li><a href="homepage.php">HOME</a></li>
                        <li><a href="aboutus.php">ABOUT US</a></li>
                        <li><a href="profile1.php">PROFILO</a></li>
                        <li><a href="php/logout.php">LOGOUT</a></li>
                    </ul>
                </div>
                <i class="fa fa-bars" id="show"></i> <!--Icona per mostrare il menu laterale (solo in modalit&agrave; responsive)-->
            </nav>

            <div id="search-10" class="widget_search"> <!--Div per la barra di ricerca-->
                <form class="cercalibro" action="cercalibro.php" method="post"> <!--Form per l'invio dei dati tramite POST alla pagina cercalibro.php-->
                    <div style="text-align: right;"> <!--div posizionato a destra-->
                        <p style="position: absolute; bottom: 0; left: 0; width: 98%;"> 
                            <input type="text" placeholder="Cerca un libro.." name="cerca" size="40">  <!--Barra di ricerca con suggerimento (placeholder)-->  
                            <button type="submit" class="search"><i class="fas fa-search"></i></button> <!--Tasto per inviare i dati tramite form-->
                        </p>  
                    </div>
                </form>
            </div>

        </section>

        <!--Contentenuto principale-->
        <section class="books">
            <h1>Elenco libri</h1>
            <p>Risultato della tua ricerca</p>
            
            <div id="main" class="main">
                <?php echo $tabella; ?> <!--Utilizzo il php per mostrare la tabella che possiede il contenuto html-->
            </div>
            
        </section>

        <!--Javascript per il menu laterale-->
        <script type="text/javascript">
            document.getElementById("show").onclick=function(event){ //Verifica se é; stato cliccato l'elemento con id "show"
                document.getElementById('navLinks').style.right="0px"; //Se é; stato cliccato show, mostra il menu laterale portandolo dalla posizione -200px a 0px
            }
            document.getElementById("hide").onclick=function(event){
                document.getElementById('navLinks').style.right="-200px"; //Se invece é; stato cliccato hide riporta il menu da 0px a -200px nascosto
            }

            var container_div=document.getElementById("main"); //Main container
            var divCount=container_div.getElementsByTagName("div").length; //Counter di tutti i div del main container

            for(var i=0;i<divCount;i++){ //Per ciascun div
                $('#link' + i).on('click', {'idx': i}, function(e) { //Se é; stato cliccato sul link con indice i (richiesta mail)
                    /*
                        Ottengo gli attributi personalizzati essenziali per la richiesta ajax
                        lid: codice libro
                        vid: codice offerente
                        title: titolo libro
                    */
                    var lid = $(this).attr('lid');
                    var vid = $(this).attr('vid');
                    var title= $(this).attr('titolo');

                    $.ajax({
                        type: "get", //Tipo di richiesta ajax: GET per l'invio dei dati tramite URL
                        url: "php/email.php?idVC="+vid+"&id="+lid+"&title="+title, //Indirizzo pagina php che esegue il controllo con invio dei dati da processare
                        /*Dati da processare*/
                        data: {
                            idVC: vid,
                            id: lid,
                            title: title
                        },
                        cache: false, //Disabilita la memorizzazione temporanea di questa pagina per ottimizzare la richiesta ajax
                        success: function(data){ //Se la richiesta ajax va a buon fine
                            if(data=="OK") alert("Una email é; stata inviata al destinatario.\nControlla la tua posta per metterti in contatto."); //Se il file php ha resitutito echo OK emette alert positivo
                            else if(data=="ERROR") alert("Abbiamo riscontrato un problema. Riprova per favore"); //Se il file php ha resitutito echo ERROR emette alert negativo
                            else alert("C'é; un problema con l'invio della mail. Riprova più tardi"); //Altrimenti emette alert di generico errore
                        },
                        error: function(){ //Se la richiesta ajax non pu&ograve; essere effettuata
                            alert("Qualcosa non va"); //Avvisa utente con un alert
                        }
                    });
                });


                $('#a' + i).on('click', {'idx': i}, function(e) { //Se é; stato cliccato sul link con indice i (wishlist)
                    var title= $(this).attr('titolo'); //Ottengo il titolo da attributo personalizzato

                    $.ajax({
                        type: "get", //Tipo di richiesta ajax: GET per l'invio dei dati tramite URL
                        url: "php/insert.php?title="+title, //Indirizzo pagina php che esegue il controllo con invio dei dati da processare
                        /*Dati da processare*/
                        data: {
                            title: title
                        },
                        /* Spiegazione analoga da riga 138 */
                        cache: false,
                        success: function(data){
                            if(data=="OK") alert(title+" é; stato aggiunto alla wishlist.\nPer verificare, vai sul tuo profilo.");
                            else if(data=="ERROR") alert("L'elemento "+title+" potrebbe essere gi&agrave; presente in wishlist.\nSe cos&igrave; non fosse, contatta l'amministratore per risolvere.");
                            else alert("Errore");
                        },
                        error: function(){
                            alert("Qualcosa non va");
                        }
                    });
                });
            }
        </script>


    </body>
</html>