<?php
/**
* @package   com_zoo Component
* @file      image.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

/*
	Class: ElementImage
		The image element class
*/
class ElementImage extends Element implements iSubmittable, iSubmissionUpload {

	/*
		Function: hasValue
			Checks if the element's value is set.

	   Parameters:
			$params - render parameter

		Returns:
			Boolean - true, on success
	*/
	public function hasValue($params = array()) {

		// init vars
		$file = $this->_data->get('file');

		return !empty($file);
	}

	/*
		Function: getSearchData
			Get elements search data.

		Returns:
			String - Search data
	*/
	public function getSearchData() {
		if ($this->_config->get('custom_title')) {
			return $this->_data->get('title');
		}
		return null;
	}

	/*
		Function: render
			Renders the element.

	   Parameters:
            $params - render parameter

		Returns:
			String - html
	*/
	public function render($params = array()) {

		// init vars
		$title  	  = $this->_data->get('title');
		$params		  = $this->app->data->create($params);
		$file  		  = $this->app->zoo->resizeImage(JPATH_ROOT.DS.$this->_data->get('file'), $params->get('width', 0), $params->get('height', 0));
		$link   	  = JURI::root() . trim(str_replace('\\', '/', preg_replace('/^'.preg_quote(JPATH_ROOT, '/').'/i', '', $file)), '/');

		if ($params->get('link_to_item', false)) {

            if ($this->getItem()->getState()) {
                $url	   = $this->app->route->item($this->_item);
                $target	   = false;
                $rel  	   = '';
                $title 	   = empty($title) ? $this->_item->name : $title;
            } else {

                $url = $target = $rel = '';

            }

		} else if ($this->_data->get('link')) {

			$url 	= $this->_data->get('link');
			$target	= $this->_data->get('target');
			$rel  	= $this->_data->get('rel');

		} else if ($this->_data->get('lightbox_image')) {

			// load lightbox
			if ($this->_config->get('load_lightbox', 0)) {
				$this->app->document->addScript('elements:gallery/assets/lightbox/slimbox.js');
				$this->app->document->addStylesheet('elements:gallery/assets/lightbox/css/slimbox.css');
			}

			$lightbox_image = $this->app->zoo->resizeImage(JPATH_ROOT.DS.$this->_data->get('lightbox_image', ''), 0 , 0);
			$url		    = JURI::root() . trim(str_replace('\\', '/', preg_replace('/^'.preg_quote(JPATH_ROOT, '/').'/i', '', $lightbox_image)), '/');
			$target	= '';
			$rel  	= 'lightbox['.$title.']';

		} else {

			$url = $target = $rel = '';

		}

		// get alt
		$alt = empty($title) ? $this->_item->name : $title;

		// render layout
		if ($layout = $this->getLayout()) {
			return $this->renderLayout($layout,
				array(
					'file' => $file,
					'title' => $title,
					'alt' => $alt,
					'link' => $link,
					'link_enabled' => !empty($url),
					'url' => $url,
					'target' => $target,
					'rel' => $rel
				)
			);
		}

		return null;
	}

	/*
	   Function: edit
	       Renders the edit form field.

	   Returns:
	       String - html
	*/
	public function edit() {

		$this->app->document->addScript('assets:js/image.js');

        if ($layout = $this->getLayout('edit.php')) {
            return $this->renderLayout($layout,
                array(
                    'element' => $this->identifier,
                    'file' => $this->_data->get('file'),
                    'title' => $this->_data->get('title'),
                    'link' => $this->_data->get('link'),
                    'target' => $this->_data->get('target'),
                    'rel' => $this->_data->get('rel'),
					'lightbox_image' => $this->_data->get('lightbox_image')
                )
            );
        }

	}

	/*
		Function: renderSubmission
			Renders the element in submission.

	   Parameters:
            $params - submission parameters

		Returns:
			String - html
	*/
	public function renderSubmission($params = array()) {

		// load js
		$this->app->document->addScript('elements:image/assets/js/image.js');

        // init vars
        $image        = $this->_data->get('file');

        // is uploaded file
        $image        = is_array($image) ? '' : $image;

        // get params
        $params       = $this->app->data->create($params);
        $trusted_mode = $params->get('trusted_mode');

        // build image select
        $lists = array();
        if ($trusted_mode) {
            $options = array($this->app->html->_('select.option', '', '- '.JText::_('Select Image').' -'));
            if (!empty($image) && !$this->_inUploadPath($image)) {
                $options[] = $this->app->html->_('select.option', $image, '- '.JText::_('No Change').' -');
            }
            $img_ext = str_replace(',', '|', trim(JComponentHelper::getParams('com_media')->get('image_extensions'), ','));
			foreach ($this->app->path->files('root:'.$this->_getUploadImagePath(), false, '/\.('.$img_ext.')$/i') as $file) {
                $options[] = $this->app->html->_('select.option', $this->_getUploadImagePath().'/'.$file, $file);
            }
            $lists['image_select'] = $this->app->html->_('select.genericlist', $options, 'elements['.$this->identifier.'][image]', 'class="image"', 'value', 'text', $image);
        } else {
            if (!empty($image)) {
                $image = $this->app->zoo->resizeImage($this->app->path->path('root:' . $image), 0, 0);
                $image = trim(str_replace('\\', '/', preg_replace('/^'.preg_quote(JPATH_ROOT, '/').'/i', '', $image)), '/');
            }
        }

        if (!empty($image)) {
            $image = $this->app->path->url('root:' . $image);
        }

        if ($layout = $this->getLayout('submission.php')) {
            return $this->renderLayout($layout,
				array(
					'lists' => $lists,
					'image' => $image,
					'trusted_mode' => $trusted_mode,
					'title' => $this->_data->get('title'),
                    'link' => $this->_data->get('link'),
                    'target' => $this->_data->get('target'),
                    'rel' => $this->_data->get('rel')
				)
			);
        }

	}

	/*
		Function: validateSubmission
			Validates the submitted element

	   Parameters:
            $value  - AppData value
            $params - AppData submission parameters

		Returns:
			Array - cleaned value
	*/
	public function validateSubmission($value, $params) {

        // init vars
        $trusted_mode = $params->get('trusted_mode');

        // get old file value
        $element = new ElementImage();
        $element->identifier = $this->identifier;
        $old_file = $element->setData($this->_item->elements)->getElementData()->get('file');

        $file = '';
        // get file from select list
        if ($trusted_mode && $file = $value->get('image')) {

            if (!$this->_inUploadPath($file) && $file != $old_file) {
                throw new AppValidatorException(sprintf('This file is not located in the upload directory.'));
            }

            if (!JFile::exists($file)) {
                throw new AppValidatorException(sprintf('This file does not exist.'));
            }

        // get file from upload
        } else {

            try {

                // get the uploaded file information
                $userfile = $this->app->request->getVar('elements_'.$this->identifier, array(), 'files', 'array');

				$max_upload_size = $this->_config->get('max_upload_size', '512') * 1024;
				$max_upload_size = empty($max_upload_size) ? null : $max_upload_size;
                $validator = $this->app->validator->create('file', array('mime_type_group' => 'image', 'max_size' => $max_upload_size));
                $file = $validator->addMessage('mime_type_group', 'Uploaded file is not an image.')->clean($userfile);

            } catch (AppValidatorException $e) {
                if ($e->getCode() != UPLOAD_ERR_NO_FILE && $e->getCode() != 0) {
                    throw $e;
                }

                if (!$trusted_mode && $old_file && $value->get('image')) {
                    $file = $old_file;
                }

            }

        }

        if ($params->get('required') && empty($file)) {
            throw new AppValidatorException('Please select an image to upload.');
        }

		$result = compact('file');

		if ($trusted_mode) {
			$result['title'] = $this->app->validator->create('string', array('required' => false))->clean($value->get('title'));
			$result['link'] = $this->app->validator->create('url', array('required' => false), array('required' => 'Please enter an URL.'))->clean($value->get('link'));
			$result['target'] = $this->app->validator->create('', array('required' => false))->clean($value->get('target'));
			$result['rel'] = $this->app->validator->create('string', array('required' => false))->clean($value->get('rel'));
		}

		return $result;
	}

    protected function _inUploadPath($image) {
        return $this->_getUploadImagePath() == dirname($image);
    }

    protected function _getUploadImagePath() {
		return trim(trim($this->_config->get('upload_directory', 'images/stories/zoo/uploads/')), '\/');
    }

	/*
		Function: doUpload
			Does the actual upload during submission

		Returns:
			void
	*/
    public function doUpload() {

        // get the uploaded file information
        $userfile = $this->_data->get('file');

        if (is_array($userfile)) {
            // get file name
            $ext = $this->app->filesystem->getExtension($userfile['name']);
            $base_path = JPATH_ROOT . '/' . $this->_getUploadImagePath() . '/';
            $file = $tmp = $base_path . $userfile['name'];
            $filename = basename($file, '.'.$ext);

            $i = 1;
            while (JFile::exists($tmp)) {
                $tmp = $base_path . $filename . '-' . $i++ . '.' . $ext;
            }
            $file = trim(str_replace('\\', '/', preg_replace('/^'.preg_quote(JPATH_ROOT, '/').'/i', '', $tmp)), '/');

            if (!JFile::upload($userfile['tmp_name'], $file)) {
                throw new AppException('Unable to upload file.');
            }

            $this->_data->set('file', $file);
        }
    }

}

class ElementImageData extends ElementData{

	public function encodeData() {

		// add image width/height
		$filepath = JPATH_ROOT.DS.$this->_data->get('file');

		if (JFile::exists($filepath)) {
			$size = getimagesize($filepath);
			$this->set('width', ($size ? $size[0] : 0));
			$this->set('height', ($size ? $size[1] : 0));
		}

		return parent::encodeData();
	}

}