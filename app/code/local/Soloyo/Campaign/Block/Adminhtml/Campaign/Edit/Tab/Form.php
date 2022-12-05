<?php

class Soloyo_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return Soloyo_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        if (Mage::getSingleton('adminhtml/session')->getCampaignData()) {
            $data = Mage::getSingleton('adminhtml/session')->getCampaignData();
            Mage::getSingleton('adminhtml/session')->setCampaignData(null);
        } elseif (Mage::registry('campaign_data')) {
            $data = Mage::registry('campaign_data')->getData();
        }

        $fieldset = $form->addFieldset('campaign_form', array(
            'legend'=>Mage::helper('campaign')->__('Item information')
        ));

        $fieldset->addField('name', 'text', array(
            'label'        => Mage::helper('campaign')->__('Name'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'name',
        ));

        $fieldset->addField('image_share', 'file', array(
            'label'        => Mage::helper('campaign')->__('Image share'),

            'name'        => 'image_share',
        ));

        $fieldset->addField('url', 'text', array(
            'label'        => Mage::helper('campaign')->__('Url share'),
            'name'        => 'url',
            'required'    => true,
        ));


        $fieldset->addField('banner', 'file', array(
            'label'        => Mage::helper('campaign')->__('Banner'),
            'required'    => false,
            'name'        => 'banner',
        ));


        $fieldset->addField('adward_text', 'editor', array(
            'name'        => 'adward_text',
            'label'        => Mage::helper('campaign')->__('Adward Text'),
            'title'        => Mage::helper('campaign')->__('Adward Text'),
            'style'        => 'width:700px; height:200px;',
            'wysiwyg'    => false,
            'required'    => true,
        ));


        $fieldset->addField('adward_image', 'file', array(
            'label'        => Mage::helper('campaign')->__('Adward image'),
            'required'    => false,
            'name'        => 'adward_image',
        ));


        $fieldset->addField('rule_win', 'editor', array(
            'name'        => 'rule_win',
            'label'        => Mage::helper('campaign')->__('Rule win'),
            'title'        => Mage::helper('campaign')->__('Rule win'),
            'style'        => 'width:700px; height:200px;',
            'wysiwyg'    => false,
            'required'    => true,
        ));

        $fieldset->addField('rule_win_other', 'editor', array(
            'name'        => 'rule_win_other',
            'label'        => Mage::helper('campaign')->__('Rule win other'),
            'title'        => Mage::helper('campaign')->__('Rule win other'),
            'style'        => 'width:700px; height:200px;',
            'wysiwyg'    => false,

        ));


        $fieldset->addField('other_title', 'text', array(
            'label'        => Mage::helper('campaign')->__('Other title'),
            'name'        => 'other_title',
        ));

        $fieldset->addField('other_text', 'editor', array(
            'name'        => 'other_text',
            'label'        => Mage::helper('campaign')->__('Other text'),
            'title'        => Mage::helper('campaign')->__('Other text'),
            'style'        => 'width:700px; height:200px;',
            'wysiwyg'    => false,

        ));

        $fieldset->addField('num_player', 'text', array(
            'label'        => Mage::helper('campaign')->__('Number player'),
            'name'        => 'num_player',
        ));


        $fieldset->addField('status', 'select', array(
            'label'        => Mage::helper('campaign')->__('Status'),
            'name'        => 'status',
            'values'    => $this->getOptionHash(),
        ));



        $form->setValues($data);
        return parent::_prepareForm();
    }

    public function getOptionHash()
    {
        $options = array();
        foreach ($this->getOptionArray() as $value => $label) {
            $options[] = array(
                'value'    => $value,
                'label'    => $label
            );
        }
        return $options;
    }

    public function getOptionArray()
    {
        return array(
            0   => 'Preparing',
            1   => 'Running',
            2 => 'Finish'
        );
    }




}