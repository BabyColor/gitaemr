function ConfirmPassword(){
  var psw = $("#Password").val()
  var psw2 = $("#psw2").val()
 		if (psw!=psw2){
   $("#GEMRPidgey").show()
 } else {
   $("#GEMRPidgey").hide()
 }
}

function Echidona(){
  var input = $("#Input").val()
  $("#Input").keyup(function(x){
    if(x.which == 13){
      $("#Hidden").val(input)
      $("#Textarea").val(input)
    }
  })
}


// Make list of entered data in multi line input (like symptomp, diagnosis, etc)
// ewe = text to be displayed
// ena = <ul> id
// ene = input id (to be cleaned on list inserting and focused back)
// atuk = array/object to be converted to JSON
function Pokeball(ewe, ena, ene, atuk=[]) {
  var ran = Math.random();
  var lis = $('<li></li>').html(ewe);
  $(lis).addClass('w3-container w3-padding-small');
  $(lis).attr("onclick","BukaTutup('"+ ran +"')");
  if (SuspMarker) {
    $(lis).addClass('w3-hover-orange w3-text-orange w3-hover-text-white');
  } else {
    $(lis).addClass('w3-hover-blue w3-text-blue w3-hover-text-white');
  }
  $(lis).append(" <span class='w3-right-align w3-text-red rem'>[&times;]</span>");
  var jonson = JSON.stringify(atuk);
  $(lis).append(' <span class=jonson hidden>' + jonson + '</span>');
  $(lis).append("<div id="+ ran +" class='w3-container w3-hide w3-white'><?php echo walkthrough(1); ?></div>");
  $(ena).append(lis);
  $(ene).val('');
  $(ene).focus();
  $('.rem').on('click', function() {
    $(this).parent().addClass('w3-red');
    $(this).parent().remove();
  });
  $(ene).focus();
}

function FieldSwitch(e) {
  $(e).removeClass("w3-hide").addClass("w3-show");
  $(e).focus();
}

//Filter trought an array of object
// (arr = Array, k = properties to be filtered, v = value as condition)
// it returned object[indexnumber] = value of objects which k value is v
function AOFilter(arr, k, v) {
  var biu=[];
  var x = -1;
  for (var i = 0; i < arr.length; i++) {
    if (arr[i][k] == v) {
      x++
      biu[x]=arr[i];
    }
  }
  return biu;
}

function StrChecker(str, needle) {

  var volvo;
  $(needle).each(function(stri, strv) {
    if (str.includes(strv)) {
      volvo = true;
    }
  });
  return volvo;
}

function SuspClean(dx, suspstring) {
  $(suspstring).each(function(suspk, suspv) {
    dx = dx.replace(suspv + " ", '');
  });
  return dx;
}

function RevealAll(field) {
  $(field).each(function(g, x) {
    $(x).removeClass('w3-hide').addClass('w3-show');
  });
}

function HideEraseAll(field) {
  $(field).each(function(g, x) {
    $(x).removeClass('w3-show').addClass('w3-hide');
    $(x).val('');
  });
}

// Same as AOFilter, but instead of returning as array, it's embeded an option element in a list (datalist, select) elements
//(obj = array of object, p = searched properties, c = searched criteria value of p, listid = the datalist/select to be appended (in jquery terms), rv = the property to be displayed as value in the list, rt = the property to be returned as text of the option)
function MakeList(obj, p, c, listid, rv, rt){
  var arrays = [];
  for (var x = 0; x < obj.length; x++) {
    if (obj[x][p] == c) {
      if(arrays.indexOf(obj[x][rv]) != -1 ){  continue;  }
        $(listid).append("<option id='" + obj[x][rv] + "' value='" + obj[x][rv] + "'>" + obj[x][rt] + "</option>");
        arrays.push(obj[x][rv]);
      }
    }
}

//Same as MakeList, but without filetring, this function take the object raw, without filter, swallowing the whole things.
function Obj2List(obj, listid, rv, rt){
  var arrays = [];
  for (var x = 0; x < obj.length; x++) {
    if(arrays.indexOf(obj[x][rv]) != -1){ continue; }
      $(listid).append("<option id='" + obj[x][rv] + "' value='" + obj[x][rv] + "'>" + obj[x][rt] + "</option>");
      arrays.push(obj[x][rv]);
    }
  }