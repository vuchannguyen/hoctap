<?php
// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 

/*
 * ------------------------------------------------------------------------
 * JA Nex Template J17
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
*/
?>
<p class="ja-day">
	  <?php 
		echo "<span class=\"date\">".date ('d')."</span>";
		echo "<span class=\"month\">".JText::_(strtoupper(date ('F')))."</span>";
		echo "<span class=\"year\">".date ('Y')."</span>";
		
	  ?>
	</p>
	 
<?php if($this->countModules('topmenu-left')) : ?>
		<jdoc:include type="modules" name="topmenu-left" />
	<?php endif; ?>
	<?php if($this->countModules('topmenu-right')) : ?>
		<jdoc:include type="modules" name="topmenu-right" />
	<?php endif; ?>

