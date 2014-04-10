<?php  
 require_once("allfields.php");
 class Notification{  
   var $type;  
   var $for_user;  
   var $referingField;  
   var $timestamp;  
   var $newcount;  
   public function getAllNotifications() {  
     $this->newcount = Notification::newCount($this->for_user);  
     $notificationsquery = "SELECT * from notifications where notificationFor = {$this->for_user} ORDER BY `timestamp` DESC LIMIT 10";  
     $result = mysql_query($notificationsquery);  
     echo mysql_error();  
     if ($result) {  
       return $result;  
     }  
     return false; //none found  
   }  
   public function Add() {  
     $sql = "INSERT INTO notifications(notificationFor,notificationType,referingField,) VALUES ({$this->for_user},'{$this->type}',{$this->referingField})";  
     mysql_query($sql);  
   }  
    static function Seen($id) {  
     $sql = "UPDATE notifications SET seen = 1 WHERE notificationID = {$id} AND notificationFor = {$this->for_user}";  
     mysql_query($sql);  
   }  
   static function newCount($user) {  
     $sqlcnt = "SELECT count(*) FROM notifications WHERE notificationFor = {$user} AND seen = 0";  
     $result = mysql_query($sqlcnt);  
     $row = mysql_fetch_row($result);  
	return $row[0];  	
   }  
   static function deleteNotification($id) {  
     $sql = "DELETE FROM notifications WHERE notificationID = {$id} AND notificationFor = {$this->for_user}";  
     mysql_query($sql);  
   }  
 }
 ?>  