<?php
namespace ONEIT\LDAP2Postfix\Api;
use \OPNsense\Base\ApiControllerBase;
use \OPNsense\Base\FieldTypes\TextField;
use \OPNsense\Core\Backend;
use \OPNsense\Core\Config;
use \OPNsense\Postfix\Api\ServiceController as PostfixServiceController;
use \OPNsense\Postfix\Sender;
use \OPNsense\Postfix\Recipient;
use \OPNsense\Postfix\Api\RecipientController;
use \OPNsense\Postfix\Api\SenderController;
use \ONEIT\LDAP2Postfix\LDAP2Postfix;




class ServiceController extends ApiControllerBase
{
  public function importAction()
  {
      $uuidField = new TextField();
      $status = "failed";
      $message = "";
      $mdlLDAP2Postfix = new LDAP2Postfix();
      $settings = $mdlLDAP2Postfix->getNodes()["general"];
      if ($settings["Enabled"])
      {
        $mdlRecipient = new Recipient();
        $mdlSender = new Sender();

        if ($settings["EmptyRecipients"])
        {
          $recipients = $mdlRecipient->getNodeByReference("recipients.recipient");
          $ctrlRecipient = new RecipientController();
          $rows = $ctrlRecipient->searchRecipientAction();
          foreach ($rows["rows"] as $row)
          {
            $recipients->del($row["uuid"]);
          }
        }

        if ($settings["EmptySenders"])
        {
          $senders = $mdlSender->getNodeByReference("senders.sender");
          $ctrlSender = new SenderController();
          $rows = $ctrlSender->searchSenderAction();
          foreach ($rows["rows"] as $row)
          {
            $senders->del($row["uuid"]);
          }
        }

        if ($settings["FillRecipients"] || $settings["FillSenders"])
        {

          //fetch LDAP Data
          $output=null;
          $retval=null;
          exec($settings["LDAPCommand"], $output, $retval);
          $recipient = array();
          $sender = array();
          foreach ($output as $row)
          {
              $uuid = $uuidField->generateUUID();
              list($address, $action) = explode(" ", $row);
              $recipient[$uuid] = array("enabled" => "1", "address" => $address, "action" => "OK");
              $sender[$uuid] = array("enabled" => "1", "address" => $address, "action" => "OK");
          }
          $recipients = array("recipients" => array("recipient" => $recipient));
          if ($settings["FillRecipients"])
          {
            $mdlRecipient->setNodes($recipients);
          }

          $senders = array("senders"=> array("sender" => $sender));
          if ($settings["FillSenders"])
          {
            $mdlSender->setNodes($senders);
          }
        }

        //validate and save changes
        $mdlRecipient->performValidation();
        $mdlRecipient->serializeToConfig();
        $mdlSender->performValidation();
        $mdlSender->serializeToConfig();
        Config::getInstance()->save();

        $message = "Saved";
        $status = "successful";

        //regenerate Postfix Config and reload Postfix
        $backend = new Backend();
        $backend->configdRun('template reload OPNsense/Postfix');
        $backend->configdRun('postfix make-transport');
        $postfixServiceController = new PostfixServiceController();
        $postfixServiceController->startAction();
      }else {
        $status = "failed";
        $message = "Not Enabled!";
      }

      return array("status" => $status, "message" => $message);
  }

}


?>
