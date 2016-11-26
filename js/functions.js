function toggleMe(formname, elementname){
var language = document.forms[formname].elements[elementname].options[document.forms[formname].elements[elementname].selectedIndex].value;
var f = language + "_title";
var g = language + "_description";
var b = document.getElementById(f);
var x = b.parentNode.parentNode;
var c = document.getElementById(g);
var y = c.parentNode.parentNode;
if(!x)return true;
if(x.style.display=="none"){
x.style.display="table-row"
} else {
x.style.display="none"
}
if(!y)return true;
if(y.style.display=="none"){
y.style.display="table-row"
} else {
y.style.display="none"
}
return true;
}
function toggleMe2(formname, elementname) {
var language = elementname;
var f = language + "_title";
var g = language + "_description";
var b = document.getElementById(f);
var x = b.parentNode.parentNode;
var c = document.getElementById(g);
var y = c.parentNode.parentNode;
if(!x)return true;
if(x.style.display=="none"){
x.style.display="table-row"
} else {
x.style.display="none"
}
if(!y)return true;
if(y.style.display=="none"){
y.style.display="table-row"
} else {
y.style.display="none"
}
return true;
}
function toggleMe3(formname, elementname) {
var language = elementname;
var g = language + "_description";
var c = document.getElementById(g);
var y = c.parentNode.parentNode;
if(!y)return true;
if(y.style.display=="none"){
y.style.display="table-row"
} else {
y.style.display="none"
}
return true;
}
function platformremove(elementId) {
var selecter = document.forms['extlink'].elements['platform'].options[document.forms['extlink'].elements['platform'].selectedIndex].value;
var _el = document.getElementById(elementId);
var _parent = _el.parentNode;
if (selecter != 0) {
document.getElementById('album').parentNode.parentNode.style.display = 'none';
document.getElementById('year').parentNode.parentNode.style.display = 'none';
document.getElementById('track').parentNode.parentNode.style.display = 'none';
document.getElementById('length').parentNode.parentNode.style.display = 'none';
document.getElementById('bitrate').parentNode.parentNode.style.display = 'none';
document.getElementById('frequence').parentNode.parentNode.style.display = 'none';
_parent.removeChild(_el);
_parent.innerHTML = '<textarea cols="50" rows="10" name="' + _el.id + '" id="' + _el.id + '"></textarea>';
_el = null;
} else {
document.getElementById('album').parentNode.parentNode.style.display = 'table-row';
document.getElementById('year').parentNode.parentNode.style.display = 'table-row';
document.getElementById('track').parentNode.parentNode.style.display = 'table-row';
document.getElementById('length').parentNode.parentNode.style.display = 'table-row';
document.getElementById('bitrate').parentNode.parentNode.style.display = 'table-row';
document.getElementById('frequence').parentNode.parentNode.style.display = 'table-row';
_parent.removeChild(_el);
_parent.innerHTML = '<input type="text" name="' + _el.id + '" id="' + _el.id + '" value="" size="50" maxlength="500" />';
_el = null;
}
}
function saveOrderList(listId) {
var list = document.getElementById(listId);
var items = list.getElementsByTagName('li');
var ids = '';
var hiddeninput = document.getElementById('hiddenvalues'); // the input field storing the order
for (var i = 0; i < items.length; i++)  {
if (i > 0) ids += ' ';
var id = items[i].getAttribute('title');
ids += id;
}
hiddeninput.value = ids;
return true;
}
function visibleembed() {
var visembed = document.getElementById('visembed');
visembed.style.display='block';
}
function novisibleembed() {
var novisembed = document.getElementById('visembed');
novisembed.style.display='none';
document.getElementById('selform').reset();
document.getElementById('showthecode').innerHTML = ' ';
}