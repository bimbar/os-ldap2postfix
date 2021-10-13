<?php
namespace ONEIT\LDAP2Postfix\Api;
use \OPNsense\Base\ApiMutableModelControllerBase;

class SettingsController extends ApiMutableModelControllerBase
{
    protected static $internalModelName = "ldap2postfix";
    protected static $internalModelClass = "ONEIT\LDAP2Postfix\LDAP2Postfix";
}
?>