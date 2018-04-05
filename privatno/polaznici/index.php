<?php include_once '../../konfiguracija.php'; 
provjeraOvlasti();
$veza->exec("update programoperater set 
brojotvaranja=brojotvaranja+1 where program=3 
and operater=" . $_SESSION[$appID."autoriziran"]->sifra . ";");
$stranica = isset($_GET["stranica"]) ? $_GET["stranica"] : 1;

?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <?php include_once '../../include/head.php'; ?>
  </head>
  <body>
    <div class="grid-container">
    	<?php include_once '../../include/zaglavlje.php'; ?>
      	<?php include_once '../../include/izbornik.php'; ?>
      	
      	<div class="grid-x grid-padding-x">
			<div class="large-12 cell">
				
				<form method="get">
					<input type="text" name="uvjet" 
					placeholder="uvjet pretraživanja (broj ugovora, ime, prezime ili email)"
					value="<?php echo isset($_GET["uvjet"]) ? $_GET["uvjet"] : "" ?>" />
				</form>
				<a href="novi.php" class="button success expanded"><i class="fas fa-plus-circle fa-2x"></i></a>
				
				<?php
					
					$uvjet = "%" . (isset($_GET["uvjet"]) ? $_GET["uvjet"] : "") . "%";
					
					$izraz = $veza->prepare("
					
						select count(a.sifra)
						from polaznik a inner join osoba b
						on a.osoba=b.sifra
						where concat(b.ime, b.prezime, a.brojugovora,b.email) 
						like :uvjet
					
					");
					$izraz->execute(array("uvjet"=>$uvjet));
					$ukupnoRedova = $izraz->fetchColumn();
					$ukupnoStranica = ceil($ukupnoRedova/$brojRezultataPoStranici);
					
					if($stranica<1){
						$stranica=1;
					}
					if($ukupnoStranica>0 && $stranica>$ukupnoStranica){
						$stranica=$ukupnoStranica;
					}
					

					$izraz = $veza->prepare("
					
						select 
							a.sifra,
							b.ime, 
							b.prezime, 
							a.brojugovora,
							b.email
						from polaznik a inner join osoba b
						on a.osoba=b.sifra
						where concat(b.ime, b.prezime, a.brojugovora,b.email) 
						like :uvjet
						order by b.prezime, b.ime
					 	limit :stranica, :brojRezultataPoStranici");
					$izraz->bindValue("stranica", $stranica* $brojRezultataPoStranici -  $brojRezultataPoStranici , PDO::PARAM_INT);
					$izraz->bindValue("brojRezultataPoStranici", $brojRezultataPoStranici, PDO::PARAM_INT);
					$izraz->bindParam("uvjet", $uvjet);
					$izraz->execute();
					$rezultati = $izraz->fetchAll(PDO::FETCH_OBJ);
				if($ukupnoRedova>$brojRezultataPoStranici){
					include 'paginacija.php';
				}
				  ?>
				<table>
					<thead>
						<tr>
							<th>Polaznik</th>
							<th>Broj ugovora</th>
							<th>Email</th>
							<th>Akcija</th>
						</tr>
					</thead>
					<tbody>
						
					<?php
					foreach ($rezultati as $red):
					?>
						
						<tr>
							<td>
								<?php 
								
								if(file_exists("../../img/polaznici/" . $red->sifra . ".png")):
								
								?>
								<img style="max-width: 50px;" src="<?php echo $putanjaAPP ?>img/polaznici/<?php echo $red->sifra ?>.png?i=<?php echo date("U") ?>" />
								<?php else:
									echo "<i  style=\"color: #b74d4d\"  class=\"fas fa-user fa-3x\"></i>";
									endif;
									?>
								
								<?php echo $red->prezime . " " . $red->ime ?></td>
							<td><?php echo $red->brojugovora; ?></td>
							<td><?php echo $red->email; ?></td>
							
							<td>
								<a href="detalji.php?sifra=<?php echo $red->sifra ?>"><i class="far fa-edit fa-2x"></i></a>
								<a onclick="return confirm('Sigurno obrisati');" href="brisanje.php?sifra=<?php echo $red->sifra ?>"><i class="far fa-trash-alt fa-2x"></i></a>  
								<a class="upisiPolaznika" id="up_<?php echo $red->sifra ?>" href="#"><i class="fas fa-sign-in-alt fa-2x"></i></a>
								<a class="grupe" id="gr_<?php echo $red->sifra ?>" href="#"><i class="fas fa-list-ul fa-2x"></i></a>
								<a href="promjeniSliku.php?sifra=<?php echo $red->sifra ?>"><i class="far fa-file-image fa-2x"></i></a>
								<a class="posaljiEmail" id="pe_<?php echo $red->sifra ?>" href="#"><i class="far fa-envelope-open fa-2x"></i></a>
							</td>
						</tr>
						
					<?php endforeach; ?>
						
					</tbody>
				</table>
				<?php if($ukupnoRedova>$brojRezultataPoStranici){
					include 'paginacija.php';
				}?>
			</div>
		</div>
		<?php include_once '../../include/podnozje.php'; ?>
		
      
    </div>
    
    <div class="reveal" id="upisiPolaznika" data-reveal>
	  <p id="naslov"></p>
	  
	  <label for="smjer">Smjer</label>
	  <select id="smjer"></select>
	  
	  <label id="lgrupa" for="grupa">Grupa</label>
	  <select id="grupa"></select>
	  
	  <a href="#" class="success button expanded" id="upisi">Upiši polaznika</a>
	  
	  <button class="close-button" data-close aria-label="Close modal" type="button">
	    <span aria-hidden="true">&times;</span>
	  </button>
	</div>
	
	
	<div class="reveal" id="prikaziGrupe" data-reveal>
	  <p id="naslovGrupe"></p>
	  
	  <ol id="grupePopis"></ol>
	  
	  <button class="close-button" data-close aria-label="Close modal" type="button">
	    <span aria-hidden="true">&times;</span>
	  </button>
	</div>
	
	
	<div class="reveal" id="posaljiEmail" data-reveal>
	  <textarea id="poruka"></textarea>
	  
	  <a href="#" class="success button expanded" id="saljiEmail">Pošalji email</a>
	  
	  <button class="close-button" data-close aria-label="Close modal" type="button">
	    <span aria-hidden="true">&times;</span>
	  </button>
	</div>
    

    <?php include_once '../../include/skripte.php'; ?>
    <script>
    
    var sifraPolaznika;
    
    	$(".upisiPolaznika").click(function(){
    		$("#lgrupa").hide();
    		$("#grupa").hide();
    		$("#upisi").hide();
    		var tr = $(this).parent().parent();
    		
    		var ip = $(tr).find("td:first").html();
    		
    		$("#naslov").html("Upis na grupu za " + ip);
    		sifraPolaznika = $(this).attr("id").split("_")[1];
    		$.ajax({
			  type: "POST",
			  url: "traziSmjer.php",
			  data: "polaznik=" + sifraPolaznika,
			  success: function(vratioServer){
			  	$("#smjer").html("");
			  	$("#smjer").append("<option value=\"0\">Odaberite smjer</option>");
			  	var niz = jQuery.parseJSON(vratioServer);
			  	$( niz ).each(function(index,objekt) {
				 $("#smjer").append("<option value=\""  + objekt.sifra  + "\">"  + objekt.naziv  + " </option>");
				});
				
			  }
			});
    		
    		
    		$('#upisiPolaznika').foundation('open');
    		
    	});
    	
    	
    	$( "#smjer" ).change(function() {
    		$("#upisi").hide();
		  $.ajax({
			  type: "POST",
			  url: "traziGrupa.php",
			  data: "smjer=" + $( "#smjer" ).val(),
			  success: function(vratioServer){
			  	$("#grupa").html("");
			  	$("#grupa").append("<option value=\"0\">Odaberite grupa</option>");
			  	var niz = jQuery.parseJSON(vratioServer);
			  	$( niz ).each(function(index,objekt) {
				 $("#grupa").append("<option value=\""  + objekt.sifra  + "\">"  + objekt.naziv  + " </option>");
				});
				
			  }
			});
    		
		  $("#lgrupa").show();
    	  $("#grupa").show();
		});
		
		
		$( "#grupa" ).change(function() {
		  $("#upisi").show();
		});
		
		$("#upisi").click(function(){
			
			$.ajax({
			  type: "POST",
			  url: "../grupe/dodajPolaznik.php",
			  data: "grupa=" + $( "#grupa" ).val() + "&polaznik=" + sifraPolaznika,
			  success: function(vratioServer){
			  	if(vratioServer==="OK"){
			  		$('#upisiPolaznika').foundation('close');
			  	}else{
			  		alert(vratioServer);
			  	}
			  	
			  }
			});
			
			
			return false;
		});
    	
    	
    	
    	
    	
    	$(".grupe").click(function(){
    		var tr = $(this).parent().parent();
    		
    		var ip = $(tr).find("td:first").html();
    		
    		$("#naslovGrupe").html(ip + " upisano na ");
    		sifraPolaznika = $(this).attr("id").split("_")[1];
    		$.ajax({
			  type: "POST",
			  url: "popisGrupe.php",
			  data: "polaznik=" + sifraPolaznika,
			  success: function(vratioServer){
			  	$("#grupePopis").html("");
			  	var niz = jQuery.parseJSON(vratioServer);
			  	$( niz ).each(function(index,objekt) {
				 $("#grupePopis").append("<li>"  + objekt.naziv  + " <a class=\"brisanjePolaznika\" id=\"b_" + objekt.sifra + "\" href=\"\"><i class=\"far fa-trash-alt\"></i></a> </li>");
				});
				definirajBrisanjePolaznika();
				$('#prikaziGrupe').foundation('open');
			  }
			});
    	});
    	
    	function definirajBrisanjePolaznika(){
    		$(".brisanjePolaznika").click(function(){
    			var stavka = $(this);
    			$.ajax({
				  type: "POST",
				  url: "../grupe/brisiPolaznik.php",
				  data: "grupa=" + $(this).attr("id").split("_")[1] + "&polaznik=" + sifraPolaznika,
				  success: function(vratioServer){
				  	stavka.parent().remove();
				  }
				});
    			
    			return false;
    		});
    		
    		
    	}
    	
    	
    	
    	
    	
    	
    	$(".posaljiEmail").click(function(){
    		sifraPolaznika = $(this).attr("id").split("_")[1];
    		$('#posaljiEmail').foundation('open');
    		
    	});
    	
    	$("#saljiEmail").click(function(){
			
			$.ajax({
			  type: "POST",
			  url: "saljiEmail.php",
			  data: "poruka=" + $( "#poruka" ).val() + "&polaznik=" + sifraPolaznika,
			  success: function(vratioServer){
			  	if(vratioServer=="OK"){
			  		alert("Mail poslan");
			  	}else{
			  		alert(vratioServer);
			  	}
			  	$('#posaljiEmail').foundation('close');
			  }
			});
			
			
			return false;
		});
    	
    </script>
  </body>
</html>
