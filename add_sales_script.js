"use strict";
 function fillSaleLines() {
   
 }

 function saveSaleLines() {
   var linesNum = document.getElementById('linesnum').content;
   var i = 0;
   var currentItemID,currentAmtID;
   while (i < linesNum) {
     currentItemID = "itemline_" + i;
     currentAmtID = "amtline_" + i;
     localStorage.setItem(currentItemID,document.getElementById(currentItemID).content);
     localStorage.setItem(currentAmtID,document.getElementById(currentAmtID).content);
     i += 1;
   }
 }

 function init() {
   var buttonNewLine = document.getElementById('button_new_line');
   var processForm1 = document.getElementById('form_process');
   buttonNewLine.onsubmit = saveSaleLines;
   processForm1.onsubmit = saveSaleLines;
 }

 document.onload = init;
