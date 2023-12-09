<?php 
    session_start();
    include('header.html');
    require_once('php/config.php');

    $email=$_SESSION['email'];
    
    // si estrapola l'id dell'utente correntemente loggato conoscendo la sua email (estrapolata dalla variabile di sessione)
    $sql0="SELECT ricevente.id AS id FROM ricevente WHERE ricevente.email='$email'"; 
    $result=$link->query($sql0); // si salva il risultato della query in una variabile
    $row = mysqli_fetch_array($result); // si scorrono le righe della variabile result
    $cid=$row['id']; // si estrapola il campo id dalla riga

    /* Seconda query per ottenere i dati da inserire in tabella é; incluso anche l'id del libro lid per inviarlo tramite get al file php*/
    $sql2="SELECT libri.titolo, libri.autore, libri.descrizione, ricevente.username, libri.id AS lid
    FROM ricevente INNER JOIN offerente ON ricevente.id=offerente.id INNER JOIN libri ON offerente.id=libri.idVC INNER JOIN wishlist ON libri.id=wishlist.idLibri
    WHERE wishlist.idRicevente='$cid'";

    // spiegazione analoga al file profile1.php

    $result = $link->query($sql2);
    $tabella = " ";
    $i=0;
    while($row = $result->fetch_assoc())
    {
        $tabella.= "
        <form method='GET'>
        <tr>
            <th>".$row['titolo']."</th>
            <th>".$row['autore']."</th>
            <th>".$row['descrizione']."</th>
            <th>".$row['username']."</th>
            <th><a id='link$i' cid='".$cid."' lid='".$row['lid']."' href='' onclick='return false;'><button>Rimuovi</button></a></th>
        </tr>
        </form>";
        $i++;
}

    $link->close();
?>
<style>
    <?php include 'css/wishlist.css'; ?>
</style>
<div style="overflow-x:scroll;">
    <table id="tID">
        <thead>
            <tr>
                <th><h2>Titolo</h2></th> 
                <th><h2>Autore</h2></th> 
                <th><h2>Descrizione</h2></th>
                <th><h2>offerente</h2></th> 
            </tr>
        </thead>
        <tbody>
            <?php echo $tabella; ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    var rowCount=document.getElementById("tID").rows.length;
    
    for(var i = 0; i < rowCount; i++){
    $('#link' + i).on('click', {'idx': i}, function(e) {
                    //alert('you clicked ' + e.data.idx);
                    var cid= $(this).attr('cid');
                    var lid= $(this).attr('lid');

                    $.ajax({
                        type: "get",
                        url: "php/delete.php?cid="+cid+"&lid="+lid,
                        data: {
                            cid: cid,
                            lid: lid
                        },
                        cache: false,
                        success: function(data){
                            if(data=="OK") {
                                alert("Elemento rimosso dalla tua wishlist.");
                                window.location.reload(); 
                            }
                            else if(data=="ERROR") alert("Impossibile rimuovere l'elemento");
                            else alert(data);
                        },
                        error: function(){
                            alert("Qualcosa non va");
                        }
                    });
                });
            };
</script>
</body>