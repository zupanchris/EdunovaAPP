<?php include_once '../../konfiguracija.php'; 
provjeraOvlasti();

$greska=array();
if(!isset($_GET["sifra"])){
	$greska=array();
	
	if(isset($_POST["sifra"])){
		
		include_once 'kontrole.php';

	print_r($greska);
	if(count($greska)==0){
	
		$izraz=$veza->prepare("update grupa set naziv=:naziv, smjer=:smjer, 
		predavac=:predavac,datumpocetka=:datumpocetka where sifra=:sifra;");
		$izraz->execute($_POST);
		header("location: index.php");
	}
	
	}else{
		header("location: " . $putanjaAPP . "logout.php");
	}
	
}else{
	$izraz=$veza->prepare("select * from grupa where sifra=:sifra");
	$izraz->execute($_GET);
	$_POST=$izraz->fetch(PDO::FETCH_ASSOC);
	if($_POST["datumpocetka"]==null){
		$_POST["datumpocetka"]="";
	}else{
		$_POST["datumpocetka"]=date("Y-m-d",strtotime($_POST["datumpocetka"]));
	}
	
}

?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <?php include_once '../../include/head.php'; ?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  </head>
  <body>
    <div class="grid-container">
    	<?php include_once '../../include/zaglavlje.php'; ?>
      	<?php include_once '../../include/izbornik.php'; ?>
      	<a href="index.php"><i style="color: red;" class="fas fa-chevron-circle-left fa-2x"></i></a>
      	<div class="grid-x grid-padding-x">
			<div class="large-4 cell">
				<form class="log-in-form" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
				  <h4 class="text-center">Promjena grupe</h4>
				  
				  
				  <label for="smjer">Smjer</label>
				  <select name="smjer" id="smjer">
				  	<?php 
				  	$izraz = $veza->prepare("
				  				select sifra,naziv from smjer
				  				order by naziv
				  	");
				  	$izraz->execute();
				  	$rezultati = $izraz->fetchAll(PDO::FETCH_OBJ);
				  	foreach ($rezultati as $red):?>
				  	
						  <option
						  <?php 
						  if(isset($_POST["smjer"]) && $_POST["smjer"]==$red->sifra){
						  	echo "selected=\"selected\"";
						  }
						  ?>
						   value="<?php echo $red->sifra ?>"><?php echo $red->naziv ?></option>
						  
				<?php endforeach;?>
				  </select>
				  
				  
				  <?php if(!isset($greska["naziv"])): ?>
				  <label>Naziv
				    <input  type="text" id="naziv" name="naziv" placeholder="PPX"
				    value="<?php echo isset($_POST["naziv"]) ? $_POST["naziv"] : ""; ?>">
				  </label>
				  <?php else: ?>
				   <label class="is-invalid-label">
				    Naziv
				    <input type="text"  id="naziv" name="naziv" class="is-invalid-input"  aria-invalid aria-describedby="greskaNaziv"
				    value="<?php echo isset($_POST["naziv"]) ? $_POST["naziv"] : ""; ?>" >
				    <span class="form-error is-visible" id="greskaNaziv"><?php echo $greska["naziv"]; ?></span>
				  </label>
				  <?php endif; ?>
				  
				  
				  
				  
				  <label for="predavac">Predavač</label>
				  <select name="predavac" id="predavac">
				  	<?php 
				  	$izraz = $veza->prepare("
				  				select a.sifra,concat(b.prezime, ' ', b.ime) as predavac
				  				from predavac a inner join osoba b on a.osoba=b.sifra
				  				order by 2
				  	");
				  	$izraz->execute();
				  	$rezultati = $izraz->fetchAll(PDO::FETCH_OBJ);
				  	foreach ($rezultati as $red):?>
				  	
						  <option
						  <?php 
						  if(isset($_POST["predavac"]) && $_POST["predavac"]==$red->sifra){
						  	echo "selected=\"selected\"";
						  }
						  ?>
						   value="<?php echo $red->sifra ?>"><?php echo $red->predavac ?></option>
						  
				<?php endforeach;?>
				  </select>
				
				  
				 
				    <?php if(!isset($greska["datumpocetka"])): ?>
				  <label>Datum početka
				    <input  type="date" id="datumpocetka" name="datumpocetka"
				    value="<?php echo isset($_POST["datumpocetka"]) ? $_POST["datumpocetka"] : ""; ?>">
				  </label>
				  <?php else: ?>
				   <label class="is-invalid-label">
				    Naziv
				    <input type="text"  id="datumpocetka" name="datumpocetka" class="is-invalid-input"  
				    aria-invalid aria-describedby="greskaDatumPocetka"
				    value="<?php echo isset($_POST["datumpocetka"]) ? $_POST["datumpocetka"] : ""; ?>" >
				    <span class="form-error is-visible" id="greskaDatumPocetka"><?php echo $greska["datumpocetka"]; ?></span>
				  </label>
				  <?php endif; ?>
				
				  
				
				  
				  
				   <input type="hidden" name="sifra" value="<?php echo $_POST["sifra"]; ?>"></input>
				  <p><input type="submit" class="button expanded" value="Promjeni grupu"></input></p>
				</form>
				
			</div>
			<div class="large-8 cell">
				Polaznici
				<input type="text" id="uvjet" placeholder="dio imena i prezimena polaznika" />
				<div style="max-height: 400px; overflow: auto;">
				<table>
					<thead>
						<tr>
							<th>Polaznik</th>
							<th>Broj ugovora</th>
							<th>Email</th>
							<th>Akcija</th>
						</tr>
					</thead>
					<tbody id="polaznici">
						<?php
						$izraz = $veza->prepare("
					
						select 
							a.sifra,
							b.ime, 
							b.prezime, 
							a.brojugovora,
							b.email
						from polaznik a inner join osoba b on a.osoba=b.sifra
						inner join clan c on c.polaznik=a.sifra
						where c.grupa=:grupa
						order by b.prezime, b.ime");
					$izraz->bindParam(":grupa", $_POST["sifra"]);
					$izraz->execute();
					$rezultati = $izraz->fetchAll(PDO::FETCH_OBJ);
					foreach ($rezultati as $red):
					?>
						
						<tr>
							<td><?php echo $red->prezime . " " . $red->ime ?></td>
							<td><?php echo $red->brojugovora; ?></td>
							<td><?php echo $red->email; ?></td>
							
							<td>
								<a class="brisanje" href="#" id="p_<?php echo $red->sifra ?>"><i class="far fa-trash-alt"></i></a>  
							</td>
						</tr>
						
					<?php endforeach; ?>
					</tbody>
				</table>
				</div>
				</div>
		</div>
		<?php include_once '../../include/podnozje.php'; ?>
		
      
    </div>

    <?php include_once '../../include/skripte.php'; ?>   
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
    	
    	$("#uvjet").autocomplete({
    		source: "traziPolaznik.php?grupa=<?php echo $_GET["sifra"] ?>",
    		minLength: 2,
    		focus: function(event,ui){
    			event.preventDefault();
    		},
    		select: function(event,ui){
    			event.preventDefault();
    			spremi(ui.item);
    		}
    	}).data("ui-autocomplete")._renderItem=function(ul,objekt){
    		return $("<li><img src=\"https://picsum.photos/50/50\" />").append("<a>" + objekt.ime + " " + objekt.prezime
    		 + " (" + objekt.oib + ")" + "</a>").appendTo(ul);
    	}
    	
    	function spremi(polaznik){
    		$.ajax({
			  type: "POST",
			  url: "dodajPolaznik.php",
			  data: "grupa=<?php echo $_GET["sifra"] ?>&polaznik="+polaznik.sifraPolaznik,
			  success: function(vratioServer){
			  	if(vratioServer==="OK"){
			  		$("#polaznici").append("<tr>" + 
			  		"<td>" + polaznik.prezime + " " + polaznik.ime + "</td>" +
			  		"<td>" + polaznik.brojugovora + "</td>" + 
			  		"<td>" + polaznik.email + "</td>" + 
			  		"<td><a class=\"brisanje\" href=\"#\" id=\"p_" + polaznik.sifraPolaznik + "\"><i class=\"far fa-trash-alt\"></i></td>" +  
			  		"</tr>");
			  		definirajBrisanje();
			  	}else{
			  		alert(vratioServer);
			  	}
			  }
			});
    	}
    	
    	function definirajBrisanje(){
	    	$(".brisanje").click(function(){
	    		var link = $(this)
	    		var sifra = link.attr("id").split("_")[1];
	    		$.ajax({
				  type: "POST",
				  url: "brisiPolaznik.php",
				  data: "grupa=<?php echo $_GET["sifra"] ?>&polaznik="+sifra,
				  success: function(vratioServer){
				  	if(vratioServer==="OK"){
				  		link.parent().parent().remove();
				  	}else{
				  		alert(vratioServer);
				  	}
				  }
				});
	    		
	    		return false;
	    	});
    	}
    	definirajBrisanje();
    </script>
    <!-- https://flatpickr.js.org/ -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/hr.js"></script>
    <script>
    	$("#datumpocetka").flatpickr({
    		locale: "hr"
    	});
    </script>
    
  </body>
</html>
