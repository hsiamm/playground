<?php
/**
* @package   com_zoo Component
* @file      submission.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
	Class: SubmissionEvent
		Submission events.
*/
class SubmissionEvent {

	public static function init($event) {

		$submission = $event->getSubject();

	}

	public static function saved($event) {

		$submission = $event->getSubject();

		if ($event['new'] || !$submission->isInTrustedMode()) {

			// send email to admins
			if ($recipients = $submission->getParams()->get('email_notification', '')) {
				$submission->app->submission->sendNotificationMail($event['item'], array_flip(explode(',', $recipients)), 'mail.submission.new.php');
			}

		}

	}

	public static function deleted($event) {

		$submission = $event->getSubject();

	}

}
