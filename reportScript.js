"use strict";
 function fillQuery() {
   var repCat = localStorage.getItem("itemCategory");
   var repMonth = localStorage.getItem("repMonth");
   var repYear = localStorage.getItem("repYear");

   document.getElementById(repCat).selected = true;
   document.getElementById(repMonth).selected = true;
   document.getElementById("repyear").value = repYear;
 }

 function saveQuery() {
   localStorage.setItem("itemCategory",document.getElementById("repcat").value);
   localStorage.setItem("repMonth",document.getElementById("repmonth").value);
   localStorage.setItem("repYear",document.getElementById("repyear").value);
 }

 function init() {
   if (localStorage.getItem("repMonth")) {
     fillQuery();
   }
   var filterForm = document.getElementById('filterform');
   filterForm.onsubmit = saveQuery;
 }

window.onload = init;
