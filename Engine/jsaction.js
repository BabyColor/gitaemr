
var suspstring = ['susp', 'Susp', 'suspect', 'Suspect', 'Suspek', 'suspek'];

var SuspMarker, EditedDX;
if ($('#dxsamson').text()) {
	var dxsamson = $('#dxsamson').text();
	dxsamson = JSON.parse(dxsamson);
}

var dxn = [];
var lelepecel = [];
$(dxsamson).each(function (k, v) {
	if (jQuery.inArray(v['dx'], dxn) !== -1) {
		return true;
	}
	var lele = {};
	$(v).each(function (key, val) {
		lele = val;
	});
	dxn.push(v['dx']);
	lelepecel.push(lele);
});




$('#DXF').keyup(function (e) {
	if (e.which == 13) {
		e.preventDefault();
		DXF('#DXF', 1);

	}
});

$('#TXF').keyup(function (e) {
	if (e.which == 13) {
		var tuk = $('#TXF').val();
		Pokeball(tuk, '#TXD', '#TXF');
		e.preventDefault();
	}
});





$('#gita_visit_soap').submit(function () {
	$('#SubjectH').val(li2json('SubjectD'));
	$('#DXH').val(li2json('DXD'));
	$('#PXH').val(li2json('PXD'));
	$('#NewMedH').val(li2json('NewMedData'));
});

//Make json from list of div class .jonson on each list
function li2json(DList) {
	var jonson = $('#' + DList + ' .jonson').toArray();
	jonson = jQuery.map(jonson, function (a) {
		return $(a).text();
	});
	jonson = JSON.stringify(jonson);
	return jonson;
}

//Subject Field
$('#SubjectF').keyup(function (e) {
	var tuk = $('#SubjectF').val();
	e.preventDefault();
	//When entered
	if (e.which == 13) {
		var jon = { 'additional': '' };
		var tuksplit = tuk.split(',');
		var ver = tuksplit[0];
		// Make var 'ver' for readable entry
		if (!!tuksplit[1]) { ver += " <span class=connector>di</span> " + tuksplit[1]; }
		if (!!tuksplit[2]) { ver += " <span class=connector>, menjalar ke</span> " + tuksplit[2]; }
		if (!!tuksplit[3]) { ver += " <span class=connector>, sejak</span> " + tuksplit[3]; }
		if (!!tuksplit[4]) { ver += " <span class=connector>, frekuensi</span> " + tuksplit[4]; }
		if (!!tuksplit[5]) { ver += " <span class=connector>, kualitas</span> " + tuksplit[5]; }
		if (!!tuksplit[6]) { ver += " <span class=connector>, memberat dengan</span> " + tuksplit[6]; }
		if (!!tuksplit[7]) { ver += " <span class=connector>, membaik dengan</span> " + tuksplit[7]; }
		for (var i = 8; i <= (tuksplit.length - 1); i++) { ver += " <span class=connector>, </span> " + tuksplit[i]; }
		//Make object 'jon' (associatove array) to be truned into json and passed to the hiddun input to be passed to form
		jon['symptomp'] = tuksplit[0];
		jon['location'] = tuksplit[1];
		jon['reffered_pain'] = tuksplit[2];
		jon['duration'] = tuksplit[3];
		jon['frequency'] = tuksplit[4];
		jon['quality'] = tuksplit[5];
		jon['worsened_by'] = tuksplit[6];
		jon['relieved_by'] = tuksplit[7];
		for (var i = 8; i <= (tuksplit.length - 1); i++) { jon['note'] += "," + tuksplit[i]; }


		Pokeball(ver, '#SubjectD', '#SubjectF', jon);
	}
	// When comma is used
	vanir(tuk, JSON.parse($('#lanSymptomp').text()));
});




function vanir(oke, message) {
	var count = (oke.split(',').length - 1);
	mes = message[count];
	$('#tip_SubjectF').text(mes);
}


function BMICal(weight, height) {
	if (!!weight && !!height) {
		return weight / ((height / 100) * (height / 100));
	}
}

function BMIInt(BMI) {
	var languageBMI = JSON.parse($("#languageBMI").text());
	var x = 0;
	switch (true) {
		case (BMI < 15):
			x = 1;
			break;
		case (BMI >= 15 && BMI < 16):
			x = 2;
			break;
		case (BMI >= 16 && BMI < 18.5):
			x = 3;
			break;
		case (BMI >= 18.5 && BMI < 25):
			x = 4
			break;
		case (BMI >= 25 && BMI < 30):
			x = 5;
			break;
		case (BMI >= 30 && BMI < 35):
			x = 6;
			break;
		case (BMI >= 35 && BMI < 40):
			x = 7;
			break;
		case (BMI >= 40):
			x = 8;
			break;
	}
	return languageBMI[x];
}

$('#stat_weight , #stat_height').keyup(function () {

	BMI();
});

function BMI() {
	var Wg = parseInt($('#stat_weight').val());
	var Hg = parseInt($('#stat_height').val());
	var BMI = BMICal(Wg, Hg);
	$('#stat_BMI').val(BMI);
	$('#auto_stat_BMI').text(BMI);
	var BMII = BMIInt(BMI);
	$('#stat_BMI_int').val(BMII);
	$('#auto_stat_BMI_int').text(BMII);
}

function BP_GoGo(Sys, Dia) {
	Sys = parseInt(Sys);
	if (parseInt(Dia)) { Dia = parseInt(Dia); }
	if (Sys < 30) {
		Sys *= 10;
	}
	if (Dia < 30) {
		Dia *= 10;
	}
	var BP = Sys + ' / ' + Dia + ' mmHg';
	$('#side_vt_BP').text(BP);
	$('#vt_BP_Sys').val(Sys);
	$('#vt_BP_Dia').val(Dia);
}

$('#vt_BP').keyup(function () {
	var BPSS = $(this).val();
	var BPSS = BPSS.split('/');
	if (BPSS[1]) {
		BP_GoGo(BPSS[0], BPSS[1]);
	}
});

$('#ob_dx_location').keyup(function (e) {
	if (e.which == 13) {
		DXJumper(2);
		e.preventDefault();
	}
});

$('#ob_dx_type').keyup(function (e) {
	if (e.which == 13) {
		DXJumper(3);
		e.preventDefault();
	}
});

$('#ob_dx_grade').keyup(function (e) {
	if (e.which == 13) {
		DXJumper(4);
		e.preventDefault();
	}
});

$('#ob_dx_stage').keyup(function (e) {
	if (e.which == 13) {
		DXJumper(5);
		e.preventDefault();
	}
});
$('#ob_dx_causa').keyup(function (e) {
	if (e.which == 13) {
		DXJumper(6)
		e.preventDefault();
	}
});

//The LAST attributes, on enter, goes to list
$('#ob_dx_note').keyup(function (e) {
	if (e.which == 13) {
		e.preventDefault();
		var a, a2, b, c, d, e, f;
		a = $('#DXF').val();
		a2 = $('#ob_dx_location').val();
		b = $('#ob_dx_type').val();
		c = $('#ob_dx_grade').val();
		d = $('#ob_dx_stage').val();
		e = $('#ob_dx_causa').val();
		f = $('#ob_dx_note').val();
		var atuk = {
			dx: a,
			location: a2,
			type: b,
			grade: c,
			stage: d,
			causa: e,
			note: f
		};
		if (SuspMarker) {
			a = 'Suspect ' + a;
			atuk.susp = true;
		}
		if (a2) {
			a2 = ' ' + a2;
		}
		if (b) {
			b = ', <span class=connector>type</span> ' + b;
		}
		if (c) {
			c = ', <span class=connector>grade</span> ' + c;
		}
		if (d) {
			d = ', <span class=connector>stage</span> ' + d;
		}
		if (e) {
			e = ', <span class=connector>et causa</span> ' + e;
		}
		if (f) {
			f = ', ' + f;
		}
		var tuk = [a, a2, b, c, d, e, f];
		var lobo;
		$(tuk).each(function (i, v) {
			if (lobo) {
				lobo = lobo + v;
			} else {
				lobo = v;
			}

		});
		Pokeball(lobo, '#DXD', '#DXF', atuk);

		//clean up
		HideEraseAll(['#ob_dx_location', '#ob_dx_type', '#ob_dx_grade', '#ob_dx_stage', '#ob_dx_causa', '#ob_dx_note']);
		$("#list_ob_dx_type").empty();
		$("#list_ob_dx_location").empty();
		$("#list_ob_dx_grade").empty();
		$("#list_ob_dx_stage").empty();
		$("#list_ob_dx_causa").empty();

	}
});
// Fungsi bila field diagnosis di tekan enter
function DXF(e, n) {
	var dx = $(e).val();

	//Check if diagnosis is suspect
	var toto = StrChecker(dx, suspstring);
	if (toto) {
		SuspMarker = 1;
		dx = SuspClean(dx, suspstring);
		$(e).val(dx);
	} else {
		SuspMarker = false;
	}


	var jonox = AOFilter(lelepecel, 'dx', dx);
	jonox = jonox[0];


	//Fill datalist to each attributes

	MakeList(dxsamson, 'dx', dx, '#list_ob_dx_type', 'type', 'type');
	MakeList(dxsamson, 'dx', dx, '#list_ob_dx_location', 'location', 'location');
	MakeList(dxsamson, 'dx', dx, '#list_ob_dx_grade', 'grade', 'grade');
	MakeList(dxsamson, 'dx', dx, '#list_ob_dx_stage', 'stage', 'stage');
	MakeList(dxsamson, 'dx', dx, '#list_ob_dx_causa', 'causa', 'causa');


	if (jonox) {
		EditedDX = jonox;
		DXJumper(n);
	} else {
		EditedDX = {
			id: 'ya',
			dx: 'ya',
			type: 'ya',
			grade: 'ya',
			stage: 'ya',
			location: 'ya',
			note: 'ya',
			causa: 'ya'
		};
		DXJumper(n);
	}
}

function DXJumper(n) {
	var x = EditedDX;
	switch (n) {
		case 1:
			if (x.location) {
				FieldSwitch('#ob_dx_location');
				break;
			}
		case 2:
			if (x.type) {
				FieldSwitch('#ob_dx_type');
				break;
			}
		case 3:
			if (x.grade) {
				FieldSwitch('#ob_dx_grade');
				break;
			}
		case 4:
			if (x.stage) {
				FieldSwitch('#ob_dx_stage');
				break;
			}
		case 5:
			if (x.causa) {
				FieldSwitch('#ob_dx_causa');
				break;
			}
		default:
			RevealAll(['#ob_dx_location', '#ob_dx_type', '#ob_dx_grade', '#ob_dx_stage', '#ob_dx_causa']);
			FieldSwitch('#ob_dx_note');
			break;
	}
}
$('#History').keydown(function (e) {
	if (e.which == 13) {
		DXF('#History', 1);
		e.preventDefault();
	}
});

//---------------------------Patient Register--------------------


$('#allergies').keyup(function (e) {
	if (e.which == 13) {
		$('#allergies_reaction').removeClass("w3-hide").addClass("w3-show");
		$('#allergies_reaction').focus();
		e.preventDefault();
	}
})
$('#allergies_reaction').keyup(function (e) {
	if (e.which == 13) {
		e.preventDefault();
		var tuk = $('#allergies').val();
		var tak = $('#allergies_reaction').val();
		var tok = { 'all': tuk, 'reac': tak };
		tok = JSON.stringify(tok);
		tuk = tuk + "-->" + tak;
		Pokeball(tuk, '#AllD', '#allergies', tok);
		$('#allergies_reaction').val('');
		$('#allergies_reaction').removeClass("w3-show").addClass("w3-hide");
	}
});

// Prevent submit on enter
$('#gita_patient').on('keypress', function (e) {
	return e.which !== 13;
});
$('#gita_visit_soap').on('keypress', function (e) {
	return e.which !== 13;
});




$('#gita_patient').submit(function () {
	var dxjonson = $('#DXD .jonson').toArray();
	jonson = jQuery.map(dxjonson, function (a) {
		return $(a).text();
	});
	var oye2 = JSON.stringify(jonson);
	$('#DXH').val(oye2);

	var alljonson = $('#AllD .jonson').toArray();
	jonson = jQuery.map(alljonson, function (a) {
		return $(a).text();
	});

	var oye3 = JSON.stringify(jonson);
	$('#AllH').val(oye3);

	var fdxjonson = $('#FDXD .jonson').toArray();
	jonson = jQuery.map(fdxjonson, function (a) {
		return $(a).text();
	});
	var oye3 = JSON.stringify(jonson);
	$('#FDXH').val(oye3);
});






$('#ob_fdx_location').keyup(function (e) {
	if (e.which == 13) {
		FDXJumper(2);
		e.preventDefault();
	}
});

$('#ob_fdx_type').keyup(function (e) {
	if (e.which == 13) {
		FDXJumper(3);
		e.preventDefault();
	}
});

$('#ob_fdx_grade').keyup(function (e) {
	if (e.which == 13) {
		FDXJumper(4);
		e.preventDefault();
	}
});

$('#ob_fdx_stage').keyup(function (e) {
	if (e.which == 13) {
		FDXJumper(5);
		e.preventDefault();
	}
});
$('#ob_fdx_causa').keyup(function (e) {
	if (e.which == 13) {
		FDXJumper(6)
		e.preventDefault();
	}
});
$('#ob_fdx_note').keyup(function (e) {
	if (e.which == 13) {
		FDXJumper(7)
		e.preventDefault();
	}
});

$('#ob_fdx_who').keyup(function (e) {
	if (e.which == 13) {
		e.preventDefault();
		var a, a2, b, c, d, e, f, AoiSora;
		a = $('#FDXF').val();
		a2 = $('#ob_fdx_location').val();
		b = $('#ob_fdx_type').val();
		c = $('#ob_fdx_grade').val();
		d = $('#ob_fdx_stage').val();
		e = $('#ob_fdx_causa').val();
		f = $('#ob_fdx_note').val();
		AoiSora = $('#ob_fdx_who').val();
		var atuk = {
			dx: a,
			location: a2,
			type: b,
			grade: c,
			stage: d,
			causa: e,
			note: f,
			relationship: AoiSora,
		};
		if (SuspMarker) {
			a = 'Suspect ' + a;
			atuk.susp = true;
		}
		if (a2) {
			a2 = ' ' + a2;
		}
		if (b) {
			b = ', <span class=connector>type</span> ' + b;
		}
		if (c) {
			c = ', <span class=connector>grade</span> ' + c;
		}
		if (d) {
			d = ', <span class=connector>stage</span> ' + d;
		}
		if (e) {
			e = ', <span class=connector>et causa</span> ' + e;
		}
		if (f) {
			f = ', ' + f;
		}
		if (AoiSora) {
			AoiSora = AoiSora + ' : ';
		}

		var tuk = [AoiSora, a, a2, b, c, d, e, f];
		var lobo;
		$(tuk).each(function (i, v) {
			if (lobo) {
				lobo = lobo + v;
			} else {
				lobo = v;
			}

		});
		Pokeball(lobo, '#FDXD', '#FDXF', atuk);

		//Clean Up

		HideEraseAll(['#ob_fdx_location', '#ob_fdx_type', '#ob_fdx_grade', '#ob_fdx_stage', '#ob_fdx_causa', '#ob_fdx_note', '#ob_fdx_who']);
		$("#list_ob_fdx_type").empty();
		$("#list_ob_fdx_location").empty();
		$("#list_ob_fdx_grade").empty();
		$("#list_ob_fdx_stage").empty();
		$("#list_ob_fdx_causa").empty();

	}
});
function FDXF(e, n) {

	var dx = $(e).val();
	var toto = StrChecker(dx, suspstring);
	if (toto) {
		SuspMarker = 1;
		dx = SuspClean(dx, suspstring);
		$(e).val(dx);
	} else {
		SuspMarker = false;
	}
	var jonox = AOFilter(lelepecel, 'dx', dx);
	jonox = jonox[0];


	//Fill datalist to each attributes

	MakeList(dxsamson, 'dx', dx, '#list_ob_fdx_type', 'type', 'type');
	MakeList(dxsamson, 'dx', dx, '#list_ob_fdx_location', 'location', 'location');
	MakeList(dxsamson, 'dx', dx, '#list_ob_fdx_grade', 'grade', 'grade');
	MakeList(dxsamson, 'dx', dx, '#list_ob_fdx_stage', 'stage', 'stage');
	MakeList(dxsamson, 'dx', dx, '#list_ob_fdx_causa', 'causa', 'causa');


	if (jonox) {
		EditedDX = jonox;
		FDXJumper(n);
	} else {
		EditedDX = {
			id: 'ya',
			dx: 'ya',
			type: 'ya',
			grade: 'ya',
			stage: 'ya',
			location: 'ya',
			note: 'ya',
			causa: 'ya'
		};
		FDXJumper(n);
	}
}

function FDXJumper(n) {
	var x = EditedDX;
	switch (n) {
		case 1:
			if (x.location) {
				FieldSwitch('#ob_fdx_location');
				break;
			}
		case 2:
			if (x.type) {
				FieldSwitch('#ob_fdx_type');
				break;
			}
		case 3:
			if (x.grade) {
				FieldSwitch('#ob_fdx_grade');
				break;
			}
		case 4:
			if (x.stage) {
				FieldSwitch('#ob_fdx_stage');
				break;
			}
		case 5:
			if (x.causa) {
				FieldSwitch('#ob_fdx_causa');
				break;
			}
		case 6:
			FieldSwitch('#ob_fdx_note');
			RevealAll(['#ob_fdx_location', '#ob_fdx_type', '#ob_fdx_grade', '#ob_fdx_stage', '#ob_fdx_causa', '#ob_fdx_who']);
			break;
		default:
			FieldSwitch('#ob_fdx_who');
			break;
	}
}
$('#History').keyup(function (e) {
	if (e.which == 13) {
		DXF('#History', 1);
		e.preventDefault();
	}
});
$('#FDXF').keyup(function (e) {
	if (e.which == 13) {
		FDXF('#FDXF', 1);
		e.preventDefault();
	}
});

$("#guardianid").keyup(function (e) {
	if (e.which == 13) {
		if ($("#guardianid").val() != 0) {
			var pxson = JSON.parse($('#pxson').text());
			console.log(pxson);
			$(pxson).each(function (y, x) {
				if (x.patientid == $("#guardianid").val()) {
					$("#pxinfo").addClass('w3-show');
					$("#pxinfoname").text(x.FName + " " + x.LName);
					$("#pxinfosub").text(x.patientid);
					if (!x.photo) {
						switch (x.sex) {
							case '$lanMale':
								var aang = 'def_male.png';
								break;
							case '$lanFemale':
								var aang = 'def_female.png';
								break;
							case '$lanAlien':
								var aang = 'def_alien.png';
								break;
							case '$lanUnidentified':
								var aang = 'def_unknown.png';
								break;
						}
					}
					$("#pxinfoaang").attr("src", "Media/Korra/" + aang);
					$("#guardianfname").val(x.FName);
					$("#guardianlname").val(x.LName);
					$("#guardianprefix").val(x.prefix);
					$("#guardiansex").val(x.sex);
					$("#guardianfname, #guardianlname, #guardiansex, #guardianprefix").attr("disabled", "TRUE");

				}
			});
		} else {
			ClearGuardian();
		}
	}
});
$("#removeguardian").click(function () {
	ClearGuardian();
});

function ClearGuardian() {
	$("#guardianid").val("");
	$("#pxinfo").removeClass('w3-show');
	$("#guardianfname, #guardianlname, #guardiansex, #guardianprefix").removeAttr("disabled");
	$("#guardianfname").val("");
	$("#guardianlname").val("");
	$("#guardianprefix").val("");
}


/////////////////////////////Billing


//Change currency unit and convert the value {UNDER CONSTERUCTION}
$(".js_select_currency").on('change', function () {
	unitF = $(this).closest(".w3-row-padding").find(".js_currency_unit")[0];
	currU = JSON.parse($("#CurrencyUnit").text());
	Unit = FilterList(currU, $(this).closest(".w3-row-padding").find(".js_currency_unit").attr('unit'), 'id')
	console.log(Unit[0]);
	$(unitF).text("AA");
});

$(".js_disc").blur(function () {
	discF = $(this).closest(".js_discount_div").find(".js_discount")[0];
	console.log($(this).closest(".js_discount_div").find(".js_discount"));
	$(discF).val(GimmeDiscount($("#billingRawPrice").text(), $(this).val()));
	console.log(GimmeDiscount($("#billingRawPrice").text(), $(this).val()));
});

$(".js_price , .js_discount, .js_disc").blur(function () {
	$("#billingRawPrice").text(Sum41(".js_price"));
	$("#billingDiscountPrice").text(Sum41(".js_discount"));
	$('#billingTotalPrice').text($('#billingRawPrice').text() - $('#billingDiscountPrice').text());
});


$("#manual_label , #manual_price").keyup(function (e) {
	if (e.which == 13) {

		//If value still empty, don't continue and focus on the empty field instead
		if (!$("#manual_label").val()) { $("#manual_label").focus(); return; }
		if (!$("#manual_price").val()) { $("#manual_price").focus(); return; }

		Label = $("#manual_label").val();
		Nominal = $("#manual_price").val();
		Unit = $("#manual_unit").val();
		Jonson = {'label':Label,'price':Nominal,'unit':Unit};
		Jonson = JSON.stringify(Jonson);
		List = "<li>" + Label + " " + Unit + " " + Nominal + "<input type=hidden class='js_price' value="+ Nominal +"><span hidden class='jonson'>"+ Jonson +"</span></li>";
		$("#billing_manual_additional_list").append(List);

		console.log($(".js_price"));
		$("#billingRawPrice").text(Sum41(".js_price"));
		$("#billingDiscountPrice").text(Sum41(".js_discount"));
		$('#billingTotalPrice').text($('#billingRawPrice').text() - $('#billingDiscountPrice').text());
	}
});

$("#manual_disc_label , #manual_disc_present ,  #manual_disc_price").keyup(function (e) {
	if (e.which == 13) {

		//If value still empty, don't continue and focus on the empty field instead
		if (!$("#manual_disc_label").val()) { $("#manual_disc_label").focus(); return; }
		if (!$("#manual_disc_present").val() && !$("#manual_disc_price").val()) { $("#manual_disc_price").focus(); return; }

		Label = $("#manual_disc_label").val();
		Precent = $("#manual_disc_present").val();
		if($("#manual_disc_price").val()) {
			Nominal = $("#manual_disc_price").val();
		} else {
			Nominal = Number($("#billingRawPrice").text()) * ( Number($("#manual_disc_present").val()) / 100 );
		}
		Unit = $("#manual_disc_unit").val();
		Jonson = {'label':Label,'price':Nominal,'unit':Unit};
		Jonson = JSON.stringify(Jonson);
		List = "<li>" + Label + " " + Unit + " " + Nominal + "<input type=hidden class='js_discount' value="+ Nominal +"><span hidden class='jonson'>"+ Jonson +"</span></li>";
		$("#billing_manual_additional_discount").append(List);


		$("#billingRawPrice").text(Sum41(".js_price"));
		$("#billingDiscountPrice").text(Sum41(".js_discount"));
		$('#billingTotalPrice').text($('#billingRawPrice').text() - $('#billingDiscountPrice').text());
	}
});

$(".js_noSubmitEnter").keydown(function (e) {
	if (e.which == 13) {
		e.preventDefault();
	}
});


$('#gita_billing').submit(function () {
	
	var collectedBills = collectBill('.js_Bills_Items');
	var listBiils = li2json('billing_manual_additional_list');
	listBiils = JSON.parse(listBiils);
	collectedBills = $.merge(collectedBills,listBiils);

	var collectedDisc = collectBill('.js_discount_div');
	var listDisc = li2json('billing_manual_additional_discount');
	listDisc = JSON.parse(listDisc);
	collectedDisc = $.merge(collectedDisc,listDisc);
	
	$('#jonson_bills').val(JSON.stringify(collectedBills));
	$('#jonson_discounts').val(JSON.stringify(collectedDisc));
	$('#price_bills').val($("#billingRawPrice").text());
	$('#price_discounts').val($("#billingDiscountPrice").text());
	$('#price_total').val($("#billingTotalPrice").text());
		
});


//////////////////////////////////////---Template

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




///TESTER
$('#LOLOK').on('click', function () {
	//var oye1 = JSON.stringify(jonson);
	$('#SubjectH').val(li2json('SubjectD'));
	$('#DXH').val(li2json('DXD'));
	$('#PXH').val(li2json('PXD'));
	console.log(li2json('SubjectD'));
	console.log(li2json('PXD'));
	console.log(li2json('NewMedData'));
});

///////////////////// LOGIN

