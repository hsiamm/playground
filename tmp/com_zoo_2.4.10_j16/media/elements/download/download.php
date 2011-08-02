<?php
/**
* @package   com_zoo Component
* @file      download.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

/*
	Class: ElementDownload
		The file download element class
*/
class ElementDownload extends Element implements iSubmittable, iSubmissionUpload {

	/*
	   Function: Constructor
	*/
	public function __construct() {

		// call parent constructor
		parent::__construct();

		// set defaults
		$this->_config->set('secret', $this->app->system->config->getValue('config.secret'));

		$params = JComponentHelper::getParams('com_media');
		$this->_config->set('directory', $params->get('file_path'));

		// set callbacks
		$this->registerCallback('download');
		$this->registerCallback('reset');
	}

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

		return !empty($file) && is_readable($file) && is_file($file);
	}

	/*
		Function: getSize
			Gets the download file size.

		Returns:
			String - Download file with KB/MB suffix
	*/
	public function getSize() {
		return $this->app->filesystem->formatFilesize($this->_data->get('size', 0));
	}
	
	/*
		Function: getSize
			Gets the download file size.

		Returns:
			String - Download file with KB/MB suffix
	*/
	function isDownloadLimitReached() {

		$limit = $this->_data->get('download_limit');
		if ($limit && $this->_data->get('hits', 0) >= $limit) {
			return true;
		}
		return false;
	}
	
	/*
	   Function: getExtension
	       Get the file extension string.

	   Returns:
	       String - file extension
	*/
	public function getExtension() {
		return $this->app->filesystem->getExtension($this->_data->get('file'));
	}

	/*
		Function: getLink
			Gets the link to the download.

		Returns:
			String - link
	*/
	public function getLink() {
		
		// init vars
		$file		   = $this->_data->get('file');
		$download_mode = $this->_config->get('download_mode');

		// create download link
		$query = array('task' => 'callelement', 'format' => 'raw', 'item_id' => $this->_item->id, 'element' => $this->identifier, 'method' => 'download');

		if ($download_mode == 1) {
			return $this->app->link($query);
		} else if ($download_mode == 2) {
			$query['args[0]'] = $this->filecheck();
			return $this->app->link($query);
		} else {
			return $file;
		}

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
		$filename      = basename($this->_data->get('file'));
		$download_link = $this->getLink();
		$filetype      = str_replace('.','',$this->getExtension());
		$params		   = $this->app->data->create($params);
		$display       = $params->get('display', null);
		$download_name = $params->get('download_name', '');
		$download_name = $this->app->string->str_ireplace('{filename}', $filename, $download_name);

		// render layout
		if ($layout = $this->getLayout()) {
			return $this->renderLayout($layout,
				array(
					'file' => $this->_data->get('file'),
					'filename' => $filename, 'size' => $this->getSize(),
					'hits' => (int) $this->_data->get('hits', 0),
					'download_name' => $download_name,
					'download_link' => $download_link,
					'filetype' => $filetype,
					'display' => $display,
					'limit_reached' => $this->isDownloadLimitReached(),
					'download_limit' => $this->_data->get('download_limit')
				)
			);
		}
	}

	/*
		Function: download
			Download the file.

		Returns:
			Binary - File data
	*/
	public function download($check = '') {
		
		// init vars
		$filepath = JPATH_ROOT . '/' .$this->_data->get('file');
		$download_mode = $this->_config->get('download_mode');
		
		// check limit
		if ($this->isDownloadLimitReached()) {
			header('Content-Type: text/html');
			echo JText::_('Download limit reached!');
			return;
		}

		// trigger on download event
		$this->app->event->dispatcher->notify($this->app->event->create($this, 'element:download', compact('check')));

		// output file
		if ($download_mode == 1 && is_readable($filepath) && is_file($filepath)) {
			$this->_data->set('hits', $this->_data->get('hits', 0) + 1);
			$this->app->filesystem->output($filepath);
		} else if ($download_mode == 2 && $this->filecheck() == $check && is_readable($filepath) && is_file($filepath)) {
			$this->_data->set('hits', $this->_data->get('hits', 0) + 1);
			$this->app->filesystem->output($filepath);
		} else {
			header('Content-Type: text/html');
			echo JText::_('Invalid file!');
		}

		// save item
		$this->app->table->item->save($this->getItem());

	}

	/*
	   Function: filecheck
	       Get the file check string.

	   Returns:
	       String - md5(file + secret + date) 
	*/
	public function filecheck() {
		$secret = $this->_config->get('secret');
		return md5($this->_data->get('file').$secret.date('Y-m-d'));
	}

	/*
	   Function: edit
	       Renders the edit form field.

	   Returns:
	       String - html
	*/
	public function edit(){

		// init vars
		$file = $this->_data->get('file');
		$directory = $this->_config->get('directory');
		$directory = trim($directory, '\/') . '/';
		$trimmed_file = trim(str_replace('\\', '/', preg_replace('/^'.preg_quote($directory, '/').'/i', '', $file)), '/');

		$hide_path = empty($file) || preg_match('/^'.preg_quote($directory, '/').'/i', $file);

		// create info
		$info[] = JText::_('Size').': '.$this->getSize();
		$info[] = JText::_('Hits').': '.(int)$this->_data->get('hits', 0);
		$info   = ' ('.implode(', ', $info).')';

        if ($layout = $this->getLayout('edit.php')) {
            return $this->renderLayout($layout,
                array(
                    'element' => $this->identifier,
					'hide_path' => $hide_path,
                    'file' => $file,
                    'directory' => $directory,
                    'trimmed_file' => $trimmed_file,
					'info' => $info,
                    'hits' => $this->_data->get('hits', 0)
                )
            );
        }


	}

	/*
		Function: loadAssets
			Load elements css/js assets.

		Returns:
			Void
	*/
	public function loadAssets() {
		$this->app->document->addScript('elements:download/assets/js/download.js');
		return $this;
	}
	
	public function reset() {
		
		$this->_data->set('hits', 0);
		
		//save item
		$this->app->table->item->save($this->getItem());		
		
		return $this->edit();
	}	
	
	/*
		Function: bindData
			Set data through data array.

		Parameters:
			$data - array

		Returns:
			Void
	*/	
	public function bindData($data = array()) {
		
		if (!isset($data['hits'])) {
			$this->setData($this->getItem()->elements);
			$data['hits'] = $this->_data->get('hits', 0);
		}
		
		parent::bindData($data);
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

        // get params
        $params       = $this->app->data->create($params);
        $trusted_mode = $params->get('trusted_mode');

        // init vars
        $upload         = $this->_data->get('file');
        $download_limit = $this->_data->get('download_limit');

        if (empty($upload) && $trusted_mode) {
            $upload = $this->_data->get('upload');
        }

        // is uploaded file
        $upload        = is_array($upload) ? '' : $upload;

        // build upload select
        $lists = array();
        if ($trusted_mode) {
            $options = array($this->app->html->_('select.option', '', '- '.JText::_('Select File').' -'));
            if (!empty($upload) && !$this->_inUploadPath($upload)) {
                $options[] = $this->app->html->_('select.option', $upload, '- '.JText::_('No Change').' -');
            }
			foreach ($this->app->path->files('root:'.$this->_getUploadPath()) as $file) {
                $options[] = $this->app->html->_('select.option', $this->_getUploadPath().'/'.$file, basename($file));
            }
            $lists['upload_select'] = $this->app->html->_('select.genericlist', $options, 'elements['.$this->identifier.'][upload]', 'class="upload"', 'value', 'text', $upload);
        }

        if (!empty($upload)) {
            $upload = basename($upload);
        }

        if ($layout = $this->getLayout('submission.php')) {
            return $this->renderLayout($layout,
                array(
                    'element' => $this->identifier,
                    'lists' => $lists,
                    'upload' => $upload,
                    'trusted_mode' => $trusted_mode,
                    'download_limit' => $download_limit
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
        $element = new ElementDownload();
        $element->identifier = $this->identifier;
        $old_file = $element->setData($this->_item->elements)->getElementData()->get('file');

        $file = '';
        // get file from select list
        if ($trusted_mode && $file = $value->get('upload')) {

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

				// get legal extensions
				$extensions = explode(',', $this->_config->get('upload_extensions', 'png,jpg,doc,mp3,mov,avi,mpg,zip,rar,gz'));
				$extensions = array_map(create_function('$ext', 'return strtolower(trim($ext));'), $extensions);
				//get legal mime types
				$legal_mime_types = $this->app->data->create(array_intersect_key($this->app->filesystem->getMimeMapping(), array_flip($extensions)));
				$legal_mime_types = $legal_mime_types->flattenRecursive();

				// get max upload size
				$max_upload_size = $this->_config->get('max_upload_size', '512') * 1024;
				$max_upload_size = empty($max_upload_size) ? null : $max_upload_size;

				// validate
                $validator = $this->app->validator->create('file', array('mime_types' => $legal_mime_types, 'max_size' => $max_upload_size));
                $file = $validator->addMessage('mime_types', 'Uploaded file is not of a permitted type.')->clean($userfile);

            } catch (AppValidatorException $e) {
				
                if ($e->getCode() != UPLOAD_ERR_NO_FILE && $e->getCode() != 0) {
                    throw $e;
                }

                if (!$trusted_mode && $old_file && $value->get('upload')) {
                    $file = $old_file;
                }

            }

        }

        if ($params->get('required') && empty($file)) {
            throw new AppValidatorException('Please select a file to upload.');
        }

        $validator      = $this->app->validator->create('integer', array('required' => false), array('number' => 'The Download Limit needs to be a number.'));
        $download_limit = $validator->clean($value->get('download_limit'));
        $download_limit = empty($download_limit) ? '' : $download_limit;

		return array('file' => $file, 'download_limit' => $download_limit);
	}

    protected function _inUploadPath($image) {
        return $this->_getUploadPath() == dirname($image);
    }

    protected function _getUploadPath() {
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
            $base_path = JPATH_ROOT . '/' . $this->_getUploadPath() . '/';
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

class ElementDownloadData extends ElementData{

	public function encodeData() {		
	
		// add size to xml
		$filepath = JPATH_ROOT.'/'.$this->get('file');
		if (is_readable($filepath) && is_file($filepath)) {
			$this->set('size', sprintf('%u', filesize($filepath)));
		} else {
			$this->set('size', 0);
		}			
		
		return parent::encodeData();
		
	}		
	
}