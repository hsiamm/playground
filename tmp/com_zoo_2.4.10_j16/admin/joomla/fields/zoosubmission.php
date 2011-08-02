<?php
/**
* @package   com_zoo Component
* @file      zoosubmission.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

// load config
require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

class JFormFieldZooSubmission extends JFormField {

	protected $type = 'ZooSubmission';

	public function getInput() {

		// get app
		$app = App::getInstance('zoo');

		$app->html->_('behavior.modal', 'a.modal');
		$app->document->addStylesheet('joomla:elements/zoosubmission.css');
		$app->document->addScript('joomla:elements/zoosubmission.js');

		// init vars
		$params		= $app->parameter->create($this->form->getValue('params'));
		$table		= $app->table->application;
		$field_name = "{$this->formControl}[{$this->group}][%s]";

        $show_types = (string) $this->element->attributes()->types;

		// create application/category select
        $submissions = array();
		$types       = array();
		$app_options = array($app->html->_('select.option', '', '- '.JText::_('Select Application').' -'));
        
		foreach ($table->all(array('order' => 'name')) as $application) {
			// application option
			$app_options[$application->id] = $app->html->_('select.option', $application->id, $application->name);

            // create submission select
            $submission_options = array();
            foreach($application->getSubmissions() as $submission) {
                $submission_options[$submission->id] = $app->html->_('select.option', $submission->id, $submission->name);

                if ($show_types) {
                    $type_options = array();
                    $type_objects = $submission->getSubmittableTypes();
                    if (!count($type_objects)) {
                        unset($submission_options[$submission->id]);
                        continue;
                    }

                    foreach ($type_objects as $type) {
                        $type_options[] = $app->html->_('select.option', $type->id, $type->name);
                    }
					$type_name = sprintf($field_name, 'type');
                    $attribs = "class=\"type submission-{$submission->id} app-{$application->id}\" role=\"{$type_name}\"";
                    $types[] = $app->html->_('select.genericlist', $type_options, $type_name, $attribs, 'value', 'text', $params->get('type'));
                }
            }

            if (!count($submission_options)) {
                unset($app_options[$application->id]);
                continue;
            }

			$submission_name = sprintf($field_name, 'submission');
			$attribs = "class=\"submission app-{$application->id}\" role=\"{$submission_name}\"";
			$submissions[] = $app->html->_('select.genericlist', $submission_options, $submission_name, $attribs, 'value', 'text', $params->get('submission'));
		}


		// create html
		$html[] = '<div id="'.$this->fieldname.'" class="zoo-submission">';
		
		// create application html	
		$html[] = $app->html->_('select.genericlist', $app_options, sprintf($field_name, $this->fieldname), 'class="application"', 'value', 'text', $this->value);

		// create submission html
		$html[] = '<div class="submissions">'.implode("\n", $submissions).'</div>';

		// create types html
        if ($show_types) {
            $html[] = '<div class="types">'.implode("\n", $types).'</div>';
        }
		
		$html[] = '</div>';

		$javascript  = 'jQuery("#'.$this->fieldname.'").ZooSubmission();';
		$javascript  = "<script type=\"text/javascript\">\n// <!--\n$javascript\n// -->\n</script>\n";
		
		return implode("\n", $html).$javascript;
	}

}