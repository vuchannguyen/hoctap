<?php
/**
* @version $Id$
* Kunena Component
* @package Kunena
*
* @Copyright (C) 2011 Kunena Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.kunena.org
**/
defined( '_JEXEC' ) or die();

$kunena_config = KunenaFactory::getConfig ();
$document = JFactory::getDocument();
$template = KunenaFactory::getTemplate();
$this->params = $template->params;

$rtl = JFactory::getLanguage()->isRTL();

// Template requires Mootools 1.2 framework
$template->loadMootools();

// We load mediaxboxadvanced library only if configuration setting allow it
if ( $kunena_config->lightbox == 1 ) {
	// We load mediaxboxadvanced library
	CKunenaTools::addStyleSheet ( KUNENA_DIRECTURL . 'js/mediaboxadvanced/css/mediaboxAdv.css');
	CKunenaTools::addScript( KUNENA_DIRECTURL . 'js/mediaboxadvanced/js/mediaboxAdv.js' );
}

// New Kunena JS for default template
// TODO: Need to check if selected template has an override
CKunenaTools::addScript ( KUNENA_DIRECTURL . 'template/default/js/default-min.js' );

$skinner = $this->params->get('enableSkinner', 0);

if (file_exists ( KUNENA_JTEMPLATEPATH . '/css/kunena.forum.css' )) {
	// Load css from Joomla template
	CKunenaTools::addStyleSheet ( KUNENA_JTEMPLATEURL . 'css/kunena.forum-min.css', $rtl );

	if ($skinner && file_exists ( KUNENA_JTEMPLATEPATH . '/css/kunena.skinner.css' )){
		CKunenaTools::addStyleSheet ( KUNENA_JTEMPLATEURL . 'css/kunena.skinner-min.css', $rtl );
	} elseif (!$skinner && file_exists ( KUNENA_JTEMPLATEPATH . '/css/kunena.default.css' )) {
		CKunenaTools::addStyleSheet ( KUNENA_JTEMPLATEURL . 'css/kunena.default-min.css', $rtl );
	}
} else if (file_exists ( KUNENA_ABSTMPLTPATH . '/css/kunena.forum.css' )){
	// Load css from the current template
	CKunenaTools::addStyleSheet ( KUNENA_TMPLTCSSURL, $rtl );

	if ($skinner && file_exists ( KUNENA_ABSTMPLTPATH . '/css/kunena.skinner.css' )){
		CKunenaTools::addStyleSheet ( KUNENA_TMPLTURL . 'css/kunena.skinner-min.css', $rtl );
	} elseif (!$skinner && file_exists ( KUNENA_ABSTMPLTPATH . '/css/kunena.default.css' )) {
		CKunenaTools::addStyleSheet ( KUNENA_TMPLTURL . 'css/kunena.default-min.css', $rtl );
	}
} else {
	// Load css from default template
	CKunenaTools::addStyleSheet ( KUNENA_DIRECTURL . 'template/default/css/kunena.forum-min.css', $rtl );
	if ($skinner){
		CKunenaTools::addStyleSheet ( KUNENA_DIRECTURL . 'template/default/css/kunena.skinner-min.css', $rtl );
	} else {
		CKunenaTools::addStyleSheet ( KUNENA_DIRECTURL . 'template/default/css/kunena.default-min.css', $rtl );
	}
}
$imagesurl = JURI::base() . "components/com_kunena/template/ja_nex/images";
$cssurl = JURI::base() . "components/com_kunena/template/ja_nex/css";
?>

<!--[if IE]>
<link rel="stylesheet" href="<?php echo $cssurl; ?>/kunena.forum.ie.css" type="text/css" />
<![endif]-->

<!--[if lte IE 7]>
<link rel="stylesheet" href="<?php echo $cssurl; ?>/kunena.forum.ie7.css" type="text/css" />
<![endif]-->
<?php
$mediaurl = JURI::base() . "components/com_kunena/template/ja_nex/media";


