// var scrolling = function (){
//   for (var i = 0; i < 500; i++)
//   {
//     setTimeout(function () {}, 1000);
//     window.scrollTo(0,document.body.scrollHeight);
//   }
// };
var scrolling = function (){ for (var i = 0; i < 500; i++) { setTimeout(function () {}, 1000); window.scrollTo(0,document.body.scrollHeight); } };

var yes = function () { for (var i = 0; i < 500; i++) { scrolling(); } }
// hack.push(document.querySelectorAll('.KL4Bh'));

// var get_data2 = function (hack) {
//   var data = [];
//   for (var i = 0; i < hack.length; i++){
//     for(var j = 0; j < hack[i].length; j++){
//       data.push(hack[i][j].innerHTML);
//     }
//   }
//   return(data);
// }
var get_data2 = function (hack) { var data = []; for (var i = 0; i < hack.length; i++){ for(var j = 0; j < hack[i].length; j++){ data.push(hack[i][j].innerHTML); } } return(data); }

var inlist = function (element, data){ for (var i = 0; i < data.length; i++){ if (data[i] === element) { return true;} } return false; }

var sanitizer = function (data) { var sanitized = []; data.forEach(element => { if (!inlist(element, sanitized)){ sanitized.push(element); } }); return sanitized; }
