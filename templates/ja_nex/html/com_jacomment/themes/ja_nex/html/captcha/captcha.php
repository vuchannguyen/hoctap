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
defined('_JEXEC') or die('Restricted access');

$helper = new JACommentHelpers();

$captcha->valid_text = false;
$captcha->draw_lines = false;
$captcha->draw_lines_over_text = false;
$captcha->arc_linethrough = false;
$captcha->arcline_colors = "#8080ff,#cccfff,#000fff,#fffccc";
$captcha->text_color = "#ff0000";
$captcha->usepatern = false;
// use P=point,L=line,C=circle,E=elipse,CF=fillcircle,EF=fillelipse;T=text
$captcha->paternType = "p,l,c,e,cf,ef,t";
$captcha->paternRandColor = 0;
$captcha->use_multi_text = true;
$captcha->multi_text_color = "#222222,#222222,#222222";
$captcha->image_bg_color = "#FFFFFF";
$captcha->xpos_start = 4;
$captcha->font_size = 35;
$captcha->text_entered;
$captcha->text_angle_minimum = - 20;
$captcha->text_angle_maximum = 20;
$captcha->text_minimum_space = 30;
$captcha->text_maximum_space = 30;
$captcha->use_transparent_text = true;
$captcha->text_transparency_percentage = 30;
$captcha->line_color = "#222222";
$captcha->line_space = 5;
$captcha->line_thickness = 1;
$captcha->draw_angled_lines = false;
$captcha->im;
$captcha->ttf_file = $helper->getFontOfCaptcha();
$captcha->bgimg;
$captcha->text_length = 5;
$captcha->charset = 'ABCDEFGHKLMNPRSTUVWYZ23456789';
$captcha->imgwidth = 200;
$captcha->imgheight = 80;
?>