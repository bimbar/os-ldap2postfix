<?php
function ldap2postfix_xmlrpc_sync()
{
    $result = array();
    $result['id'] = 'ldap2postfix';
    $result['section'] = 'ONEIT.LDAP2Postfix';
    $result['description'] = gettext('LDAP2Postfix');
    return array($result);
}
?>