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

function Pokeball(ewe, ena, ene, atuk) {
  var lis = $('<li></li>').html(ewe);
  if (SuspMarker) {
    $(lis).addClass('suspect');
  }
  $(lis).append(' <span class=rem>[X]</span>')
  var jonson = JSON.stringify(atuk);
  $(lis).append(' <span class=jonson hidden>' + jonson + '</span>')
  $(ena).append(lis);
  $(ene).val('');
  $(ene).focus();
  $('.rem').on('click', function() {
    $(this).parent().remove();
  });
}

function FieldSwitch(e) {

  $(e).removeAttr('hidden');
  $(e).show();
  $(e).focus();
}

function AOFilter(arr, k, v) {
  for (var i = 0; i < arr.length; i++) {
    if (lelepecel[i][k] == v) {
      return lelepecel[i];
    }
  }
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
    $(x).removeAttr('hidden');
    $(x).show();
  });
}

function HideEraseAll(field) {
  $(field).each(function(g, x) {
    $(x).hide();
    $(x).val('');
  });
}
