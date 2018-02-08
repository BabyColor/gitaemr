console.log ("MEDICINE LODADE");

//Get the JSON from PHP thru element div id=planning_jonson (Medicine List)
var medlist = JSON.parse($('#planning_jonson').text());
//Get the JSON from PHP thru element div id=abv_jonson (Abbrevation List)
var Abv = JSON.parse($("#abv_jonson").text());
//Turn medlist into complete object along with json inside the json inside the json
for (var x = 0; x < medlist.length; x++) {
  if (medlist[x].comp != "") {
    medlist[x].comp = JSON.parse(medlist[x].comp);
  }
  if (medlist[x].adm != "") {
    medlist[x].adm = JSON.parse(medlist[x].adm);
  }
  if (medlist[x].form != "") {
    medlist[x].form = JSON.parse(medlist[x].form);
  }
  if (medlist[x].rule != "") {
    medlist[x].rule = JSON.parse(medlist[x].rule);
  }
  if (medlist[x].ext != "") {
    medlist[x].ext = JSON.parse(medlist[x].ext);
  }
}
//Language & Text Variable
var tooltipOriginal = {} ;
tooltipOriginal.tip_ob_form = $("#tip_ob_form").html();
tooltipOriginal.tip_ob_administration = $("#tip_ob_administration").html();
tooltipOriginal.tip_ob_rule = $("#tip_ob_rule").html();
tooltipOriginal.tip_ob_extra = $("#tip_ob_extra").html();
//Make filetered list
//(array of object to be proccesed [object1,object2,dst],criteria, property to be proccesed, other properite (array of object))
//return only those with property equal to proccesed
function FilterList(array, criteria, property, other = null) {
  var filterlist = [];
  var index = 0;
  for (var x = 0; x < array.length; x++) {
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
    $(lis).attr("id","medlist_"+x);
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
function MedCursor(input){
	MedCur += input;
  $("#teraphy .medList").removeClass('w3-blue w3-text-white').addClass('w3-white w3-text-blue');
  //$("#teraphy.medList")
  $("#medlist_"+MedCur).removeClass('w3-white w3-text-blue').addClass('w3-blue w3-text-white');
  //$("#medlist"+MedCur);
}

//Function to make datalist of the med Attrib
//field = field id of the input
//medObj = the Object of the medicine
//medprop = the property of the medicine
//example ("ob_form","2","adm")
function MedDataList(field,medObj,medprop){
	var DataList = $("<datalist></datalist>");
  var tooltip = $("<ul></ul>");
  var abvName, abvExp;
  $(tooltip).addClass("w3-ul");
  $(DataList).attr("id","list_"+field);
  for(var x = 0 ; x < medObj[medprop].length ; x++){
    if(Abv[medObj[medprop][x]]){ 
    	 abvName=Abv[medObj[medprop][x]].name;
       abvExp=Abv[medObj[medprop][x]].exp;
      } else { 
    	 abvName="";
       abvExp="";
     }
    $(DataList).append("<option value='"+medObj[medprop][x]+"'>"+abvName+"</option>");
    $(tooltip).append("<li class='w3-small w3-left-align'>"+medObj[medprop][x]+" : ("+abvName+")"+" ["+abvExp+"]"+"</li>")
  }
  $("#DataListHolder").append(DataList);
  $("#tip_"+field).html("");
  $("#tip_"+field).append(tooltipOriginal["tip_"+field]);
  $("#tip_"+field).append(tooltip);
  $("#tip_"+field).addClass("w3-left w3-panel");
}

//Made Medicine Description
function MedDes(Med){
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
function CompList(Med){
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
//how = how many? if left null, all of the result

function FetchArray(arr,prop,cond,how){
	var callBack= []; 
  if(!how || how==null){ how = arr.length; }
	for(var x = 0 ; x < arr.length ; x++){
  	if(arr[x][prop]==cond){ callBack.push(arr[x]); how--; }
    if(how <= 0) { break; }
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




//Event handdler
var TempMed ; // Temporary medicine that is edited it's attributes
var MedCur = -1; //Cursor on selecting list, set to default (-1) if key other than up/down pressed
var  newlist;
$('#PlanningF').keyup(function(e) {
  if(e.which != 38  && e.which != 40){
    if($(this).val() || $(this).val()!=""){
      newlist = FilterList(medlist, $(this).val(), "name", "comp");
      MedList(newlist, '#teraphy');
    } else {
      $("#teraphy").empty();
    }
   }
});

$('#PlanningF').focus(function() {
	if($(this).val() || $(this).val()!=""){
    newlist = FilterList(medlist, $(this).val(), "name", "comp");
    MedList(newlist, '#teraphy');
  } else {
  	$("#teraphy").empty();
  }
});

$('#PlanningF').blur(function() { $("#teraphy").delay(500).queue(function() { $("#teraphy").empty(); })});

$('#PlanningF').keydown(function(e) {
	switch(e.which){
  case 38:
  	MedCursor(-1);
    break;
  case 40:
  	MedCursor(1);
    break;
  case 13:
  	//TempMed = newlist[MedCur];
      if(!newlist[0] || newlist[0]=='') { $("#newMedicine").css('display','block'); }
      Resep(newlist[MedCur].id,newlist[MedCur].name,newlist[MedCur].form_n);
    break;
  default:
  	MedCur = -1; //Cursor on selecting list, set to default (-1) if key other than up/down pressed
    break;
  }
	
});

$("#PNo").keyup(function(e){
  if(e.which==13 && $(this).val() != "" ){
     var MedFillet = FetchArray(medlist,'id',$("#PID").text(),1);
     TempMed = MedFillet[0];
    $("#MedAttr").removeAttr("Hidden").removeClass("w3-hide");
    $("#ob_qday").removeAttr("Hidden").removeClass("w3-hide").focus();
    if(!Datalist || Datalist == ""){ // Check if datalist for this temp already created?
      MedDataList("ob_form",TempMed,'form');
      MedDataList("ob_administration",TempMed,'adm');
      MedDataList("ob_rule",TempMed,'rule');
      MedDataList("ob_extra",TempMed,'ext');
      Datalist = "TRUE";
     }
  }
});

$("#ob_qday").keyup(function(e){
  if(e.which==13 && $(this).val() != ""){
    $("#ob_qtt").removeAttr("Hidden").removeClass("w3-hide").focus();
  }
});

$("#ob_qtt").keyup(function(e){
  if(e.which==13 && $(this).val() != ""){
    $("#ob_form").removeAttr("Hidden").removeClass("w3-hide").focus();
  }
});

$("#ob_form").keyup(function(e){
  if(e.which==13){
    $("#ob_administration").removeAttr("Hidden").removeClass("w3-hide").focus();
  }
});

$("#ob_administration").keyup(function(e){
  if(e.which==13){
    $("#ob_rule").removeAttr("Hidden").removeClass("w3-hide").focus();
  }
});

$("#ob_rule").keyup(function(e){
  if(e.which==13){
    $("#ob_extra").removeAttr("Hidden").removeClass("w3-hide").focus();
  }
});

$("#ob_extra").keyup(function(e){
  if(e.which==13){
  
  //Check if required field already filled
  if(!$("#PlanningF").val()){ $("#PlanningF").focus(); return; }
  if(!$("#PNo").val()){ $("#PNo").focus(); return; }
  if(!$("#ob_qday").val()){ $("#ob_qday").focus(); return; }
  
  //Preparing the data for the list
  console.log (TempMed);
  	var jaensan = {
    			"id":TempMed.id,
          "No":$("#PNo").val(),
          "qday":$("#ob_qday").val(),
          "qtt":$("#ob_qtt").val(),
          "form":$("#ob_form").val(),
          "adm":$("#ob_administration").val(),
          "rule":$("#ob_rule").val(),
          "extra":$("#ob_extra").val()
          } 
     console.log (jaensan);
    var dispText1 = "<strong>"+TempMed.name + "</strong> " + TempMed.form_n + " No " + jaensan.No;
    var dispText2 = "<span class='w3-large'>∫</span> " + jaensan.qday + " <span class='w3-small'>dd</span> " + jaensan.qtt + " " + jaensan.form + " " + jaensan.adm ;
    if(jaensan.rule && jaensan.rule !=""){ dispText2 += " . " + jaensan.rule; }
    if(jaensan.extra && jaensan.extra !=""){ dispText2 += " ("+ jaensan.extra +")"; }
    
    //Make list element
    var dispList = $("<li id='"+ jaensan.id +"'></li>");
    $(dispList).attr("onclick","BukaTutup('detail_"+ jaensan.id +"')");
    $(dispList).addClass('w3-container w3-padding-small w3-hover-blue w3-text-blue w3-hover-text-white');
    $(dispList).append("<span class='w3-xlarge'>R/</span>");
    $(dispList).append("<div>" + dispText1 + "</div>");
    $(dispList).append("<div>" + dispText2 + "</div>");
    
    $(dispList).append("<div></div>");
    $(dispList).append(" <span class='w3-right-align w3-text-red w3-tiny rem'>[&times]</span>");
    $(dispList).append(" <span class='w3-right-align w3-text-red w3-tiny edit'>[Edit]</span>");
    
    
    $(dispList).append("<div class=jonson hidden>" + JSON.stringify(jaensan) + "</div>");
    
    //Detail information Box
    var DetailBox = $("<div id='detail_"+ jaensan.id +"' class='w3-card w3-hide w3-light-grey'></div>");
    $(DetailBox).append(MedDes(TempMed));
    $(dispList).append(DetailBox);
    
    $("#PXD").append(dispList);
    
    $('.rem').on('click', function() {
      $(this).parent().remove();
    });
    
    //The EDIT Button
            $('.edit').on('click', function() {
            var editedID = $(this).parent().prop('id');
            var MedFillet = FetchArray(medlist,'id',editedID,1);
            Datalist = "";
            $("#editedP").removeAttr("Hidden").removeClass("w3-hide");
            $("#PNo").removeAttr("Hidden").removeClass("w3-hide").focus();
            
            $("#PID").text(editedID);
            $("#PName").text(MedFillet[0].name);
            $("#PForm").text(MedFillet[0].form_n);
            $("#teraphy").empty();
            $("#PlanningF").val(MedFillet[0].name);
            
            MedFillet = FetchArray(medlist,'id',$("#PID").text(),1);
            TempMed = MedFillet[0];
            $("#MedAttr").removeAttr("Hidden").removeClass("w3-hide");
            $("#ob_qday").removeAttr("Hidden").removeClass("w3-hide").focus();
            if(!Datalist || Datalist == ""){ // Check if datalist for this temp already created?
                MedDataList("ob_form",TempMed,'form');
                MedDataList("ob_administration",TempMed,'adm');
                MedDataList("ob_rule",TempMed,'rule');
                MedDataList("ob_extra",TempMed,'ext');
                Datalist = "TRUE";
            }
            console.log($(this).parent().find(".jonson").text());
            var PJonson = JSON.parse($(this).parent().find(".jonson").text());
            console.log(PJonson);
            $("#PNo").val(PJonson.No);
            $("#ob_qday").val(PJonson.qday);
            $("#ob_qtt").val(PJonson.qtt);
            $("#ob_form").val(PJonson.form);
            $("#ob_administration").val(PJonson.adm);
            $("#ob_rule").val(PJonson.rule);
            $("#ob_extra").val(PJonson.extra);
            
            $(this).parent().remove();
            });
    
    //Cleaning
    $("#PlanningF").val("").focus();
    $("#editedP").attr("Hidden",'TRUE').addClass("w3-hide");
    $("#PID").text("");
    $("#PName").text("");
    $("#PForm").text("");
    $("#PNo").val("").addClass("w3-hide");
    $("#ob_qday").val("").addClass("w3-hide");
    $("#ob_qtt").val("").addClass("w3-hide");
    $("#ob_form").val("").addClass("w3-hide");
    $("#ob_administration").val("").addClass("w3-hide");
    $("#ob_rule").val("").addClass("w3-hide");
    $("#ob_extra").val("").addClass("w3-hide");
    $("#PNo").val("").addClass("w3-hide");
    $("#PNo").attr("Hidden",'TRUE').addClass("w3-hide");
    $("#MedAttr").attr("Hidden",'TRUE').addClass("w3-hide");
    $("#ob_qday").attr("Hidden",'TRUE').addClass("w3-hide")
    TempMed = null ;
    $("#tip_ob_form").html(tooltipOriginal.tip_ob_form);
    $("#tip_ob_administration").html(tooltipOriginal.tip_ob_administration);
    $("#tip_ob_rule").html(tooltipOriginal.tip_ob_rule);
    $("#tip_ob_extra").html(tooltipOriginal.tip_ob_extra);
  }
});

$("#newMed").click(function(){$("#newMedicine").css('display','block')});