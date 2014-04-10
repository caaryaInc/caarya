var unseen_id_array = new Array();  
 function CheckUpdates()  
 {  
   jQuery.ajax({url:"shownotifications.php", dataType:"html", success: function(msg) { 
       if (msg != "") {  
         var result = msg.split("|");  
         var unseen = parseInt(result[0]);  
         var notifications = result[1];  
         var unseen_ids = result[2];  
         if (unseen > 0) {  
           $('#notification-seen').css("display", "inline");  
           $('#notification-seen').html(unseen);  
           for (i = 0; i < unseen_ids.length; i++) {  
             unseen_id_array.push(unseen_ids[i]);  
           }  
         } 
         jQuery('#notifications').html(notifications);  
       } else {jQuery('#notifications').html("No notifications..."); }
     }  
   })
 }  
function DeleteNotification(id) {  
   jQuery.ajax({url:"ajax/updatenotifications.php", data:"notification="+id+"&action=delete", dataType:"html", success: function(msg) {  
       $("#notification_"+id).hide();  
     }  
   });  
 }  
 function SeenNotification() {  
   jQuery.ajax({url:"ajax/updatenotifications.php", data:"notifications="+JSON.stringify(unseen_id_array)+"&action=seen", dataType:"html", success: function(msg) {  
       setTimeout(function() {  
         $('#notification-seen').css("display", "none");  
         $('#notification-seen').html("");  
       },1000);  
     }  
   });  
 }  
 $(document).ready(function() {  
   $('#notifications').click(function() {  
   //  SeenNotification(); 
   });  
 })  
 CheckUpdates(); 
 
// var intervalId = setInterval(CheckUpdates,5000);  