<?php
	session_start(); //Avvio la sessione per accedere alle variabili

	if(isset($_SESSION["loggedin"])){ //Se l'utente é; gi&agrave; loggato
		header("Location: homepage.php"); //Reindirizza la pagina index.php a homepage.php
	}
?>

<!doctype html> <!-- Specifica il tipo di documento al browser -->
<html>
<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!--Link al cdn font-awesome-->
	<link rel="stylesheet" type="text/css" href="css/style.css"> <!--Collegamento alla pagina css-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> <!--Per l'utilizzo di ajax-->

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

	<title> DigiBook </title> <!--Titolo della pagina-->
</head>

<body>
	<div class="split-screen"> <!-- Div sinistra contenento l'immagine -->
		<div class="left">
			<section class="copy">
				<h1>DigiBook</h1> <!--Piccola descrizione-->
				<p>La tua libreria personale...a portata di click</p>
			</section>
		</div>
		<div class="right"> <!--Div destra contenente le informazioni da inserire per la registrazione-->
			<form id="signup" method="post"> <!--Form per l'invio dei dati tramite richiesta POST-->
				<section class="copy">
				<h2>Registrati</h2>
				<div class="login-container"> <!--Div per il login-->
					<p>Hai gi&agrave; un account? </p> <!--Carattere speciale &amp per visualizzare la e commerciale-->
					<a href="login.php"><strong>Accedi</strong></a> <!--Link per il collegamento al login se l'utente é; in possesso delle credenziali-->
				</div>
				</section>
				<div class="input-container name"> <!--Div per inserire il nome-->
					<label for="fname">Nome</label>
					<input id="fname" name="fname" type="text">
				</div>
				<div class="input-container email"> <!--Div per inserire email-->
					<label for="email">Email</label>
					<input id="email" name="email" type="email">
				</div>
				<div class="input-container password"> <!--Div per inserire password-->
					<label for="password">Password</label>
					<input id="password" name="password" placeholder="Deve essere di almeno 6 caratteri" type="password"> <!--Type password per non mostrare il contenuto a vista-->
					<i class="fa fa-eye-slash"></i> <!--Icona occhio per password-->

					<label for="password">Conferma password</label> <!--Div conferma password-->
					<input  id="confirm_password" name="confirm_password" placeholder="Deve essere di almeno 6 caratteri" type="password">
					<i class="fa fa-eye-slash"></i>
				</div>
				<button class="signup-btn" disabled="true" type="submit"">Registrati</button> <!--Bottone per l'invio dei dati al form tramite POST-->
				<div class="registrationFormAlert" style="color:green;" id="CheckPasswordMatch"></div> <!--Div per mostrare messaggio di errore se le password non coincidono, controllo effettuato on javascript-->
				<section class="copy legal"> <!--Sezione relativa alla parte legale: termini di serivizio e cookie-->
					<p><span class="small">
					<!--Testo cliccabile che apre il link in una nuova finestra di prestabilite dimensioni-->
						Registrandoti accetti i nostri <br> <a href="info.html" target="popup" onclick="window.open('info.html','Termini di servizio','width=600,height=400')">Termini di servizio &amp; privacy</a>
					</span></p>
				</section>
			</form>
		</div>
	</div>

	<script>
		function checkPasswordMatch() {
			//Script per la verifica della conferma password
			var password = $("#password").val(); //Ricavo il valore dall'elemento con id password
			var confirmPassword = $("#confirm_password").val(); //Ricavo il valore dall'elemento con id confirm_password
			if (password != confirmPassword) //Se le password non coincidono
				$("#CheckPasswordMatch").html("Le password non corrispondono!"); //Stampa messaggio html nel div con id CheckPasswordMatch
			else
			{
				$('.signup-btn').prop('disabled', false); //Rende cliccabile nuovamente il bottone con classe signup-btn
				$("#CheckPasswordMatch").html(""); //Cancella eventuali errori mostrati
			}
				
		}
		$(document).ready(function () { //Quando il documento é; pronto avvia il controllo password
		   $("#confirm_password").keyup(checkPasswordMatch);
		});

		/*Avvio controllo ajax*/
		$(document).on('click','.signup-btn',function(e) { //Se é; stato cliccato il bottone signup-btn per la registrazione
		e.preventDefault(); //Ferma l'esecuzione di un evento in caso si verifichi e
		var data = $("#signup").serialize(); //Ottengo i dati dal form signup serializzati
		/*Uso ajax per una esecuzione del php in backgruound e avvisare l'utente di conferma/errore senza refreshare la pagina*/
		$.ajax({
			 data: data, //Dati serializzati
			 type: "post", //Tipo connessione POST: l'url non varia
			 url: "php/signup.php", //Pagina php contenente ci&ograve; che ajax deve processare in backgruound
			 cache: false, //Disabilita la memorizzazione temporanea di questa pagina per ottimizzare la richiesta ajax
			 success: function(data){ //Se la richiesta ajax va a buon fine, inizia la funzione con i dati serializzati
				 //alert(data);
				 if(data=='Logged'){ //Se il php ha restituito Logged, procede con la reindirizzazione alla homepage
					window.location='homepage.php';
				 }
				 else if(data=='Login'){ //Se il php ha restituito Login, procede con la reindirizzazione alla pagina di login
					 //alert("Login effettuato")
					 window.location='login.php';
				 }
				 else if(data=='Please enter a username.'){ //Altrimenti avvisa degli eventuali errori correlati
					 alert('Per favore inserire username');
				 }
				 else if(data=='This username is already taken.'){
					 alert('Questo username é; stato gi&agrave; utilizzato');
				 }
				 else { //Generico errore
					 //alert("Oops, qualcosa é; andato storto.\nVerifica che la mail non sia gi&agrave; stata utilizzata.");
					 alert(data);
				 }
			 }
		});
	});
		</script>
</body>

</html>