<?php

class Soloyo_Productupload_Model_Observer
{
    /**
     * process controller_action_predispatch event
     *
     * @return Soloyo_Productupload_Model_Observer
     */
    public function controllerActionPredispatch($observer)
    {
        $action = $observer->getEvent()->getControllerAction();
        return $this;
    }
}