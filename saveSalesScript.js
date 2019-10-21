"use strict";

 function resetForm() {
   localStorage.clear();
 }

 function init() {
   var errorMsgArray = document.getElementsByClassName("errmsg");
   var errString = "";
   for (var errMsg of errorMsgArray) {
     errString += errMsg.textContent + " ";
   }
   var url = "addSalesOrStock.php?errMsg=" + errString;
   localStorage.setItem("errMsg",errString);
   window.location.href = url;
 }

window.onload = init;
