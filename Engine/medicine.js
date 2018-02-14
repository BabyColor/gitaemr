console.log("MEDICINE JS LOADED");
//Get the JSON from PHP thru element div id=planning_jonson (Medicine List)
var medlist = JSON.parse($('#planning_jonson').text());
//Get the JSON from PHP thru element div id=abv_jonson (Abbrevation List) in var AbvOri, and make variable Abv into objetcs of Abv = {'name':name, 'exp':explanation}
var AbvOri = JSON.parse($("#abv_jonson").text());
var Abv={};
for(var yaya = 0 ; yaya < AbvOri.length ; yaya++){
  Abv[AbvOri[yaya].abv] = { "name":AbvOri[yaya].name ,  "exp":AbvOri[yaya].explanation };
}
//Medicine form name
var MFormName = JSON.parse($("#MFormN_jonson").text());
//Medicine type
var MType = JSON.parse($("#MType_jonson").text());



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
      if(!newlist[0] || newlist[0]=='') { NewMedModal($(this).val()); }
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
    var dispList = $("<li id='med_"+ jaensan.id +"' medId='"+ jaensan.id +"'></li>");
    $(dispList).attr("onclick","BukaTutup('detail_med"+ jaensan.id +"')");
    $(dispList).addClass('w3-container w3-padding-small w3-hover-blue w3-text-blue w3-hover-text-white');
    $(dispList).append("<span class='w3-xlarge'>R/</span>");
    $(dispList).append("<div>" + dispText1 + "</div>");
    $(dispList).append("<div>" + dispText2 + "</div>");
    
    $(dispList).append("<div></div>");
    $(dispList).append(" <span class='w3-right-align w3-text-red w3-tiny rem' onclick=remButton($(this).parent().attr('id'))>[&times]</span>");
    $(dispList).append(" <span class='w3-right-align w3-text-red w3-tiny edit' onclick=editButton($(this).parent().attr('id'))>[Edit]</span>");
    
    
    $(dispList).append("<div class=jonson hidden>" + JSON.stringify(jaensan) + "</div>");
    
    //Detail information Box
    var DetailBox = $("<div id='detail_med"+ jaensan.id +"' class='w3-card w3-hide w3-light-grey'></div>");
    $(DetailBox).append(MedDes(TempMed));
    $(dispList).append(DetailBox);
    
    $("#PXD").append(dispList);
    
    //$('.rem').click(remButton(this.id));

    //$('.edit').on('click', editButton );
    
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

// New Med Modal Listener
$("#NewForm , #NewRule , #NewAdm , #NewExt").keyup(function(e){
  if(e.which == 13){
    var ABBV = FetchArray(AbvOri,'abv',$(this).val(),1)[0];
    Pokeball($(this).val() +" (" + ABBV.name +") : " + ABBV.explanation , "#"+$(this).prop('id')+"D" ,"#"+$(this).prop('id'),$(this).val());
  }
});

$("#NewCompF , #NewCompD, #NewCompP, #NewCompPer").keyup(function(e){
  if(e.which == 13){
    console.log("ENTER");
    if(!$("#NewCompF").val() || $("#NewCompF").val()==""){ $("#NewCompF").focus(); return; }
    if(!$("#NewCompD").val() ||$("#NewCompD").val()==""){ $("#NewCompD").focus(); return; }
    console.log("ENTER OKE");
    var Comp = $("#NewCompF").val();
    var Dose = $("#NewCompD").val();
    var Prosent = $("#NewCompP").val();
    var Per = $("#NewCompPer").val();
    var Unit, DPer;
    if(Prosent==true) {  Unit = "%"; } else {  Unit = "mg";}
    if(Per == "") { DPer = ""; } else { DPer = " per "+Per; }
    console.log([Comp,Dose,Prosent,Per,Unit,DPer]);
    var jonson = {'name':Comp,'amount':Dose,'prosent':Prosent,'per':Per};
    console.log(Comp + " " + Dose + Unit + DPer);
    Pokeball(Comp + " " + Dose + Unit + DPer,"#NewCompDD","#NewCompF",jonson);
    $("#NewCompF").val("");
    $("#NewCompD").val("");
    $("#NewCompPer").val("");
  }
});

$("#NewMedSave").click(function(){
  var DrugID = "NEW_"+Math.random();
  Resep(DrugID, $("#NewName").val() , $("#Form_N").val())

  var compJonson = $('#NewCompDD .jonson').toArray();
  compJonson = jQuery.map(compJonson, function(a) {
    return $(a).text();
  });

  var formJonson = $('#NewFormD .jonson').toArray();
  formJonson = jQuery.map(formJonson, function(a) {
    return $(a).text();
  });

  var admJonson = $('#NewAdmD .jonson').toArray();
  admJonson = jQuery.map(admJonson, function(a) {
    return $(a).text();
  });

  var ruleJonson = $('#NewRuleD .jonson').toArray();
  ruleJonson = jQuery.map(ruleJonson, function(a) {
    return $(a).text();
  });

  var extJonson = $('#NewExtD .jonson').toArray();
  extJonson = jQuery.map(extJonson, function(a) {
    return $(a).text();
  });
  
  var DrugData= {"id" : DrugID,
                  "name" : $("#NewName").val(),
                  "comp" : compJonson,
                  "type" :  $("#NewType").val(),
                  "form_n" :  $("#NewFormNameD").val(),
                  "form" : formJonson,
                  "adm" : admJonson,
                  "rule" : ruleJonson,
                  "ext" : extJonson
                }
  medlist.push(DrugData);
  $("#NewMedData").append("<li class='jonson'>"+JSON.stringify(DrugData)+"</li>");
  $("#newMedicine").attr('style',"display='none'")
});



//The EDIT adn Remove Button
        
        
function editButton(buttId) {
  var editedID = buttId.replace('medlist_', '');;
  var MedFillet = FetchArray(medlist,'id',editedID,1);
  console.log(MedFillet);
  Datalist = "";
  $("#editedP").removeAttr("Hidden").removeClass("w3-hide");
  $("#PNo").removeAttr("Hidden").removeClass("w3-hide").focus();
  $("#ob_qday").removeAttr("Hidden").removeClass("w3-hide").focus();
  $("#ob_qtt").removeAttr("Hidden").removeClass("w3-hide").focus();
  $("#ob_form").removeAttr("Hidden").removeClass("w3-hide").focus();
  $("#ob_administration").removeAttr("Hidden").removeClass("w3-hide").focus();
  $("#ob_rule").removeAttr("Hidden").removeClass("w3-hide").focus();
  $("#ob_extra").removeAttr("Hidden").removeClass("w3-hide").focus();
  
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
  console.log($("#"+buttId).find(".jonson").text());
  var PJonson = JSON.parse($("#"+buttId).find(".jonson").text());
  console.log(PJonson);
  $("#PNo").val(PJonson[0].No.toString());
  $("#ob_qday").val(PJonson[0].qday.toString());
  $("#ob_qtt").val(PJonson[0].qtt.toString());
  $("#ob_form").val(PJonson[0].form);
  $("#ob_administration").val(PJonson[0].adm);
  $("#ob_rule").val(PJonson[0].rule);
  $("#ob_extra").val(PJonson[0].extra);
  $("#"+buttId).remove();
};

function remButton(buttId){
  $("#"+buttId).remove();
}