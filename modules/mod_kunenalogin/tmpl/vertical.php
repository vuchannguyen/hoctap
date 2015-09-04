<?php
/**
 * @version $Id: vertical.php 4047 2010-12-21 07:59:21Z severdia $
 * @package Kunena Login
 *
 * @Copyright (C) 2010-2011 Kunena Team. All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 */
defined('_JEXEC') or die();
?>
<div class="klogin-vert">
	<?php if($this->type == 'logout') : ?>
		<form action="index.php" method="post" name="login">
		<?php if ($this->params->get('greeting')) : ?>
			<div class="klogin-hiname">
			<?php echo JText::sprintf('MOD_KUNENALOGIN_HINAME','<strong>'.CKunenaLink::GetProfileLink ( $this->my->id, $this->user->getName()).'</strong>' ); ?>
			</div>
		<?php endif; ?>
	<div class="klogin-avatar">
		<?php if ($this->params->get('showav')) :
			$avatar =  $this->kunenaAvatar( $this->my->id ) ;
			echo $avatar;
		endif; ?>
	</div>
	<div>
	<?php if ($this->params->get('lastlog')) : ?>
	<div class="klogin-lastvisit">
		<ul>
			<li class="kms">
				<span class="klogin-lasttext"><?php echo JText::_('MOD_KUNENALOGIN_LASTVISIT'); ?></span>
				<span class="klogin-lastdate" title="<?php echo CKunenaTimeformat::showDate($this->my->lastvisitDate, 'date_today', 'utc'); ?>">
					<?php echo CKunenaTimeformat::showDate($this->my->lastvisitDate, 'ago', 'utc'); ?>
				</span>
			</li>
		</ul>
	</div>
	<?php endif; ?>
	</div>
	<div class="klogin-links">
		<input type="submit" name="Submit" class="kbutton" value="<?php echo JText::_('MOD_KUNENALOGIN_BUTTON_LOGOUT'); ?>" /></div>
		<div>
			<ul class="klogin-loginlink">
			<?php	if ($this->params->get('showmessage')) : ?>
				<?php if ($this->PMlink) : ?>
				<li class="klogin-mypm"><?php echo $this->PMlink; ?></li>
				<?php endif ?>
			<?php endif; ?>
			<?php if ($this->params->get('showprofile')) : ?>
				<li class="klogin-myprofile"><?php echo CKunenaLink::GetProfileLink ( $this->my->id, JText::_ ( 'MOD_KUNENALOGIN_MYPROFILE' ) ); ?></li>
			<?php endif; ?>
			<?php if ($this->params->get('showmyposts')) : ?>
				<li class="klogin-mypost"><?php echo CKunenaLink::GetShowMyLatestLink ( JText::_ ( 'MOD_KUNENALOGIN_MYPOSTS' ) ); ?></li>
			<?php endif; ?>
			<?php if ($this->params->get('showrecent')) : ?>
				<li class="klogin-recent"><?php echo CKunenaLink::GetShowLatestLink ( JText::_ ( 'MOD_KUNENALOGIN_RECENT' ) ); ?></li>
			<?php endif; ?>
			</ul>
		</div>
	<input type="hidden" name="option" value="<?php echo $this->logout['option']; ?>" />
	<?php if (!empty($this->logout['view'])) : ?>
	<input type="hidden" name="view" value="<?php echo $this->logout['view']; ?>" />
	<?php endif; ?>
	<input type="hidden" name="task" value="<?php echo $this->logout['task']; ?>" />
	<input type="hidden" name="<?php echo $this->logout['field_return']; ?>" value="<?php echo $this->return; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>

<?php else : ?>

<form action="index.php" method="post" name="login" class="klogin-form-login" >
	<?php echo $this->params->get('pretext'); ?>
	<fieldset class="input">
	<p class="klogin-form-login-username">
		<label for="klogin-username"><?php echo JText::_('MOD_KUNENALOGIN_USERNAME') ?></label>
		<input class="klogin-username" type="text" name="<?php echo $this->login['field_username']; ?>" class="kinputbox" alt="username" size="18" />
	</p>
	<p class="klogin-form-login-password">
		<label for="klogin-passwd"><?php echo JText::_('MOD_KUNENALOGIN_PASSWORD') ?></label>
		<input class="klogin-passwd" type="password" name="<?php echo $this->login['field_password']; ?>" class="kinputbox" size="18" alt="password" />
	</p>
	<?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
	<p class="klogin-form-login-remember"><label for="klogin-remember">
	<input class="klogin-remember" type="checkbox" name="remember" value="yes" alt="<?php echo JText::_('MOD_KUNENALOGIN_REMEMBER_ME') ?>" />
		<?php echo JText::_('MOD_KUNENALOGIN_REMEMBER_ME') ?></label>
	</p>
	<?php endif; ?>
	<input type="submit" name="Submit" class="kbutton" value="<?php echo JText::_('MOD_KUNENALOGIN_BUTTON_LOGIN') ?>" />
	</fieldset>
	<ul class="klogin-logoutlink">
		<li class="klogin-forgotpass"><?php echo CKunenaLogin::getLostPasswordLink (); ?></li>
		<li class="klogin-forgotname"><?php echo CKunenaLogin::getLostUserLink ();?></li>
		<?php
		$registration = CKunenaLogin::getRegisterLink ();
		if ($registration) : ?>
		<li class="klogin-register"><?php echo $registration ?></li>
		<?php endif; ?>
	</ul>
	<?php echo $this->params->get('posttext'); ?>
	<input type="hidden" name="option" value="<?php echo $this->login['option']; ?>" />
	<?php if (!empty($this->login['view'])) : ?>
	<input type="hidden" name="view" value="<?php echo $this->login['view']; ?>" />
	<?php endif; ?>
	<input type="hidden" name="task" value="<?php echo $this->login['task']; ?>" />
	<input type="hidden" name="<?php echo $this->login['field_return']; ?>" value="<?php echo $this->return; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
	<?php endif; ?>
</div>