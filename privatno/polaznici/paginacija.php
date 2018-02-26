<ul class="pagination text-center" role="navigation">
				  <li class="pagination-previous"><a href="?stranica=<?php echo $stranica - 1; ?>&uvjet=<?php echo isset($_GET["uvjet"]) ? $_GET["uvjet"] : "" ?>">Prethodno</a></li>
				 
				  <li><?php echo $stranica ?> / <?php echo $ukupnoStranica; ?></li>
				 
				  <li class="pagination-next"><a href="?stranica=<?php echo $stranica + 1; ?>&uvjet=<?php echo isset($_GET["uvjet"]) ? $_GET["uvjet"] : "" ?>">Sljedeće</a></li>
				</ul>