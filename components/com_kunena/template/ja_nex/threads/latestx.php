<?php
/**
 * @version $Id: latestx.php 4336 2011-01-31 06:05:12Z severdia $
 * Kunena Component
 * @package Kunena
 *
 * @Copyright (C) 2008 - 2011 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 *
 * Based on FireBoard Component
 * @Copyright (C) 2006 - 2007 Best Of Joomla All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.bestofjoomla.com
 *
 * Based on Joomlaboard Component
 * @copyright (C) 2000 - 2004 TSMF / Jan de Graaff / All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author TSMF & Jan de Graaff
 **/
// Dont allow direct linking
defined ( '_JEXEC' ) or die ();
?>

<?php
$this->displayAnnouncement ();
CKunenaTools::showModulePosition ( 'kunena_announcement' );
?>
<!-- B: List Actions -->
<div class="klist-actions clearfix">

  <?php if ($this->mode=='posts') : ?>
		<div class="klist-actions-info-all">
			<strong><?php echo $this->escape($this->total)?></strong>
			<?php echo $this->escape($this->header); ?>
		</div>
  <?php else: ?>
		<div class="klist-actions-info-all">
			<strong><?php echo $this->escape($this->total)?></strong>
			<?php echo JText::_('COM_KUNENA_DISCUSSIONS')?>
		</div>

		<?php if ($this->func != 'mylatest' && $this->func != 'noreplies') : ?>
		<div class="klist-times-all">
			<form id="timeselect" name="timeselect" method="post" target="_self" action="<?php echo $this->escape(JURI::getInstance()->toString());?>">
			<?php
			// make the select list for time
			$timesel[] = JHTML::_('select.option', 0, JText::_('COM_KUNENA_SHOW_LASTVISIT'));
			$timesel[] = JHTML::_('select.option', 4, JText::_('COM_KUNENA_SHOW_4_HOURS'));
			$timesel[] = JHTML::_('select.option', 8, JText::_('COM_KUNENA_SHOW_8_HOURS'));
			$timesel[] = JHTML::_('select.option', 12, JText::_('COM_KUNENA_SHOW_12_HOURS'));
			$timesel[] = JHTML::_('select.option', 24, JText::_('COM_KUNENA_SHOW_24_HOURS'));
			$timesel[] = JHTML::_('select.option', 48, JText::_('COM_KUNENA_SHOW_48_HOURS'));
			$timesel[] = JHTML::_('select.option', 168, JText::_('COM_KUNENA_SHOW_WEEK'));
			$timesel[] = JHTML::_('select.option', 720, JText::_('COM_KUNENA_SHOW_MONTH'));
			$timesel[] = JHTML::_('select.option', 8760, JText::_('COM_KUNENA_SHOW_YEAR'));
			// build the html select list
			echo JHTML::_('select.genericlist', $timesel, 'sel', 'class="inputboxusl" onchange="this.form.submit()" size="1"', 'value', 'text', $this->escape($this->show_list_time));
			?>
			</form>
		</div>
		<?php endif; ?>

		<div class="klist-jump-all">
			<?php $this->displayForumJump () ?>
		</div>
<?php endif; ?>

<?php
//pagination 1
if (count ( $this->messages ) > 0) :
	echo '<div class="klist-pages-all">';
	$maxpages = 5 - 2; // odd number here (# - 2)
	echo $pagination = $this->getPagination ( $this->func, $this->show_list_time, $this->page, $this->totalpages, $maxpages );
	echo '</div>';
endif;
?>

</div>
<!-- F: List Actions -->

<?php
if (count ( $this->threads ) > 0) :
	$this->displayItems ();
?>


<!-- B: List Actions -->
<div class="klist-actions clearfix">
		<div class="klist-actions-info-all">
			<strong><?php echo $this->total?></strong>
				<?php echo $this->mode=='posts' ? $this->escape($this->header) : JText::_('COM_KUNENA_DISCUSSIONS')?>
		</div>
		<?php
			//pagination 1
			if (count ( $this->messages ) > 0) :
				echo '<div class="klist-pages-all">';
				echo $pagination;
				echo '</div>';
			endif;
		?>
</div>
<!-- F: List Actions -->

<?php endif; ?>

<?php
$this->displayWhoIsOnline ();
$this->displayStats ();
?>
