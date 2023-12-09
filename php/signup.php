<?php

    //Questo file php effettua la registrazione di un utente al database
    session_start(); //Avvio della sessione per l'accesso alle relative variabili

    $err=0; //Variabile di errore
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]===true){ //Se l'utente é; gi&agrave; precedentemente loggato
        echo "Logged"; //Invia ad AJAX un messaggio "Logged"
        exit; //E termina l'esecuzione di questa pagina
    }

	require_once('config.php'); //Connessione al database, con require_once evitiamo che config.php venga incluso più volte

    $username = $password = $email = $confirm_password = ""; //Parametri da ottenere via form dalla pagina HTML
	$username_err = $password_err = $confirm_password_err = ""; //Stringhe di errore

 
	if($_SERVER["REQUEST_METHOD"] == "POST"){ //Se é; avvenuta una richiesta di POST

		$email=trim($_POST["email"]); //Si ottiene l'email eliminando gli eventuali spazi inseriti
 
    if(empty(trim($_POST["fname"]))){ //Se la variabile ottenuta via POST "fname" é; vuota
        $username_err = "Please enter a username."; //Aggiorna la variabile di errore
        echo $username_err; //Invia ad Ajax l'errore ottenuto
    } else{
        //Altrimenti avvia la preparazione della query per verificare che i parametri inseriti siano validi (disponibili) nel database
        $sql = "SELECT id FROM compratore WHERE username = ?"; //Seleziono l'id univoco
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Si effettua il collegamento delle variabili con i parametri per la query
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            //Imposta il parametro username
            $param_username = trim($_POST["fname"]);
            
            // Avvio l'esecuzione della query
            if(mysqli_stmt_execute($stmt)){//Se l'esecuzione pu&ograve; essere avviata

                mysqli_stmt_store_result($stmt); //Si immagazzina il risultato della query
                
                if(mysqli_stmt_num_rows($stmt) == 1){ //Si verifica che ci sia una riga nel db con i parametri richiesti
                    $username_err = "This username is already taken."; //Se ha restituito 1 riga, l'username esiste gi&agrave;
                    echo $username_err; //Avvisa Ajax con il relativo messaggio
                } else{
                    $username = trim($_POST["fname"]); //Altrimenti l'username é; valido e si salva
                }
            } else{
                echo "Oops! Something went wrong. Please try again later."; //Altrimenti generico errore
            }

            mysqli_stmt_close($stmt); //Chiusura della query
        }
    }
    
    // Verifico che la password sia valida
    if(empty(trim($_POST["password"]))){ //Se la variabile ottenuta via POST senza spazi "password" é; vuota
        $password_err = "Please enter a password."; //Aggiorna la stringa di errore
        echo $password_err; //Invia un messaggio per Ajax
    } elseif(strlen(trim($_POST["password"])) < 6){ //Altrimenti verifica che soddisfa i canoni di lunghezza
        $password_err = "Password must have atleast 6 characters."; //Se non soddisfa i criteri, invia un messaggio per Ajax con la stringa di errore
        echo $password_err;
    } else{
        $password = trim($_POST["password"]); //Altrimenti, tutto é; andato bene e si salva la password in una variabile
    }
    
    
    if(empty(trim($_POST["confirm_password"]))){ //Se il campo confirm_password del file HTML é; vuoto
        $confirm_password_err = "Please confirm password.";  //Avvisa Ajax con la relativa stringa di errore
        echo $confirm_password_err;
    } else{
        $confirm_password = trim($_POST["confirm_password"]); //Altrimenti salvo la password di conferma in una variabile
        if(empty($password_err) && ($password != $confirm_password)){ //Verifico che siano coincidenti
            $confirm_password_err = "Password did not match."; //Se non sono coincidenti, notifico Ajax con la stringa di errore
            echo($confirm_password_err);
        }
    }
    
    // Se non ci sono stati errori
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Preparo l'esecuzione della query per l'inserimento dell'utente nel database
        $sql = "INSERT INTO ricevente (username, email, password) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Collega le variabili
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);
            
            // Setta i parametri
            $param_username = $username;
            $param_email=$email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); //Si crea l'hash per la sicurezza della password
            
            // Avvia l'esecuzione della query
            if(mysqli_stmt_execute($stmt)){
                echo "Login"; //Se l'esecuzione della query é; andata a buon fine, avvisa Ajax con un messaggio "Login"
            } else{
                echo "Oops! Something went wrong. Please try again later."; //Altrimenti invia un messaggio di errore
            }

            //Chiusura della query
            mysqli_stmt_close($stmt);
        }
    }
    
    //Chiusura della connessione al database
    mysqli_close($link);
	}
?>