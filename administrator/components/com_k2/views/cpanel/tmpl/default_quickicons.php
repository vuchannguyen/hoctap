<?php
/**
 * @version		$Id$
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2011 JoomlaWorks, a business unit of Nuevvo Webware Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>

  <div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&amp;view=item">
		    <img alt="<?php echo JText::_('K2_ADD_NEW_ITEM'); ?>" src="<?php echo JURI::root(true); ?>/media/k2/assets/images/dashboard/item-new.png" />
		    <span><?php echo JText::_('K2_ADD_NEW_ITEM'); ?></span>
	    </a>
    </div>
  </div>

  <div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&amp;view=items&amp;filter_trash=0">
		    <img alt="<?php echo JText::_('K2_ITEMS'); ?>" src="<?php echo JURI::root(true); ?>/media/k2/assets/images/dashboard/items.png" />
		    <span><?php echo JText::_('K2_ITEMS'); ?></span>
	    </a>
    </div>
  </div>

	<div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&amp;view=items&amp;filter_featured=1">
		    <img alt="<?php echo JText::_('K2_FEATURED_ITEMS'); ?>" src="<?php echo JURI::root(true); ?>/media/k2/assets/images/dashboard/items-featured.png" />
		    <span><?php echo JText::_('K2_FEATURED_ITEMS'); ?></span>
	    </a>
    </div>
  </div>

  <div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&amp;view=items&amp;filter_trash=1">
		    <img alt="<?php echo JText::_('K2_TRASHED_ITEMS'); ?>" src="<?php echo JURI::root(true); ?>/media/k2/assets/images/dashboard/items-trashed.png" />
		    <span><?php echo JText::_('K2_TRASHED_ITEMS'); ?></span>
	    </a>
    </div>
  </div>

	<div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&amp;view=categories&amp;filter_trash=0">
		    <img alt="<?php echo JText::_('K2_CATEGORIES'); ?>" src="<?php echo JURI::root(true); ?>/media/k2/assets/images/dashboard/categories.png" />
		    <span><?php echo JText::_('K2_CATEGORIES'); ?></span>
	    </a>
    </div>
  </div>

	<div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&amp;view=categories&amp;filter_trash=1">
		    <img alt="<?php echo JText::_('K2_TRASHED_CATEGORIES'); ?>" src="<?php echo JURI::root(true); ?>/media/k2/assets/images/dashboard/categories-trashed.png" />
		    <span><?php echo JText::_('K2_TRASHED_CATEGORIES'); ?></span>
	    </a>
    </div>
  </div>

	<?php if(!$this->params->get('lockTags') || $this->user->gid>23): ?>
	<div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&amp;view=tags">
		    <img alt="<?php echo JText::_('K2_TAGS'); ?>" src="<?php echo JURI::root(true); ?>/media/k2/assets/images/dashboard/tags.png" />
		    <span><?php echo JText::_('K2_TAGS'); ?></span>
	    </a>
    </div>
  </div>
  <?php endif; ?>

	<div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&amp;view=comments">
		    <img alt="<?php echo JText::_('K2_COMMENTS'); ?>" src="<?php echo JURI::root(true); ?>/media/k2/assets/images/dashboard/comments.png" />
		    <span><?php echo JText::_('K2_COMMENTS'); ?></span>
	    </a>
    </div>
  </div>

  <?php if ($this->user->gid>23): ?>
  <!--
  <div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&view=users">
		    <img src="<?php echo JURI::root(true); ?>/media/k2/assets/images/dashboard/users.png" alt="<?php echo JText::_('K2_USERS'); ?>" />
		    <span><?php echo JText::_('K2_USERS'); ?></span>
	    </a>
    </div>
  </div>

  <div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&view=userGroups">
		    <img src="<?php echo JURI::root(true); ?>/media/k2/assets/images/dashboard/user-groups.png" alt="<?php echo JText::_('K2_USER_GROUPS'); ?>" />
		    <span><?php echo JText::_('K2_USER_GROUPS'); ?></span>
	    </a>
    </div>
  </div>
  -->

  <div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&amp;view=extraFields">
		    <img alt="<?php echo JText::_('K2_EXTRA_FIELDS'); ?>" src="<?php echo JURI::root(true); ?>/media/k2/assets/images/dashboard/extra-fields.png" />
		    <span><?php echo JText::_('K2_EXTRA_FIELDS'); ?></span>
	    </a>
    </div>
  </div>

	<div style="float:left;">
    <div class="icon">
	    <a href="index.php?option=com_k2&amp;view=extraFieldsGroups">
		    <img alt="<?php echo JText::_('K2_EXTRA_FIELD_GROUPS'); ?>" src="<?php echo JURI::root(true); ?>/media/k2/assets/images/dashboard/extra-field-groups.png" />
		    <span><?php echo JText::_('K2_EXTRA_FIELD_GROUPS'); ?></span>
	    </a>
    </div>
  </div>
  <?php endif; ?>

	<div style="float:left;">
    <div class="icon">
	    <a href="#" onclick="window.open('http://www.splashup.com/splashup/','splashupEditor','height=700,width=990,toolbar=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes'); return false;">
		    <img alt="<?php echo JText::_('K2_EDIT_IMAGES_WITH_SPLASHUP'); ?>" src="<?php echo JURI::root(true); ?>/media/k2/assets/images/dashboard/image-editing.png" />
		    <span><?php echo JText::_('K2_EDIT_IMAGES_WITH_SPLASHUP'); ?></span>
	    </a>
    </div>
  </div>

  <div style="float:left;">
    <div class="icon">
    	<a target="_blank" href="http://getk2.org/documentation/">
    		<img alt="<?php echo JText::_('K2_DOCS_AND_TUTORIALS'); ?>" src="<?php echo JURI::root(true); ?>/media/k2/assets/images/dashboard/documentation.png" />
    		<span><?php echo JText::_('K2_DOCS_AND_TUTORIALS'); ?></span>
    	</a>
    </div>
  </div>

  <?php if ($this->user->gid>23): ?>
  <div style="float:left;">
    <div class="icon">
    	<a target="_blank" href="http://community.getk2.org/">
    		<img alt="<?php echo JText::_('K2_COMMUNITY'); ?>" src="<?php echo JURI::root(true); ?>/media/k2/assets/images/dashboard/help.png" />
    		<span><?php echo JText::_('K2_COMMUNITY'); ?></span>
    	</a>
    </div>
  </div>

  <div style="float:left;">
    <div class="icon">
	    <a class="modal" rel="{handler: 'iframe', size: {x: 1040, y: 600}}" href="http://joomlareader.com" title="<?php echo JText::_('K2_JOOMLA_NEWS_FROM_MORE_THAN_200_SOURCES_WORLDWIDE'); ?>">
		    <img alt="<?php echo JText::_('K2_JOOMLA_NEWS_FROM_MORE_THAN_200_SOURCES_WORLDWIDE'); ?>" src="<?php echo JURI::root(true); ?>/media/k2/assets/images/dashboard/joomlareader.png" />
		    <span><?php echo JText::_('K2_JOOMLAREADERCOM'); ?></span>
	    </a>
    </div>
  </div>
  <?php endif; ?>
