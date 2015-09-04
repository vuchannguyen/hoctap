<?php
/**
 * @version $Id: mod_kunenalogin.php 4429 2011-02-17 11:30:49Z mahagr $
 * Kunena Login Module
 * @package Kunena login
 *
 * @Copyright (C)2010-2011 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 */
defined ( '_JEXEC' ) or die ();

// Kunena detection and version check
$minKunenaVersion = '1.6.3';
if (! class_exists ( 'Kunena' ) || Kunena::versionBuild () < 4344) {
	echo JText::sprintf ( 'MOD_KUNENALOGIN_KUNENA_NOT_INSTALLED', $minKunenaVersion );
	return;
}
// Kunena online check
if (! Kunena::enabled ()) {
	echo JText::_ ( 'MOD_KUNENALOGIN_KUNENA_OFFLINE' );
	return;
}

require_once (dirname ( __FILE__ ) . DS . 'class.php');

$params = ( object ) $params;
$modKunenaLogin = new ModKunenaLogin ( $params );
$modKunenaLogin->display();