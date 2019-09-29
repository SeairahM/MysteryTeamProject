"use strict";
 function fillSaleLines() {
   var linesNum = Number(document.getElementById('linesnum').textContent);
   var i = 0;
   var currentItemID,currentAmtID;
   while (i < linesNum) {
     currentItemID = "itemline_" + i;
     currentAmtID = "amtline_" + i;
     document.getElementById(currentItemID).value = localStorage.getItem(currentItemID);
     document.getElementById(currentAmtID).value = localStorage.getItem(currentAmtID);
     i += 1;
   }
 }

 function saveSaleLines() {
   var linesNum = Number(document.getElementById('linesnum').textContent);
   var i = 0;
   var currentItemID,currentAmtID;
   while (i < linesNum) {
     currentItemID = "itemline_" + i;
     currentAmtID = "amtline_" + i;
     localStorage.setItem(currentItemID,document.getElementById(currentItemID).value);
     localStorage.setItem(currentAmtID,document.getElementById(currentAmtID).value);
     i += 1;
   }
 }

 function init() {
   if (localStorage.getItem("itemline_0")) {
     fillSaleLines();
   }
   var buttonNewLine = document.getElementById('button_new_line');
   var processForm1 = document.getElementById('form_process');
   buttonNewLine.onsubmit = saveSaleLines;
   processForm1.onsubmit = saveSaleLines;
 }

window.onload = init;
