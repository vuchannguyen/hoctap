<?php
	defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<div id="jac-total-comment">
	<h2 class="componentheading"><span id="jac-number-total-comment"><?php echo $this->totalAll; ?> <?php if($this->totalAll > 1){echo JText::_("COMMENTS");}else{echo JText::_("COMMENT");}?></span>
	</h2>	
</div>