<?php
/**
 * ------------------------------------------------------------------------
 * JA Contenslider module for Joomla 1.7
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die();
require_once JPATH_SITE . '/components/com_content/helpers/route.php';
JModel::addIncludePath(JPATH_SITE . '/components/com_content/models');
/**
 *
 * JA Module Content Slider Helper Class
 * @author JoomlArt
 *
 */
class modJacontentsliderHelper
{


    /**
     * get instance of modJacontentsliderHelper
     * @return object
     */
    function getInstance()
    {
        static $__instance = null;
        if (!$__instance) {
            $__instance = new modJacontentsliderHelper();
        }
        return $__instance;
    }


    /**
     * get Total contents by category.
     *
     * @params object.
     * @return int
     */
    function getTotalContents($catid = 0)
    {
        // Get the dbo
        $db = JFactory::getDbo();

        // Get an instance of the generic articles model
        $model = JModel::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

        // Set application parameters in model
        $appParams = JFactory::getApplication()->getParams();
        $model->setState('params', $appParams);

		$model->setState(
			'list.select',
			'a.id, a.title, a.alias, a.title_alias, a.introtext, ' .
			'a.checked_out, a.checked_out_time, ' .
			'a.catid, a.created, a.created_by, a.created_by_alias, ' .
			// use created if modified is 0
			'CASE WHEN a.modified = 0 THEN a.created ELSE a.modified END as modified, ' .
				'a.modified_by,' .
			// use created if publish_up is 0
			'CASE WHEN a.publish_up = 0 THEN a.created ELSE a.publish_up END as publish_up, ' .
				'a.publish_down, a.attribs, a.metadata, a.metakey, a.metadesc, a.access, '.
				'a.hits, a.xreference, a.featured,'.' LENGTH(a.fulltext) AS readmore '
		);

		$model->setState('filter.published', 1);

        // Access filter
        $access = !JComponentHelper::getParams('com_content')->get('show_noauth');
        $authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
        $model->setState('filter.access', $access);

        // Category filter
        if ($catid) {
            if ($catid[0] != "") {
                $model->setState('filter.category_id', $catid);
            }
        }

        $items = $model->getItems();
        return count($items);
    }

    /**
     *
     * Create article link
     * @param object $item article
     * @return string article link
     */
    function articleLink($item)
    {
        $access = !JComponentHelper::getParams('com_content')->get('show_noauth');
        $authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
        $item->slug = $item->id . ':' . $item->alias;
        $item->catslug = $item->catid . ':' . $item->category_alias;

        if ($access || in_array($item->access, $authorised)) {
            // We know that user has the privilege to view the article
            $item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
        } else {
            $item->link = JRoute::_('index.php?option=com_user&view=login');
        }

        return $item->link;
    }

    /**
     *
     * Get List Item to display in Slider
     * @param array $catid array categories article or K2
     * @param object $params
     * @param string $source the source data
     * @return array list items
     */
    function getListItems($catid, $params, $source = 'content')
    {
        $helper = new modJacontentsliderHelper();
        if ($source == 'folder') {
            return $helper->getListFolder($params);
        } else {
            $callMethod = 'getList' . ucfirst($source);
            if (method_exists($helper, $callMethod)) {
                return $helper->$callMethod($catid, $params);
            }
        }
        return array();
    }


    /**
     *
     * Get list K2 items
     * @param string $catid categories id
     * @param object $params
     * @return array
     */
    function getListK2($catids, $params)
    {
        if (file_exists(JPATH_SITE . DS . 'components' . DS . 'com_k2' . DS . 'helpers' . DS . 'route.php')) {
			require_once (JPATH_BASE . DS . "components" . DS . "com_k2" . DS . "helpers" . DS . "route.php");
		}
        $db = &JFactory::getDBO();
        $my = &JFactory::getUser();
        $date = &JFactory::getDate();
        $now = $date->toMySQL();

        $user = &JFactory::getUser();
        $aid = $user->get('aid') ? $user->get('aid') : 1;
        $jnow = &JFactory::getDate();
        $now = $jnow->toMySQL();
        $nullDate = $db->getNullDate();

        $sql = array();

        if (!empty($catids)) {
            $catids_new = $catids;
            foreach ($catids as $k => $catid) {
                $subcatids = modJacontentsliderHelper::getK2CategoryChildren($catid, true);
                if ($subcatids) {
                    $catids_new = array_merge($catids_new, array_diff($subcatids, $catids_new));
                }
            }
        }
        $query = array();
        if(!empty($catids_new)){
            foreach($catids_new as $catidsnew){
        		$select = 'SELECT items.*, cate.name AS cateName';
        		$from = ' FROM #__k2_categories as cate INNER JOIN #__k2_items as items ON cate.id = items.catid';
        		$where = ' WHERE cate.published = 1 AND items.published = 1';

        		$where .= " AND items.access <= {$aid}"
        		." AND items.trash = 0"
        		." AND cate.access <= {$aid}"
        		." AND cate.trash = 0";

        		$where .= " AND ( items.publish_up = ".$db->Quote($nullDate)." OR items.publish_up <= ".$db->Quote($now)." )";
        		$where .= " AND ( items.publish_down = ".$db->Quote($nullDate)." OR items.publish_down >= ".$db->Quote($now)." )";
        		if($catids_new) {
        			$where .= ' AND items.catid = '.intval($catidsnew).' ';
        		}

        		// order by
        		$order = $params->get('sort_order_field', 'created');
        		$orderDir = $params->get('sort_order', 'DESC');
        		switch ($order) {
        			case 'created':
        				$orderBy = " items.created {$orderDir}";
        				break;
        			case 'ordering':
        				$orderBy = " items.ordering {$orderDir}";
        				break;
        			case 'hits':
        				$orderBy = " items.hits {$orderDir}";
        				break;
        			default:
        				$orderBy = " RAND() ";
        				break;
        		}

        		$sqlTmp = $select . $from . $where . ' ORDER BY ' . $orderBy;
        		$sqlTmp .= ' LIMIT 0,' . $params->get('maxitems', 10);

                $query[] = '('.trim($sqlTmp).')';
            }
        }

        $query = implode(" UNION ", $query);
        $db->setQuery($query);
        $data = $db->loadObjectlist();
		if (empty($data)){
			$data = array();
		}
        foreach ($data as $i => $row) {

            $data[$i]->id = $row->id;
            $data[$i]->text = $data[$i]->introtext;
            $data[$i]->title = $data[$i]->title;
            $data[$i]->introtext = $row->introtext;
            $data[$i]->catid = $row->catid;
            $data[$i]->cateName = $row->cateName;
            $data[$i]->link = K2HelperRoute::getItemRoute($row->id, $row->catid);

            $data[$i]->featured = $row->featured;
            // Get rating data from K2 Components
            $sqlRating = "SELECT * FROM #__k2_rating WHERE itemID = '" . intval($data[$i]->id) . "'  ";
            $db->setQuery($sqlRating);
            $rating = $db->loadRow();
            $data[$i]->rating = $rating;
            $image = modJacontentsliderHelper::parseImages($data[$i], $params, 'k2');
            if ($image) {
                $data[$i]->image = modJacontentsliderHelper::renderImage($row->title, $data[$i]->link, $image, $params, $params->get('iwidth'), $params->get('iheight'));
            } else {
                $data[$i]->image = '';
            }
            $data[$i] = modJacontentsliderHelper::processIntrotext($data[$i], $params->get( 'numchar', 0 ));
        }
        return $data;
    }


	/**
     *
     * Get K2 category children
     * @param int $catid
     * @param boolean $clear if true return array which is removed value construction
     * @return array
     */
    function getK2CategoryChildren($catid, $clear = false) {

		static $array = array();
		if ($clear)
		$array = array();
		$user = &JFactory::getUser();
		$aid = $user->get('aid') ? $user->get('aid') : 1;
		$catid = (int) $catid;
		$db = &JFactory::getDBO();
		$query = "SELECT * FROM #__k2_categories WHERE parent={$catid} AND published=1 AND trash=0 AND access<={$aid} ORDER BY ordering ";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		if (empty($rows)){
			$rows=array();
		}
		foreach ($rows as $row) {
			array_push($array, $row->id);
			if (modJacontentsliderHelper::hasK2Children($row->id)) {
				modJacontentsliderHelper::getK2CategoryChildren($row->id);
			}
		}
		return $array;
	}


	/**
	 *
	 * Check category has children
	 * @param int $id
	 * @return boolean
	 */
	function hasK2Children($id) {

		$user = &JFactory::getUser();
		$aid = $user->get('aid') ? $user->get('aid') : 1;
		$id = (int) $id;
		$db = &JFactory::getDBO();
		$query = "SELECT * FROM #__k2_categories WHERE parent={$id} AND published=1 AND trash=0 AND access<={$aid} ";
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		if (count($rows)) {
			return true;
		} else {
			return false;
		}
	}


    /**
     *
     * Get List Articles
     * @param array $catid
     * @param object $params
     * @return array list articles
     */
    function getListContent($catid, $params)
    {
        $mainframe = JFactory::getApplication();

        // Get the dbo
        $db = JFactory::getDbo();

        // Get an instance of the generic articles model
        $model = JModel::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
        /* cc.description as catdesc, cc.title as cattitle */
        // Set application parameters in model
        $appParams = JFactory::getApplication()->getParams();
        $model->setState('params', $appParams);

		$model->setState(
			'list.select',
			'a.id, a.title, a.alias, a.title_alias, a.introtext, ' .
			'a.checked_out, a.checked_out_time, ' .
			'a.catid, a.created, a.created_by, a.created_by_alias, ' .
			// use created if modified is 0
			'CASE WHEN a.modified = 0 THEN a.created ELSE a.modified END as modified, ' .
				'a.modified_by,' .
			// use created if publish_up is 0
			'CASE WHEN a.publish_up = 0 THEN a.created ELSE a.publish_up END as publish_up, ' .
				'a.publish_down, a.attribs, a.metadata, a.metakey, a.metadesc, a.access, '.
				'a.hits, a.xreference, a.featured,'.' LENGTH(a.fulltext) AS readmore '
		);

     // Set the filters based on the module params
        $model->setState('list.start', 0);
        //if($limit>0) {
       // $model->setState('list.limit', $params->get('maxitems', 10));
        //}


        $model->setState('filter.published', 1);

        $access = !JComponentHelper::getParams('com_content')->get('show_noauth');
        $authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
        $model->setState('filter.access', $access);

        // Category filter

		/**
        JArrayHelper::toInteger($catid);
		if (count($catid)>0 && $catid[0]>0) {
             $model->setState('filter.category_id', $catid);
        }
		*/
        if ($params->get('sort_order_field', 'created') == "ordering") {
            $model->setState('list.ordering', 'a.ordering');
        } else {
            $model->setState('list.ordering', $params->get('sort_order_field', 'created'));
        }

        $model->setState('list.direction', $params->get('sort_order', 'DESC'));

        // Category filter
        $data = array();

        if (!empty($catid) && intval($catid[0]) > 0) {
            for($i=0; $i<count($catid); $i++){
                if(intval($catid[$i]) > 0){
                    $model->setState('filter.category_id', $catid[$i]);
                    $model->setState('list.limit', $params->get('maxitems', 10));
                    $data = array_merge($data, $model->getItems());
                }
            }
        }else{
            //$model->setState('list.limit', $params->get('maxitems', 10));
            $data = array_merge($data, $model->getItems());
        }

        $thumbnailMode = $params->get('source-articles-images-thumbnail_mode', 'crop');
        $aspect = $params->get('source-articles-images-thumbnail_mode-resize-use_ratio', '1');
        $crop = $thumbnailMode == 'crop' ? true : false;
        $jaimage = JAImage::getInstance();
        foreach ($data as $i => $row) {
            $data[$i]->text = $data[$i]->introtext;
            $mainframe->triggerEvent('onPrepareContent', array(&$data[$i], &$params, 0), true);
            $data[$i]->introtext = $data[$i]->text;
            $data[$i]->catid = $row->catid;
            $data[$i]->title = $row->title;
            $data[$i]->cateName = $row->category_title;
            $data[$i]->link = modJacontentsliderHelper::articleLink($row);

            $image = modJacontentsliderHelper::parseImages($data[$i], $params);
            if ($image) {
                $data[$i]->image = modJacontentsliderHelper::renderImage($row->title, $data[$i]->link, $image, $params, $params->get('iwidth'), $params->get('iheight'));
            } else {
                $data[$i]->image = '';
            }
            $data[$i] = modJacontentsliderHelper::processIntrotext($data[$i], $params->get( 'numchar', 0 ));
        }
        return $data;
    }

    /**
     *
     * Get List Images in Folder
     * @param object $params
     * @return array list images
     */
    function getListFolder($params)
    {
        $folder = $params->get('folder_images');
		$path = JPath::clean(JPATH_ROOT . DS .$folder);

        $data = array();
        if (JFolder::exists($path)) {
            $files = JFolder::files($path, "\.(jpg|png|gif|jpeg|bmp)$");
            $i = 0;

            foreach ($files as $file) {
                $image = JURI::root() . JPath::clean( $folder . '/' . $file, '/');
                $item = new stdClass();
                $item->text = '';
                $item->introtext = $item->text;
                $item->catid = 1;
                $item->title = $file;
                $item->cateName = '';
                $item->link = $image;
                $item->image = modJacontentsliderHelper::renderImage($item->title, $item->link, $image, $params, $params->get('iwidth'), $params->get('iheight'));

                $data[$i] = $item;
                $data[$i] = modJacontentsliderHelper::processIntrotext($data[$i], $params->get( 'numchar', 0 ));
                $i++;
            }
        }

        return $data;
    }


    /**
     * parser a image in the content.
     * @param object $row object content
     * @param object $params
     * @return string image
     */
    function parseImages(&$row, $params,$context = 'content')
    {
	    $arrImages = $this->getK2Images($row, $context);
		if(!empty($arrImages)){
		    
		    return $arrImages['imageGeneric'];
		}else{
			$text = $row->introtext . $row->text;
			$regex = "/\<img.+?src\s*=\s*[\"|\']([^\"]*)[\"|\'][^\>]*\>/";
			preg_match($regex, $text, $matches);
			$images = (count($matches)) ? $matches : array();
			if (count($images)) {
				return $images[1];
			}
        }
        return;
    }
      /**
     *
     * Get image in k2 item
     * @param object $item
     * @param string $context
     * @return array
     */
    function getK2Images($item, $context = 'content')
    {
        jimport('joomla.filesystem.file');
        //Image
        $arr_return = array();

        if ($context == 'k2') {
            if (JFile::exists(JPATH_SITE . DS . 'media' . DS . 'k2' . DS . 'items' . DS . 'cache' . DS . md5("Image" . $item->id) . '_XS.jpg'))
                $arr_return['imageXSmall'] = JURI::root() . 'media/k2/items/cache/' . md5("Image" . $item->id) . '_XS.jpg';

            if (JFile::exists(JPATH_SITE . DS . 'media' . DS . 'k2' . DS . 'items' . DS . 'cache' . DS . md5("Image" . $item->id) . '_S.jpg'))
                $arr_return['imageSmall'] = JURI::root() . 'media/k2/items/cache/' . md5("Image" . $item->id) . '_S.jpg';

            if (JFile::exists(JPATH_SITE . DS . 'media' . DS . 'k2' . DS . 'items' . DS . 'cache' . DS . md5("Image" . $item->id) . '_M.jpg'))
                $arr_return['imageMedium'] = JURI::root() . 'media/k2/items/cache/' . md5("Image" . $item->id) . '_M.jpg';

            if (JFile::exists(JPATH_SITE . DS . 'media' . DS . 'k2' . DS . 'items' . DS . 'cache' . DS . md5("Image" . $item->id) . '_L.jpg'))
                $arr_return['imageLarge'] = JURI::root() . 'media/k2/items/cache/' . md5("Image" . $item->id) . '_L.jpg';

            if (JFile::exists(JPATH_SITE . DS . 'media' . DS . 'k2' . DS . 'items' . DS . 'cache' . DS . md5("Image" . $item->id) . '_XL.jpg'))
                $arr_return['imageXLarge'] = JURI::root() . 'media/k2/items/cache/' . md5("Image" . $item->id) . '_XL.jpg';

            if (JFile::exists(JPATH_SITE . DS . 'media' . DS . 'k2' . DS . 'items' . DS . 'cache' . DS . md5("Image" . $item->id) . '_Generic.jpg'))
                $arr_return['imageGeneric'] = JURI::root() . 'media/k2/items/cache/' . md5("Image" . $item->id) . '_Generic.jpg';
        } else {
            //com content
        }

        return $arr_return;
    }
    /**
     *
     * Render image before display it
     * @param string $title
     * @param string $link
     * @param string $image
     * @param object $params
     * @param int $width
     * @param int $height
     * @param string $attrs
     * @param string $returnURL
     * @return string image
     */
    function renderImage($title, $link, $image, $params, $width = 0, $height = 0, $attrs = '', $returnURL = false)
    {
        global $database, $_MAMBOTS, $current_charset;
        if ($image) {
            $title = strip_tags($title);
            $thumbnailMode = $params->get('source-articles-images-thumbnail_mode', 'crop');
            $aspect = $params->get('source-articles-images-thumbnail_mode-resize-use_ratio', '1');
            $crop = $thumbnailMode == 'crop' ? true : false;
            $jaimage = JAImage::getInstance();
            if ($thumbnailMode != 'none' && $jaimage->sourceExited($image)) {
                $imageURL = $jaimage->resize($image, $width, $height, $crop, $aspect);
                if ($returnURL) {
                    return $imageURL;
                }
                if ($imageURL != $image && $imageURL) {
                    $width = $width ? "width=\"$width\"" : "";
                    $height = $height ? "height=\"$height\"" : "";
                    $image = "<img src=\"$imageURL\"  alt=\"{$title}\" title=\"{$title}\" $width $height $attrs />";
                } else {
                    $image = "<img $attrs src=\"$image\"  $attrs  alt=\"{$title}\" title=\"{$title}\" />";
                }
            } else {
                if ($returnURL) {
                    return $image;
                }
                $width = $width ? "width=\"$width\"" : "";
                $height = $height ? "height=\"$height\"" : "";
                $image = "<img $attrs src=\"$image\" alt=\"{$title}\"   title=\"{$title}\" $width $height />";
            }
        } else {
            $image = '';
        }
        $image = '<a href="' . $link . '" title="" class="ja-image">' . $image . '</a>';
        // clean up globals
        return $image;
    }


    /**
     * Process introtext
     * @param string $introtext
     * @param int $maxchars
     * @return string
     */
    function processIntrotext(&$row, $maxchars)
    {
        // expression to search for
        $row->introtext1 = strip_tags($row->introtext);
        if ($maxchars && strlen($row->introtext1) > $maxchars) {
            $row->introtext1 = substr($row->introtext1, 0, $maxchars) . "...";
        }
        if(trim($maxchars) == '-1'){
            $row->introtext1 = strip_tags($row->introtext);
        }
        if(trim($maxchars) == '0' || trim($maxchars) == ''){
            $row->introtext1 = '';
            $row->introtext = '';
        }
        // clean up globals
        return $row;
    }

    function replaceImage(&$row, $maxchars, $showimage, $width = 0, $height = 0)
    {
        // expression to search for


        $row->introtext1 = strip_tags($row->introtext);
        if ($maxchars && strlen($row->introtext) > $maxchars) {
            $row->introtext1 = substr($row->introtext1, 0, $maxchars) . "...";
        }
        // clean up globals
        return $row->image;
    }

    /**
     *
     */
    function processImage(&$row, $width, $height)
    {
        /* for 1.5 - don't need to use image parameter */
        return 0;
        /* End 1.5 */
    }


    /**
     * load javascript files: processing override js, load js compress or not.
     */
    function javascript($params)
    {

        $document = & JFactory::getDocument();
        $document->addScript(JURI::base() . 'modules/mod_jacontentslider/assets/js/ja_contentslider.js');
    }


    /**
     * load css files: processing override css
     */
    function css($params)
    {

        $mainframe = JFactory::getApplication();

        $document = & JFactory::getDocument();

        $cssFile = 'mod_jacontentslider.css';

        if (file_exists(JPATH_SITE . DS . 'templates' . DS . $mainframe->getTemplate() . DS . 'css' . DS . $cssFile)) {
            $document->addStyleSheet(JURI::base() . 'templates/' . $mainframe->getTemplate() . '/css/' . $cssFile);
        } else {
            $document->addStyleSheet(JURI::base() . 'modules/mod_jacontentslider/assets/css/style.css');
        }
    }


    /**
     * check overrider layout.
     */
    function getLayoutPath($module, $layout = 'default')
    {

        $mainframe = JFactory::getApplication();

        // Build the template and base path for the layout
        $tPath = JPATH_BASE . DS . 'templates' . DS . $mainframe->getTemplate() . DS . 'html' . DS . $module . DS . $layout . '.php';
        $bPath = JPATH_BASE . DS . 'modules' . DS . $module . DS . 'tmpl' . DS . $layout . '.php';

        // If the template has a layout override use it
        if (file_exists($tPath)) {
            return $tPath;
        } else {
            return $bPath;
        }
    }
}
?>