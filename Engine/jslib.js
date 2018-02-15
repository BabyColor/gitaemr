console.log("JS LIB LOADED");

function ConfirmPassword() {
  var psw = $("#Password").val()
  var psw2 = $("#psw2").val()
  if (psw != psw2) {
    $("#GEMRPidgey").show()
  } else {
    $("#GEMRPidgey").hide()
  }
}

function Echidona() {
  var input = $("#Input").val()
  $("#Input").keyup(function (x) {
    if (x.which == 13) {
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
function Pokeball(ewe, ena, ene, atuk = []) {
  var ran = Math.random();
  var lis = $('<li></li>').html(ewe);
  $(lis).addClass('w3-container w3-padding-small');
  $(lis).attr("onclick", "BukaTutup('" + ran + "')");
  if (SuspMarker) {
    $(lis).addClass('w3-hover-orange w3-text-orange w3-hover-text-white');
  } else {
    $(lis).addClass('w3-hover-blue w3-text-blue w3-hover-text-white');
  }
  $(lis).append(" <span class='w3-right-align w3-text-red rem'>[&times;]</span>");
  var jonson = JSON.stringify(atuk);
  $(lis).append(' <span class=jonson hidden>' + jonson + '</span>');
  $(lis).append("<div id=" + ran + " class='w3-container w3-hide w3-white'><?php echo walkthrough(1); ?></div>");
  $(ena).append(lis);
  $(ene).val('');
  $(ene).focus();
  $('.rem').on('click', function () {
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
  var biu = [];
  var x = -1;
  for (var i = 0; i < arr.length; i++) {
    if (arr[i][k] == v) {
      x++
      biu[x] = arr[i];
    }
  }
  return biu;
}

function StrChecker(str, needle) {

  var volvo;
  $(needle).each(function (stri, strv) {
    if (str.includes(strv)) {
      volvo = true;
    }
  });
  return volvo;
}

function SuspClean(dx, suspstring) {
  $(suspstring).each(function (suspk, suspv) {
    dx = dx.replace(suspv + " ", '');
  });
  return dx;
}

function RevealAll(field) {
  $(field).each(function (g, x) {
    $(x).removeClass('w3-hide').addClass('w3-show');
  });
}

function HideEraseAll(field) {
  $(field).each(function (g, x) {
    $(x).removeClass('w3-show').addClass('w3-hide');
    $(x).val('');
  });
}

// Same as AOFilter, but instead of returning as array, it's embeded an option element in a list (datalist, select) elements
//(obj = array of object, p = searched properties, c = searched criteria value of p, listid = the datalist/select to be appended (in jquery terms), rv = the property to be displayed as value in the list, rt = the property to be returned as text of the option)
function MakeList(obj, p, c, listid, rv, rt) {
  var arrays = [];
  for (var x = 0; x < obj.length; x++) {
    if (obj[x][p] == c) {
      if (arrays.indexOf(obj[x][rv]) != -1) { continue; }
      $(listid).append("<option id='" + obj[x][rv] + "' value='" + obj[x][rv] + "'>" + obj[x][rt] + "</option>");
      arrays.push(obj[x][rv]);
    }
  }
}

//Same as MakeList, but without filetring, this function take the object raw, without filter, swallowing the whole things.
function Obj2List(obj, listid, rv, rt) {
  var arrays = [];
  for (var x = 0; x < obj.length; x++) {
    if (arrays.indexOf(obj[x][rv]) != -1) { continue; }
    $(listid).append("<option id='" + obj[x][rv] + "' value='" + obj[x][rv] + "'>" + obj[x][rt] + "</option>");
    arrays.push(obj[x][rv]);
  }
}


/////////////// Medicine ////////////////////

//Make filetered list
//(array of object to be proccesed [object1,object2,dst],criteria, property to be proccesed, other properite (array of object))
//return only those with property equal to proccesed
function FilterList(array, criteria, property, other = null) {
  var filterlist = [];
  var index = 0;
  for (var x = 0; x < array.length; x++) {
    console.log(array[x][property]);
    criteria = new RegExp(criteria, 'i')
    if (array[x][property].match(criteria)) {
      filterlist[index] = array[x];
      index++;
    } else {
      for (var y = 0; y < array[x][other].length; y++) {
        if (array[x][other][y][property].match(criteria)) {
          filterlist[index] = array[x];
          index++;
          break;
        }
      }
    }
  }
  return filterlist;
}

//This feses called from an input element, everythime user type, the <li> in <ul> with id = listId (passed variable) will be filtered
//More or less same as above, but work on already drawed list instead
//listId = <ul id='listId'>

function listFilterHide(listId, inputId) {
  var filter, match;
  filter = $("#" + inputId).val().toLowerCase();
  $("#" + listId + " li").each(function () {
    match = false;
    $("div", this).each(function () {
      if ($(this).text().toLowerCase().match(filter)) {
        match = true;
      }
    });
    if (match) {
      $(this).removeClass('w3-hide').addClass('w3-show');
    } else {
      $(this).addClass('w3-hide').removeClass('w3-show');
    }
  });

}


//Make Medicine List
//(med) is the object that hold ALL the medicine that would be displayed
//(id) the id of the list (ul)
function MedList(med, id) {
  $(id).empty();
  for (var x = 0; x < med.length; x++) {
    var ran = Math.random();
    var itm = med[x];
    var lis = $('<li></li>').html("<div class='w3-col m6'>" + itm.name + "</div>");
    $(lis).addClass('w3-container w3-padding-small w3-card w3-white w3-hover-blue w3-text-blue w3-hover-text-white w3-row medList');
    $(lis).attr("id", "medlist_" + x);
    $(lis).attr("onclick", "Resep('" + itm.id + "','" + itm.name + "','" + itm.form_n + "')");
    // Additional info
    $(lis).append("<div class='w3-col m6'>" + itm.form_n + "</div>");
    var uli = $("<ul></ul>").addClass("w3-ul w3-tiny w3-container");
    //List of composition
    for (var c = 0; c < itm.comp.length; c++) {
      var lis2 = $('<li></li>');
      $(lis2).append(itm.comp[c].name);
      if (itm.comp[c].amount) {
        $(lis2).append(" " + itm.comp[c].amount);
        switch (itm.comp[c].prosent) {
          default: $(lis2).append(" mg");
            break;
          case "TRUE":
            $(lis2).append("%");
            break;
        }
      }
      if (itm.comp[c].per != "" && itm.comp[c].per) {
        $(lis2).append(" per " + itm.comp[c].per);
      }
      //$(lis).append(lis2);
      $(uli).append(lis2);
    }
    $(lis).append(uli);
    //var jonson = JSON.stringify(atuk);
    // $(lis).append(' <span class=jonson hidden>' + jonson + '</span>');

    $(lis).append("<div id=" + ran + " class='w3-container w3-hide w3-white'><?php echo walkthrough(1); ?></div>");
    $(id).append(lis);
  }
}

//Function that run when user click / chose medicine from list



//Function to navigate throught the list with arrow key
function MedCursor(input) {
  MedCur += input;
  $("#teraphy .medList").removeClass('w3-blue w3-text-white').addClass('w3-white w3-text-blue');
  //$("#teraphy.medList")
  $("#medlist_" + MedCur).removeClass('w3-white w3-text-blue').addClass('w3-blue w3-text-white');
  //$("#medlist"+MedCur);
}

//Function to make datalist of the med Attrib
//field = field id of the input
//medObj = the Object of the medicine
//medprop = the property of the medicine
//example ("ob_form","2","adm")
function MedDataList(field, medObj, medprop) {
  var DataList = $("<datalist></datalist>");
  var tooltip = $("<ul></ul>");
  var abvName, abvExp;
  $(tooltip).addClass("w3-ul");
  $(DataList).attr("id", "list_" + field);
  for (var x = 0; x < medObj[medprop].length; x++) {
    if (Abv[medObj[medprop][x]]) {
      abvName = Abv[medObj[medprop][x]].name;
      abvExp = Abv[medObj[medprop][x]].exp;
    } else {
      abvName = "";
      abvExp = "";
    }
    $(DataList).append("<option value='" + medObj[medprop][x] + "'>" + abvName + "</option>");
    $(tooltip).append("<li class='w3-small w3-left-align'>" + medObj[medprop][x] + " : (" + abvName + ")" + " [" + abvExp + "]" + "</li>")
  }
  $("#DataListHolder").append(DataList);
  $("#tip_" + field).html("");
  $("#tip_" + field).append(tooltipOriginal["tip_" + field]);
  $("#tip_" + field).append(tooltip);
  $("#tip_" + field).addClass("w3-left w3-panel");
}

//Made Medicine Description
function MedDes(Med) {
  //Making container
  var Box = $("<div></div>"); // Main container
  $(Box).addClass("w3-panel w3-row");
  var Box1 = $("<div></div>"); // Drug Class
  $(Box1).addClass("w3-col m4");
  var Box2 = $("<div></div>"); // Composition
  $(Box2).addClass("w3-col m4");
  var Box3 = $("<div></div>"); // Interaction & Contraindication
  $(Box3).addClass("w3-col m4");

  //Populate container
  $(Box1).append(Med.type);
  $(Box2).append(CompList(Med));

  $(Box).append(Box1);
  $(Box).append(Box2);
  $(Box).append(Box3);
  return Box;
}

//Make list of Drugs Compostition
function CompList(Med) {
  var diva = $("<ul></ul>");
  $(diva).addClass('w3-ul');
  //List of composition
  for (var c = 0; c < Med.comp.length; c++) {
    var lis2 = $("<li class='w3-small'></li>");

    $(lis2).append(Med.comp[c].name);
    if (Med.comp[c].amount) {
      $(lis2).append(" " + Med.comp[c].amount);
      switch (Med.comp[c].prosent) {
        default: $(lis2).append(" mg");
          break;
        case "TRUE":
          $(lis2).append("%");
          break;
      }

    }

    if (Med.comp[c].per != "" && Med.comp[c].per) {
      $(lis2).append(" per " + Med.comp[c].per);
    }
    //$(lis).append(lis2);
    $(diva).append(lis2);
  }
  return diva;
}

//MySQL Fetch Array like function, seacrh, in array, object(s) with properties that match the criteria
//Return array of object with matched criteria
//Basically filter() on steroid
//arr = array to be seacrhed
//prop = property to be searched
//cond = condition, in array of cond
//how = how many? if left null, return all of the result

function FetchArray(arr, prop, cond, how) {
  var callBack = [];
  if (!how || how == null || how == undefined) { how = arr.length; }
  for (var x = 0; x < arr.length; x++) {
    if (arr[x][prop] == cond) { callBack.push(arr[x]); how--; }
    if (how <= 0) { break; }
  }
  return callBack;
}

function Resep(did, name, form) {
  Datalist = "";
  $("#editedP").removeAttr("Hidden").removeClass("w3-hide");
  $("#PNo").removeAttr("Hidden").removeClass("w3-hide").focus();
  $("#PID").text(did);
  $("#PName").text(name);
  $("#PForm").text(form);
  $("#teraphy").empty();
  $("#PlanningF").val(name);

  // console.log("AEWA");
  // $("#TempMedJonson").text(jonson);
}

//Make <option></option> from array
// data = the array of object [{obj1},{obj2},..]
// val = which property to become option element's value?
// txt = which property to become option element's text?
function MakeOption(data, val, txt) {
  var Retur = [];
  for (var o = 0; o < data.length; o++) {
    Retur.push("<option value='" + data[o][val] + "'>" + data[o][txt] + "</option>");
  }
  return Retur;
}

//New Medicine Modal on call
//medName = The new med name
function NewMedModal(medName = "") {
  $("#NewName").val(medName);
  //Make list of composition
  var newCmopList = MakeCompList(medlist);
  //Make abbeverabalahabalah list according to their group
  var AbbForm = FetchArray(AbvOri, 'place', 'form');
  var AbbRule = FetchArray(AbvOri, 'place', 'when');
  var AbbAdm = FetchArray(AbvOri, 'place', 'where');
  var AbbExtra = FetchArray(AbvOri, 'place', 'note');


  //make options and assign to datalist of each input
  $("#NewAbvForm").append(MakeOption(AbbForm, 'abv', 'explanation'));
  $("#NewAbvRule").append(MakeOption(AbbRule, 'abv', 'explanation'));
  $("#NewAbvAdm").append(MakeOption(AbbAdm, 'abv', 'explanation'));
  $("#NewAbvExt").append(MakeOption(AbbExtra, 'abv', 'explanation'));
  $("#NewFormName").append(MakeOption(MFormName, 'form_n', 'form_n'));
  $("#NewCompList").append(MakeOption(newCmopList, 'name', 'name'));
  $("#NewTypeList").append(MakeOption(MType, 'type', 'type'));

  //Open Modal window
  $("#newMedicine").css('display', 'block')
}

//Make composition list
//med = variable containing medicine list from MySQL table com_gita_medicine
function MakeCompList(med) {
  var CompListX = "";
  CompListX = [];
  var match = false;
  for (var p = 0; p < med.length; p++) {
    for (var q = 0; q < med[p].comp.length; q++) {
      if (FetchArray(CompListX, 'name', med[p].comp[q].name, 1) < 1) {
        CompListX.push({ 'name': med[p].comp[q].name })
      }
    }
  }

  return CompListX;
}






////////////////////// BILLING /////////////////////

//Conversing currency
//cVal = Currnet Currency Value
//cRate = Currnet Currency Rate
//tRate = Target CUrrency Rate
function CurrencyConverter(cVal, cRate, tRate) {
  return (cVal * cRate) / tRate;
}

//Discount %
//price : Price
//discount : Discount (Can be raw number, can be %)
function GimmeDiscount(price, discount) {
  if (discount.search("%") > -1) {
    discount = discount.replace("%", "");
    discount = price * (Number(discount) / 100);
  }
  return discount;
}

//Billing Calculate Total Price
function Sum41(selector) {
  var sum = 0;
  $(selector).each(function () {
    sum += Number($(this).val());
  });
  return sum;
}

function collectBill(classField) {
  jonson = [];
  $(classField).each(function () {
    var Unit = $(this).find(".js_select_currency")[0];
    Unit = $(Unit).val();
    var Label = $(this).find(".js_bill_label")[0];
    Label = $(Label).text()
    switch (classField) {
      case '.js_Bills_Items':
        var Nominal = $(this).find(".js_price")[0];
        break;
      case '.js_discount_div':
        var Nominal = $(this).find(".js_discount")[0];
        break;
    }


    Nominal = $(Nominal).val();

    var Jonson = { 'label': Label, 'price': Nominal, 'unit': Unit };
    Jonson = JSON.stringify(Jonson);
    jonson.push(Jonson)
  });
  console.log(jonson);
  return jonson;
}

function UrlExists(url) {
  var http = new XMLHttpRequest();
  http.open('HEAD', url, false);
  http.send();
  return TRUE;
}

console.log("INTOL END");

function BukaTutup(id) {
  var x = "#" + id;
  if ($(x).hasClass('w3-hide')) {
    $(x).removeClass('w3-hide').removeAttr('hidden');
  } else {
    $(x).addClass('w3-hide');
  }
  //$(x+".w3-hide").removeClass('w3-hide').removeAttr('hidden');
  //$(x+".w3-hide").removeClass('w3-hide').removeAttr('hidden');
	/*
    if ($(x).className.indexOf("w3-hide") == -1) {
        x.className += " w3-hide";
    } else {
        x.className = x.className.replace(" w3-hide", "");
	}
	*/
}


