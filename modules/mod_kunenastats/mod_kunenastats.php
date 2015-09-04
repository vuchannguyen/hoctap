<?php
/**
 * @version $Id: mod_kunenastats.php 4431 2011-02-17 11:35:41Z mahagr $
 * KunenaStats Module
 * @package Kunena Stats
 *
 * @Copyright (C) 2010 www.kunena.com All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.com
 */
defined ( '_JEXEC' ) or die ();

// Kunena detection and version check
$minKunenaVersion = '1.6.3';
if (! class_exists ( 'Kunena' ) || Kunena::versionBuild () < 4344) {
	echo JText::sprintf ( 'MOD_KUNENASTATS_KUNENA_NOT_INSTALLED', $minKunenaVersion );
	return;
}
// Kunena online check
if (! Kunena::enabled ()) {
	echo JText::_ ( 'MOD_KUNENASTATS_KUNENA_OFFLINE' );
	return;
}
require_once dirname ( __FILE__ ) . '/class.php';

$params = ( object ) $params;
$kstats = new ModuleKunenaStats ( $params );
$kstats->display ();