<?php
/**
 * ------------------------------------------------------------------------
 * JA Nex Template for Joomla 2.5
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div id="jac-lasmod<?php echo $module->id;?>" class="jac-lasmod">
	<div class="ja-box-ct clearfix">
	<ul class="jac-lasmod-main">
<?php if($list): ?>
		<?php foreach ($list as $item):?>
		<li class="jac-has-layout clearfix">
			
			<div class="clearfix">
			<?php if($params->get("avatar", "1")):?>
				<?php 
					$avartaSize = $params->get("avatar_size", "");
					if($avartaSize){
						$avartaSize = "width:".$avartaSize."px;height:".$avartaSize."px";
					}
				?>
				<?php if(is_array($item->avatar[0])):?>	
					<img alt="" src="<?php echo $item->avatar[0][0];?>" style="<?php echo $avartaSize;?>" />
				<?php else:?>
					<img alt="" src="<?php echo $item->avatar[0];?>" style="<?php echo $avartaSize;?>" />
				<?php endif;?>
			<?php endif;?>
			<?php if($params->get("show_date",1)):?>
				<span class="jac-lasmod-time"><?php echo $item->date;?></span>
			<?php endif;?>
			<?php if($params->get("show_author_info",1)):?>
				<span class="jac-lasmod-author"><?php echo $item->author_info;?></span>
			<?php endif;?>
			
			<?php if($params->get("show_content_title",1)):?>
				<h4 class="jac-lasmod-title clearfix">
					<a href="<?php echo $item->referer;?>" title="<?php echo $item->contenttitle;?>">
					<?php echo $item->contenttitle;?><?php if($params->get("showcommentcount",1)):?>(<?php echo $item->commentcount;?>)<?php endif;?>
					</a>
				</h4>
			<?php endif;?>
			
			</div>
			<?php if($params->get("showcontent",1)):?>
				<div class="jac-mod_content clearfix">
					<?php echo $item->comment;?>
				</div>
			<?php endif;?>
	
			<?php if($params->get("show_vote",1)):?>
				<p class="jac-lasmod-vote">
					<?php echo JText::_("NUMBER_OF_VOTE") . ': ' . $item->voted;?>
				</p>
			<?php endif;?>
		</li>
		<?php endforeach;?>
<?php else: ?>
		<li class="jac-has-layout clearfix"><?php echo JText::_("THERE_IS_NO_COMMENT"); ?></li>
<?php endif;?>
	</ul>
	</div>
</div>