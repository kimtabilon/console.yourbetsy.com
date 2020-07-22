function modalAlert(object){

    var iconType="";
    var iconStyle="";
    var color = "";
    var action_type = "";
    var actionArray = [];

    if(!$(".custom-content").length){
        console.log(".main_container doesnt exists!");
        return;
    }

    //prevent multiple modalAlert
    // if($(".alertModal").length){
    //     return;
    // }

    switch(object['type']){
        case "error":
            // iconStyle = "color: #be1e2d; vertical-align: middle;";
            iconType = "clear";
            color = "#be1e2d";            
            title = "Failed";
            action_type = "danger";
            break;
        case "success":
            // iconStyle = "color: #1ABB9C; vertical-align: middle;";        
            iconType="check";
            color = "#1ABB9C";
            title = "Successful";
            action_type = "success";
            break;
        // case "info":
        //     iconStyle = "color: #0984e3; vertical-align: middle;";        
        //     iconType="fa fa-info-circle fa-5x";
        //     color = "#0984e3";            
        //     title = "Info";
        //     break; 

        // case "warning":
        //     iconStyle = "color: #ffa801; vertical-align: middle;";        
        //     iconType="fa fa-exclamation-triangle fa-5x";
        //     color = "#ffa801";            
        //     title = "Warning";
        //     break; 

        default:
            console.log(object['type'] + " is not recognized");
    }

    var alertModalId = "alertModal_"+$(".alertModal").length;

    /* var modalCode = "\
                  <div id=\""+alertModalId+"\" class=\"alertModal modal fade bs-example-modal-lg\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\" >\
                    <div class=\"modal-dialog modal-md\">\
                      <div class=\"modal-content\">\
                        <div class=\"modal-header\">\
                          <button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">×</span>\
                          </button>\
                        </div>\
                        <div class=\"modal-body\">\
                        <div class=\"mod_panel\" style=\"border-color:"+color+"\" >\
                        <div class=\"row\">\
                            <div class=\"text-center\">\
                             <i class=\""+iconType+"\" style=\""+iconStyle+"\";></i>\
                                <h4 class=\"text-center\">"+title+"</h4>\
                                <span>"+object['message']+"<span>\
                                </div>\
                            </div>\
                        </div>\
                        </div>\
                        <div class=\"modal-footer\">\
                          <button type=\"button\" class=\"btn btn-success\" data-dismiss=\"modal\" >Ok</button>\
                        </div>\
                      </div>\
                    </div>\
                  </div>\
    "; */

    var modalCode ="\
                    <div class=\"modal fade modal-custom-alert alertModal\" id=\""+alertModalId+"\" role=\"dialog\" data-backdrop=\"static\" data-keyboard=\"false\">\
                        <div class=\"modal-dialog\">\
                            <div class=\"modal-content\">\
                                <div class=\"modal-body\">\
                                    <div class=\"thank-you-pop\">\
                                        <i class=\"material-icons icon-alert-"+action_type+"\">"+iconType+"</i>\
                                        <h1>"+title+"!</h1>\
                                        <p>"+object['message']+"</p>\
                                        <button type=\"button\" class=\"btn btn-"+action_type+" btn-link\" data-dismiss=\"modal\">\
                                            OK\
                                        <div class=\"ripple-container\"><div class=\"ripple-decorator ripple-on ripple-out\"></div></div></button>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>\
                    </div>";


    $(".custom-content").append(modalCode);            

  
    var maxzIndex = parseInt( findHighestZIndex() );

    $("#"+alertModalId).on('show.bs.modal', function (e) {
            setTimeout(function() {       
                $('.modal-backdrop:last').css('z-index',maxzIndex + 5);
                $("#"+alertModalId).css('z-index',  maxzIndex + 15);        
             },0);
    });

    $("#"+alertModalId).modal("show");        


    $(".alertModal").on('hidden.bs.modal', function () {
        if(object['message'].search('DISABLEACTION')<0){//magic word to disable action on modal close
            if(object['action']){
                    object['action']();
            }
        }

       $("#"+alertModalId).remove();                
    })

}

function saving_modal(action) {

    if (action == "hide") {
        $("#loading_modal").modal("hide");
        // $("#loading_modal").fadeOut();
    } else if(action == "show") {
        var modal ="\
        <div class=\"modal fade modal-custom-alert alertModal-loading loading-modal\" id=\"loading_modal\" role=\"dialog\" data-backdrop=\"static\" data-keyboard=\"false\">\
            <div class=\"modal-dialog\">\
                <div class=\"modal-content\">\
                    <div class=\"modal-body\">\
                        <div class=\"thank-you-pop\">\
                            <i class=\"loading-icon\"><img src=\"/assets/img/loading.gif\"></i>\
                            <p>Saving, please wait.</p>\
                        </div>\
                    </div>\
                </div>\
            </div>\
        </div>";

        $(".custom-content").append(modal);            

        
        var maxzIndex = parseInt( findHighestZIndex() );

        $("#loading_modal").on('show.bs.modal', function (e) {
                setTimeout(function() {       
                    // $('.modal-backdrop:last').css('z-index',maxzIndex + 5);
                    $("#loading_modal").css('z-index',  maxzIndex + 15);        
                },0);
        });
        $("#loading_modal").modal("show");
    }
  
}

function findHighestZIndex(){

    elem = 'div'

/*    $('div').each(function(key){
        zindex = $(this).prop('style');
        console.log( key, $(this)[0],    zindex );
    });

    return;*/

  var elems = document.getElementsByTagName(elem);
  var highest = 0;
  for (var i = 0; i < elems.length; i++)
  {
    var zindex=document.defaultView.getComputedStyle(elems[i],null).getPropertyValue("z-index");
    if ((parseInt(zindex) > parseInt(highest) ) && (zindex != 'auto'))
    {
      highest = zindex;
    }
  }
  return highest;
}


function Status_type(status) {
    status_name = "";
    switch (status) {
        case 0:
            status_name = "Active";
            break;
        case 1:
            status_name = "Pending";
            break;
        case 2:
            status_name = "Declined";
            break;
        case 3:
            status_name = "Suspended";
            break;
        case 4:
            status_name = "Disabled";
            break;
        case 5:
            status_name = "Resubmit";
            break;
        default:
            break;
    }
    return status_name;
}

function Status_type_category(status) {
    status_name = "";
    switch (status) {
        case 0:
            status_name = "Active";
            break;
        case 1:
            status_name = "Inactive";
            break;
        default:
            break;
    }
    return status_name;
}