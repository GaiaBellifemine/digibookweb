<!--
	--------------LA STRUTTURA DI QUESTA PAGINA HA STRUTTURA IDENTICA A INDEX.PHP, PER ULTERIORI DETTAGLI APRIRE index.php--------------------
-->

<?php
	session_start(); //Avvio la sessione per accedere alle variabili

	if(isset($_SESSION["loggedin"])){ //Se l'utente é; gi&agrave; loggato
		header("Location: homepage.php"); //Reindirizza la pagina index.php a homepage.php
	}
?>

<!doctype html> <!-- Specifica il tipo di documento al browser -->
<html>
<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script> 

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

	<title>DigiBook</title> 
</head>

<body>
	<div class="split-screen">
		<div class="left">
			<section class="copy">
				<h1>DigiBook</h1>
				<p>La tua libreria personale...a portata di click</p>
			</section>
		</div>
		<div class="right">
			<form id="login" method="post">
				<section class="copy">
				<h2>Accedi</h2>
				<p>Non sei ancora registrato? </p>
				<a href="index.php"><strong>Registrati</strong></a> <!--Link per il collegamento alla registrazione se l'utente none é; in possesso di un account-->
				</section>
				<div class="input-container email">
					<label for="email">Email</label>
					<input id="email" name="email" type="email">
				</div>
				<div class="input-container password">
					<label for="password">Password</label>
					<input id="password" name="password" placeholder="Deve essere di almeno 6 caratteri" type="password">
					<i class="fa fa-eye-slash"></i>
				</div>
				<button class="signup-btn" type="submit">Accedi</button>
				<section class="copy legal">
					<p><span class="small">
						Effettuando il login acconsenti ai <br> <a href="info.html" target="popup" onclick="window.open('info.html','Termini di servizio','width=600,height=400')">Termini di servizio &amp; privacy</a><br> E alle nostre normative sui cookie.
					</span></p>
				</section>
                <div class="alert alert-success alert-dismissible" id="success" style="display:none;">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
				</div>
			</form>
		</div>
	</div>
</body>

<script>
	$(document).on('click','.signup-btn',function(e) { //Se é; stato cliccato il bottone con classe signup-btn avvia la funzione con evento e
		e.preventDefault();
		var data = $("#login").serialize(); //Serializza i dati del form con id login
		/*Utilizzo di ajax per eseguire il php in background senza il refresh della pagina*/
		$.ajax({
			 data: data, //Dati serializzati
			 type: "post", //Tipo di connessione: POST
			 url: "php/login.php",  //Indirizzo della pagina php che effettuer&agrave; il controllo da eseguire in background
			 cache: false, //Disabilita la memorizzazione temporanea di questa pagina per ottimizzare la richiesta ajax
			 success: function(data){ //Se la chiamata asincrona va a buon fine inizia una funzione con i dati serializzati
				 //alert(data);
				 if(data=='Logged'){ //Se l'utente é; gi&agrave; loggato reindirizzalo ad homepage.php
					window.location='homepage.php';
				 }
				 else if(data=='Login'){ //Se il login é; andato a buon fine reindirizza l'utente a homepage.php
					 //alert("Login effettuato")
					 window.location='homepage.php';
				 }
				 else if(data=='Error'){ //Controllo sulla verifica username/password dal db
					 alert("Username o password errati");
				 }
				 else alert(data); //Generico errore
			 }
		});
	});
	</script>

</html>
