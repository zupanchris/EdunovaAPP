<?php

function inputText($name,$placeholder,$greska){
	if(!isset($greska[$name])): ?>
	  <label><?php echo $name ?>
	    <input  type="text" id="<?php echo $name ?>" name="<?php echo $name ?>" 
	    placeholder="<?php echo $placeholder ?>"
	    value="<?php echo isset($_POST[$name]) ? $_POST[$name] : ""; ?>">
	  </label>
	  <?php else: ?>
	   <label class="is-invalid-label">
	    <?php echo $name ?>
	    <input type="text"  id="<?php echo $name ?>" name="<?php echo $name ?>" class="is-invalid-input"  
	    aria-invalid aria-describedby="uuid"
	    value="<?php echo isset($_POST[$name]) ? $_POST[$name] : ""; ?>" >
	    <span class="form-error is-visible" id="uuid"><?php echo $greska[$name]; ?></span>
	  </label>
	  <?php endif;
}
