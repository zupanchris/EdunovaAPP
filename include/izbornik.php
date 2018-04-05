<div class="title-bar" data-responsive-toggle="example-menu" data-hide-for="medium">
  <button class="menu-icon" type="button" data-toggle="example-menu"></button>
  <div class="title-bar-title"><?php echo $naslovAPP; ?></div>
</div>

<div class="top-bar" id="example-menu">
  <div class="top-bar-left">
    <ul class="dropdown menu" data-dropdown-menu>
    	
      <?php 
      //poziv funkcije koja je definirana u /Edunova05/funkcije.php:4
      stavkaIzbornika($putanjaAPP."index.php", $naslovAPP); ?>
      
      <?php if(isset($_SESSION[$appID."autoriziran"])): ?>	
      	<?php stavkaIzbornika($putanjaAPP . "privatno/nadzornaPloca.php", "Nadzorna ploča"); ?>
		<li>
			<a href="#">Programi</a>
			<ul class="menu vertical">
				<?php 
				
				$izraz = $veza->prepare("
						select * from program order by sifra;
			
				");
				$izraz->execute();
				$rezultat = $izraz->fetchAll(PDO::FETCH_OBJ);
				foreach ($rezultat as $red) {
					if($_SESSION[$appID."autoriziran"]->uloga==="admin" || $red->uloga==""){
						stavkaIzbornika($putanjaAPP . $red->putanja, $red->izborniknaziv);
					}
					
					
				}
				
				
				
				
				?>
			</ul>
		</li>
		<?php if($_SESSION[$appID."autoriziran"]->uloga==="admin"):?>
		<li>
			<a href="#">Primjeri</a>
			<ul class="menu vertical">
				<?php 
				stavkaIzbornika($putanjaAPP . "privatno/primjeri/forma.php", "Forma"); 
				?>
			</ul>
		</li>
		<?php endif; 
		stavkaIzbornika($putanjaAPP . "privatno/era.php", "ERA"); 
     endif;?>
      
      <?php stavkaIzbornika($putanjaAPP."onama.php", "O nama"); ?>
      <?php stavkaIzbornika($putanjaAPP."kontakt.php", "Kontakt"); ?>
      
    </ul>
  </div>
  <?php if($_SERVER["PHP_SELF"]!==$putanjaAPP . "login.php"): ?>
  <div class="top-bar-right">
    <ul class="menu">
      <li style="width: 100%;"><?php if(!isset($_SESSION[$appID."autoriziran"])): ?>
	  		<a href="<?php echo $putanjaAPP; ?>login.php" class="button">Prijava</a>
	  	<?php else: ?>
	  		<a href="<?php echo $putanjaAPP; ?>logout.php" class="button">Odjava <?php 
	  		
	  			echo $_SESSION[$appID."autoriziran"]->ime;
	  			
	  			?></a>
	  	<?php endif;?></li>
    </ul>
  </div>
  <?php endif;?>
</div>