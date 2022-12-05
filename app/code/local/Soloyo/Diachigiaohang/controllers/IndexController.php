<?php

class Soloyo_Diachigiaohang_IndexController extends Mage_Core_Controller_Front_Action
{

//    public function disable_lastnameAction(){
//        $installer = new Mage_Customer_Model_Entity_Setup('core_setup');
//        $installer->startSetup();
//        $installer->updateAttribute('customer_address', 'company', 'is_required', 1);
//
//        $installer->endSetup();
//    }






    public function disable_lastnameAction()
    {
        $resource = Mage::getSingleton('core/resource');
        $writeConnection = $resource->getConnection('core_write');
        $query = "UPDATE eav_attribute SET is_required = 0 WHERE attribute_code = 'lastname';UPDATE customer_eav_attribute SET
validate_rules = 'a:1:{s:15:\"max_text_length\";i:255;}'
WHERE customer_eav_attribute.attribute_id = 7;UPDATE customer_eav_attribute SET
validate_rules = 'a:1:{s:15:\"max_text_length\";i:255;}'
WHERE customer_eav_attribute.attribute_id = 22;";


        $writeConnection->query($query);
    }

    public  function getAllAction(){
        $data = Mage::helper('diachigiaohang')->getAllDiachigiaohang();
        $this->getResponse()->setHeader('Content-type', 'application/json');
        return $this->getResponse()->setBody(json_encode($data));
    }

}