<?php

class RabbitMqPlugin extends Zend_Controller_Plugin_Abstract
{
    public function dispatchLoopShutdown()
    {
        if (Application_Model_RabbitMq::$doPush) {
            $md = array('schedule' => Application_Model_Schedule::GetScheduledPlaylists());
            Application_Model_RabbitMq::SendMessageToPypo("update_schedule", $md);
            if (!isset($_SERVER['AIRTIME_SRV'])){
                Application_Model_RabbitMq::SendMessageToShowRecorder("update_recorder_schedule");
            }
        }
    }
}
