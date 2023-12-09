<?php include('header.html'); ?> <!-- Utilizzo il php per includere il file di header -->

        <!--Contenuto principale about us-->
        <section class="books">
            <h1>Il nostro team</h1>
            <p>DigiBook è una piattaforma di booksharing ideata da studenti universitari per studenti universitari… e non solo. La nostra idea principale è creare una community eterogenea ma con in comune la passione per la lettura, favorendo così lo scambio di libri in modo gratuito.</p>
            
            <div class="main"> <!--Classe principale contenente le cards del team-->
                
            <div class="cards"> <!--Sottoclasse singola card contenente le singole info di ciascuna card-->
                    <div class="image"> <!--Immagine card profilo-->
                        <img src="images/staff/gaia.jpeg">
                    </div>
                    <div class="persona"> <!--Nome-->
                        <h1>Bellifemine Gaia</h1>
                    </div>
                    <div class="informazioni"> <!--Descrizione-->
                        <p>
                            Studentessa presso Politecnico di Bari, specializzazione informatica.
                            La mia mansione verte sul lato server.
                        </p>
                    </div>
            </div>

                <div class="cards">
                    <div class="image">
                        <img src="images/staff/silvia.jpeg">
                    </div>
                    <div class="persona">
                        <h1> Deluzio <br> Silvia</h1>
                    </div>
                    <div class="informazioni">
                        <p>
                            Studentessa al terzo anno di Ingegneria informatica, ramo informatica.
                            Il mio compito &egrave; quello di redigere e supervisionare i documenti.
                        </p>
                    </div>
                </div>
            

            <div class="cards">
                    <div class="image">
                        <img src="images/staff/eugenia.jpeg">
                    </div>
                    <div class="persona">
                        <h1>Macchia Eugenia</h1>
                    </div>
                    <div class="informazioni">
                        <p>
                            Attualmente iscritta al terzo anno di Ingegneria informatica presso il Politecnico di Bari.
                            Il mio lavoro &egrave; quello di collaborare alla scrittura e revisione dei documenti.
                        </p>
                    </div>
                </div>
            </div>

            <div class="cards">
                    <div class="image">
                        <img src="images/staff/io.jpeg">
                    </div>
                    <div class="persona">
                        <h1>Mezzina Alessandro</h1>
                    </div>
                    <div class="informazioni">
                        <p>
                            Studente di ingegneria informatica con specializzazione in automazione presso il Politecnico di Bari.
                            Principalmente mi occupo della gestione delle pagine del sito.
                        </p>
                    </div>
                </div>
            </div>

            <div class="cards">
                    <div class="image">
                        <img src="images/staff/angela.jpeg">
                    </div>
                    <div class="persona">
                        <h1>Pellecchia Angela</h1>
                    </div>
                    <div class="informazioni">
                        <p>
                            Studentessa di Ingegneria Elettrica del Politecnico di Bari.
                            Svolgo la funzione di tester e collaboro alla revisione dei documenti.
                        </p>
                    </div>

                </div>
            </div>
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
        </script>


    </body>
</html>