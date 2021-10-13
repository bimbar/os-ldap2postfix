
<script type="text/javascript">
    $( document ).ready(function() {
        var data_get_map = {'frm_GeneralSettings':"/api/ldap2postfix/settings/get"};
        mapDataToFormUI(data_get_map).done(function(data){
            // place actions to run after load, for example update form styles.
        });

        // link save button to API set action
        $("#saveAct").click(function(){
            $("#saveAct").addClass("fa fa-spinner");
            saveFormToEndpoint(url="/api/ldap2postfix/settings/set",formid='frm_GeneralSettings',callback_ok=function(){
                // action to run after successful save, for example reconfigure service.
                $("#saveAct").removeClass("fa fa-spinner");
                $("#saveAct").blur();
            });
        });
        $("#importAct").click(function(){
            $("#importAct").addClass("fa fa-spinner");
            saveFormToEndpoint(url="/api/ldap2postfix/settings/set",formid='frm_GeneralSettings',callback_ok=function(){
              ajaxCall(url="/api/ldap2postfix/service/import", sendData={},callback=function(data,status) {
                  // action to run after reload
                  $("#responseMsg").removeClass("hidden");
                  $("#responseMsg").html(data['status'] + " : " + data['message']);
                  $("#importAct").removeClass("fa fa-spinner");
                  $("#importAct").blur();
              });
            });
        });

    });
</script>



<div class="alert alert-info hidden" role="alert" id="responseMsg">

</div>

{{ partial("layout_partials/base_form",['fields':generalForm,'id':'frm_GeneralSettings'])}}


<div class="col-md-12">
    <button class="btn btn-primary"  id="saveAct" type="button"><b>{{ lang._('Save') }}</b></button>
    <button class="btn btn-primary"  id="importAct" type="button"><b>{{ lang._('Import') }}</b></button>
</div>
