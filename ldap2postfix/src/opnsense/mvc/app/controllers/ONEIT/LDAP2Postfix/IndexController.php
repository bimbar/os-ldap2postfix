<?php
namespace ONEIT\LDAP2Postfix;
class IndexController extends \OPNsense\Base\IndexController
{
    public function indexAction()
    {
        // pick the template to serve to our users.
        $this->view->pick('ONEIT/LDAP2Postfix/index');
        $this->view->generalForm = $this->getForm("general");
    }
}
?>
