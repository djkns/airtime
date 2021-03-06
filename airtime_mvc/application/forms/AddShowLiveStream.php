<?php
require_once 'customvalidators/ConditionalNotEmpty.php';

class Application_Form_AddShowLiveStream extends Zend_Form_SubForm
{

    public function init()
    {
        $cb_airtime_auth = new Zend_Form_Element_Checkbox("cb_airtime_auth");
        $cb_airtime_auth->setLabel("Use Airtime Authentication:")
                          ->setRequired(false)
                          ->setDecorators(array('ViewHelper'));
        $this->addElement($cb_airtime_auth);
        
        $cb_custom_auth = new Zend_Form_Element_Checkbox("cb_custom_auth");
        $cb_custom_auth  ->setLabel("Use Custom Authentication:")
                            ->setRequired(false)
                            ->setDecorators(array('ViewHelper'));
        $this->addElement($cb_custom_auth);
        
        //custom username
        $custom_username = new Zend_Form_Element_Text('custom_username');
        $custom_username->setAttrib('class', 'input_text')
                        ->setAttrib('autocomplete', 'off')
                        ->setAllowEmpty(true)
                        ->setLabel('Custom Username')
                        ->setFilters(array('StringTrim'))
                        ->setValidators(array(
                            new ConditionalNotEmpty(array("cb_custom_auth"=>"1"))))
                        ->setDecorators(array('ViewHelper'));
        $this->addElement($custom_username);
        
        //custom password
        $custom_password = new Zend_Form_Element_Password('custom_password');
        $custom_password->setAttrib('class', 'input_text')
                        ->setAttrib('autocomplete', 'off')
                        ->setAttrib('renderPassword','true')
                        ->setAllowEmpty(true)
                        ->setLabel('Custom Password')
                        ->setFilters(array('StringTrim'))
                        ->setValidators(array(
                            new ConditionalNotEmpty(array("cb_custom_auth"=>"1"))))
                        ->setDecorators(array('ViewHelper'));
        $this->addElement($custom_password);
        
        $connection_url = Application_Model_Preference::GetLiveDJSourceConnectionURL();
        if(trim($connection_url) == ""){
            $connection_url = "N/A";
        }
        
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'form/add-show-live-stream.phtml', "connection_url"=>$connection_url))
        ));
    }
    
    public function isValid($data){
        $isValid = parent::isValid($data);
        
        if($data['cb_custom_auth'] == 1){
            if(trim($data['custom_username']) == ''){
                $element = $this->getElement("custom_username");
                $element->addError("Username field cannot be empty.");
                $isValid = false;
            }
            if(trim($data['custom_password']) == ''){
                $element = $this->getElement("custom_password");
                $element->addError("Password field cannot be empty.");
                $isValid = false;
            }
        }
        return $isValid;
    }
}
