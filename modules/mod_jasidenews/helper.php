<?php
/**
 * ------------------------------------------------------------------------
 * JA SideNews Module for Joomla 2.5
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_SITE . '/components/com_content/helpers/route.php';
JModel::addIncludePath(JPATH_SITE . '/components/com_content/models');
/**
 * modJASildeNews class.
 */
class modJASildeNewsHelper
{

    /**
     * @var string $condition;
     *
     * @access private
     */
    var $conditons = '';

    /**
     * @var string $order
     *
     * @access private
     */
    var $order = 'a.ordering';

    /**
     * @var string $limit
     *
     * @access private
     */
    var $limit = '';


    /**
     * get listing items from rss link or from list of categories.
     *
     * @param JParameter $params
     * @return array
     */
    function getList($params)
    {
        $rows = array();

        // check cache was endable ?
        if ($params->get('enable_cache')) {
            $cache = & JFactory::getCache();
            $cache->setCaching(true);
            $cache->setLifeTime($params->get('cache_time', 30) * 60);
            $rows = $cache->get(array($this, 'getArticles'), array($params));
        } else {
            $rows = $this->getArticles($params);
        }

        return $rows;
    }


    /**
     * get articles from list of categories and follow up owner paramer.
     *
     * @param JParameter $params.
     * @return array list of articles
     */
    function getArticles($params)
    {
        //$this->setOrder($params->get('sort_order_field' ,'created'), $params->get('sort_order','DESC'));
        //$this->setLimit( $params->get('max_items', 5) );
        $rows = $this->getListArticles($params);
        return $rows;
    }


    /**
     * get list articles follow setting configuration.
     *
     * @param JParameter $param
     * @return array
     */
    function getListArticles($params)
    {

        $db = JFactory::getDbo();

        // Get an instance of the generic articles model
        $model = JModel::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

        // Set application parameters in model
        $appParams = JFactory::getApplication()->getParams();
        $model->setState('params', $appParams);

        // Set the filters based on the module params
        $model->setState('list.start', 0);
        $model->setState('list.limit', $params->get('max_items', 5));

		if ($params->get('max_items', 5)==0) {
			$model->setState('filter.published', 10);
		}
		else {
			$model->setState('filter.published', 1);
		}
		
		$model->setState('list.select', 'a.fulltext, a.id, a.title, a.alias, a.title_alias, a.introtext, a.state, a.catid, a.created, a.created_by, a.created_by_alias,' .
			' a.modified, a.modified_by,a.publish_up, a.publish_down, a.attribs, a.metadata, a.metakey, a.metadesc, a.access,' .
			' a.hits, a.featured, a.ordering, ' .
			' LENGTH(a.fulltext) AS readmore');
		// Access filter
		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$model->setState('filter.access', $access);

        // Category filter
        if ($params->get("display_model", "modcats") == 'modcats') {
            $categories = $params->get('display_model-modcats-category', '');

            if ($categories && $categories[0] > 0)
                $model->setState('filter.category_id', $categories);
        } else {
            $model->setState('filter.featured', 'only');
        }

        if ($params->get('sort_order_field', 'created') == "random") {
            $model->setState('list.ordering', 'RAND()');
            $model->setState('list.direction', $params->get('sort_order', 'ASC'));
        } elseif ($params->get('sort_order_field', 'created') == "ordering") {
            $model->setState('list.ordering', 'a.ordering');
            $model->setState('list.direction', $params->get('sort_order', 'ASC'));
        } else {
            $model->setState('list.ordering', $params->get('sort_order_field', 'created'));
            $model->setState('list.direction', $params->get('sort_order', 'ASC'));
        }
        $featured = $params->get('show_featured', 1);
        if (!$featured) {
            $model->setState('filter.featured', 'hide');
        } elseif ($featured == 2) {
            $model->setState('filter.featured', 'only');
        }

        $items = $model->getItems();

        foreach ($items as &$item) {
            $item->slug = $item->id . ':' . $item->alias;
            $item->catslug = $item->catid . ':' . $item->category_alias;

            if ($access || in_array($item->access, $authorised)) {
                // We know that user has the privilege to view the article
                $item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
            } else {
                $item->link = JRoute::_('index.php?option=com_user&view=login');
            }

            $item->introtext = JHtml::_('content.prepare', $item->introtext);
        }
        return $items;
    }


    /**
     * get condition from setting configuration.
     *
     * @param JParameter $params
     * @return string.
     */
    function getCondition($params)
    {

        $condition = '';
        if ($params->get("display_model", "modcats") == 'modcats') {
            $categories = $params->get('display_model-modcats-category', '');

            if ($categories != '') {
                $ids = $this->getIds($categories);
                $condition = " AND cc.id IN($ids)";
            }
        } else {
            $condition = " AND  a.metakey LIKE '%Featured%' ";
        }
        return $condition;
    }


    /**
     * parser options, helper for clause where sql.
     *
     * @string array $options
     * @return string.
     */
    function getIds($options)
    {
        if (!is_array($options)) {
            return (int) $options;
        } else {
            return "'" . implode("','", $options) . "'";
        }
    }


    /**
     * add sort order sql
     *
     * @param string $order is article's field.
     * @param string $mode is DESC or ASC
     * @return .
     */
    function setOrder($order, $mode)
    {
        $this->order = ' a.' . $order . ' ' . $mode;
        return $this;
    }


    /**
     * add set limit sql
     *
     * @param integer $limit.
     * @return .
     */
    function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }


    /**
     * trim string with max specify
     *
     * @param string $title
     * @param integer $max.
     */
    function trimString($title, $max = 60)
    {

        if (strlen($title) > $max) {
            return substr($title, 0, $max) . '...';
        }
        return $title;
    }


    /**
     * detect and get link with each resource
     *
     * @param string $item
     * @param bool $useRSS.
     * @return string.
     */

    /**
     *
     * Render image from article
     * @param object $row
     * @param object $params
     * @param int $maxchars
     * @param int $width
     * @param int $height
     * @return string image
     */
    function renderImage(&$row, $params, $maxchars, $width = 0, $height = 0)
    {

        global $database, $_MAMBOTS, $current_charset;
        $image = "";
        $regex = "/\<img.+src\s*=\s*\"([^\"]*)\"[^\>]*\>/";
        preg_match($regex, $row->text, $matches);
        $align = ($tmp = $params->get("image_alignment", "left")) != "auto" ? 'align="' . $tmp . '"' : "";
        $images = (count($matches)) ? $matches : array();
        if (count($images))
            $image = $images[1];

        if ($image) {
            $thumbnailMode = $params->get('thumbnail_mode', 'crop');
            $aspect = $params->get('thumbnail_mode-resize-use_ratio', '1');
            $crop = $thumbnailMode == 'crop' ? true : false;
            $jaimage = JAImage::getInstance();

            if ($thumbnailMode != 'none' && $jaimage->sourceExited($image)) {
                $imageURL = $jaimage->resize($image, $width, $height, $crop, $aspect);
                if ($imageURL == $image) {
                    $width = $width ? "width=\"$width\"" : "";
                    $height = $height ? "height=\"$height\"" : "";
                    $image = "<img src=\"$imageURL\" $align  alt=\"{$row->title}\" title=\"{$row->title}\" $width $height />";
                } else {
                    $image = "<img src=\"$imageURL\"  $align  alt=\"{$row->title}\" title=\"{$row->title}\" />";
                }
            } else {
                $width = $width ? "width=\"$width\"" : "";
                $height = $height ? "height=\"$height\"" : "";
                $image = "<img src=\"$image\" alt=\"{$row->title}\" $align  title=\"{$row->title}\" $width $height />";
            }
        } else {
            $image = '';
        }

        $regex1 = "/\<img.*\/\>/";
        $row->introtext = preg_replace($regex1, '', $row->introtext);
        $row->introtext = trim($row->introtext);

        // clean up globals
        return $image;
    }
}
?>