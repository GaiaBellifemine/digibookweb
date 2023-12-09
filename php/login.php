<?php
    //Questo file php effettua il login di un utente al db con i relativi controlli
    session_start(); //Avvia la sessione per l'accesso alle relative variabili

    $err=0; //Variabile di controllo errore
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]===true){ //Se un utente ha gi&agrave; effettuato l'accesso
        echo "Logged"; //Notifica Ajax (../login.php) con un echo per il controllo e il reindirizzamento
        exit; //E termina questo file
    }

    require_once "config.php"; //Per la connessione al db, require_once permette di non includere nuovamente il file se giá incluso
    $email=$password=""; //Stringhe con i parametri che saranno riempiti dal form (../login.php)
    $email_err = $password_err = $login_err = ""; //Stringe per la gestione degli errori

    if($_SERVER["REQUEST_METHOD"]=="POST") { //Se é stata effettuata una richiesta di POST
        if(empty(trim($_POST["email"]))) { //Se dal form, il dato "email" senza spazi é vuoto
            $email_err="Please enter username"; //Aggiorna le variabili di errore
            $err=-1;
        }
        else { //Altrimenti salva il parametro ricevuto
            $email=trim($_POST["email"]);
            $_SESSION["email"]=$email; //Salva la mail in variabile di sessione per utilizzi futuri
        }

        if(empty(trim($_POST["password"]))){ //Se il dato "password" ricevuto via POST senza spazi é vuoto
            $password_err = "Please enter your password."; //Aggiorna le variabili di errore
            $err=-1;
        } else{
            $password = trim($_POST["password"]); //Altrimenti salva la password
        }

        if(empty($email_err)&&empty($password_err)) { //Se non ci sono stati errori
            //Prepara la query per selezionare id e mail
            $sql = "SELECT id, email, password FROM ricevente WHERE email = ?";

            if($stmt=mysqli_prepare($link, $sql)) { //Con l'utilizzo della prepare preveniamo un eventuale SQL Injection
                //Effettua il collegamento delle variabili con i parametri
                mysqli_stmt_bind_param($stmt, "s", $param_email);

                $param_email=$email; //Salva il parametro mail

                //Avvia l'esecuzione della query
                if(mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt); //Se l'esecuzione viene eseguita, salva il risultato

                    if(mysqli_stmt_num_rows($stmt)==1){ //Se la query ha restituito una riga
                        mysqli_stmt_bind_result($stmt, $id, $email, $hased_password);
                        if(mysqli_stmt_fetch($stmt)) { //Scorro i risultati della query
                            if(password_verify($password, $hased_password)) { //Se il controllo password inserita con la password criptata va a buon fine
                                
                                //Salvo i valori nelle variabili di sessione per utilizzi futuri
                                $_SESSION["loggedin"]=true; //Utente loggato
                                $_SESSION["id"]=$id;
                                $_SESSION["email"]=$email;
                                
                                $err=1; //Se err=1 allora il controllo password é andato a buon fine

                            } else { //Altrimenti aggiorna la stringa di errore
                                $login_err="Username o password incorretti";
                            }
                        }
                    } else { //Se la query non ha restituito nessuna riga allora l'username o la password sono errati
                        $login_err="Username o password errati"; //Aggiorna le variabili di errore
                        $err=-1;
                    }
                } else { //Altrimenti errore generico
                    $err=-1;
                }

                mysqli_stmt_close($stmt); //Chiusura query
            }
        }

        //Controllo per l'invio del messaggio ad Ajax per effettuare l'esecuzione
        if($err==1){ //Se err vale 1 il login é stato effettuato
            echo "Login";
        }
        else echo "Error"; //Altrimenti c'é stato un errore

        mysqli_close($link); //Chiusura della connessione al db
    }
?>