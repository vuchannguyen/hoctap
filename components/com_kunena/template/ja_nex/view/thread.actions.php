<?php
/**
 * @version $Id$
 * Kunena Component
 * @package Kunena
 *
 * @Copyright (C) 2008 - 2011 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/

// Dont allow direct linking
defined ( '_JEXEC' ) or die ();
?>
<!-- B: Thread Actions -->
<div class="klist-actions clearfix">
		<div class="klist-actions-goto">
			<?php echo $this->goto ?>
		</div>
		<div class="klist-actions-forum">
		<?php if ($this->thread_reply || $this->thread_subscribe || $this->thread_favorite ) : ?>
			<div class="kmessage-buttons-row">
			<?php echo $this->thread_reply ?>
			<?php echo $this->thread_subscribe ?>
			<?php echo $this->thread_favorite ?>
			</div>
		<?php endif ?>
		<?php if ($this->thread_delete || $this->thread_move || $this->thread_sticky || $this->thread_lock) : ?>
			<div class="kmessage-buttons-row">
			<?php echo $this->thread_delete ?>
			<?php echo $this->thread_sticky ?>
			<?php echo $this->thread_move ?>
			<?php echo $this->thread_lock ?>
			</div>
		<?php endif ?>
		</div>

		<div class="klist-actions-forum">
		<?php if (isset ( $this->thread_new )) : ?>
			<div class="kmessage-buttons-row">
			<?php echo $this->thread_new; ?>
			</div>
		<?php endif ?>
		<?php if (isset ( $this->thread_moderate )) : ?>
			<div class="kmessage-buttons-row">
			<?php echo $this->thread_moderate; ?>
			</div>
		<?php endif ?>
		<?php if (isset ( $this->thread_merge )) : ?>
			<div class="kmessage-buttons-row">
				<?php echo $this->thread_merge; ?>
			</div>
		<?php endif ?>
		</div>
		<?php if (!empty($this->layout_buttons)) : ?>
		<div class="klist-actions-forum">
			<div class="kmessage-buttons-row">
			<?php echo implode(' ', $this->layout_buttons) ?>
			</div>
		</div>
		<?php endif ?>
</div>
<div class="klist-pages-all clearfix">
	<?php echo $this->pagination; ?>
</div>
<!-- F: Thread Actions -->