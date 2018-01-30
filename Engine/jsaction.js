var suspstring = ['susp', 'Susp', 'suspect', 'Suspect', 'Suspek', 'suspek'];
var SuspMarker, EditedDX;
var dxsamson = $('#dxsamson').text();
dxsamson = JSON.parse(dxsamson);
var dxn = [];
var lelepecel = [];
$(dxsamson).each(function(k, v) {
  if (jQuery.inArray(v['dx'], dxn) !== -1) {
    return true;
  }
  var lele = {};
  $(v).each(function(key, val) {
    lele = val;
  });
  dxn.push(v['dx']);
  lelepecel.push(lele);
});



$('#SubjectF').keyup(function(e) {
    if (e.which == 13) {
      var tuk = $('#SubjectF').val();
      Pokeball(tuk, '#SubjectD', '#SubjectF', tuk);
			e.preventDefault();
    }
  });

	$('#DXF').keyup(function(e) {
		if (e.which == 13) {
			DXF('#DXF', 1);
			e.preventDefault();
		}
	});

  $('#TXF').keyup(function(e) {
    if (e.which == 13) {
      var tuk = $('#TXF').val();
      Pokeball(tuk, '#TXD', '#TXF');
      e.preventDefault();
    }
  });





  $('#gita_visit_soap').submit(function() {
		var dxjonson = $('#DXD .jonson').toArray();
		jonson = jQuery.map(dxjonson, function(a) {
			return $(a).text();
		});
		 var oye2 = JSON.stringify(jonson);
		 var subjonson = $('#DXD .jonson').toArray();
		jonson = jQuery.map(subjonson, function(a) {
			return $(a).text();
		});
		 var oye1 = JSON.stringify(jonson);
			var oye3 = li2json($('#TXD').text());
			$('#SubjectH').val(oye1);
			$('#DXH').val(oye2);
			$('#TXH').val(oye3);
		});
		
	function li2json(li){
		var oye = li.split(' [X]')
		return JSON.stringify(oye);
	 }

  $('#SubjectF').keyup(function(e) {
 var tuk = $('#SubjectF').val();
   vanir(tuk, "<? $GLOBALS['lanSymtompSep1']  ?>", "<? $GLOBALS['lanSymtompSep2']  ?>", "<? $GLOBALS['lanSymtompSep3']  ?>", "<? $GLOBALS['lanSymtompSep4']  ?>", "<? $GLOBALS['lanSymtompSep5']  ?>", "<? $GLOBALS['lanSymtompSep6']  ?>", "<? $GLOBALS['lanSymtompSep7']  ?>","<? $GLOBALS['lanSymtompSep8']  ?>");  });
   
   $('#SubjectF').focus(function() {
	$('#Subjectguide').css('visibility', 'visible');
   });
   $('#SubjectF').blur(function() {
	$('#Subjectguide').css('visibility', 'hidden');
   });

   
 function vanir(oke, a, b, c, d, e, f, g, h){
	 var defa="<? $GLOBALS['lanSymptomp']  ?>";
 var count = (oke.split(',').length - 1);
 switch (count) {
  case 1:
  var mes=a;
  break;
  case 2:
  var mes=b;
  break;
  case 3:
  var mes=c;
  break;
  case 4:
  var mes=d;
  break;
  case 5:
  var mes=e;
  break;
  case 6:
  var mes=f;
  break;
  case 7:
  var mes=g;
  break;
  case 8:
  var mes=h;
  break;
  default:
	 var mes=defa;
	 break;
 }
 $('#Subjectguide').text(mes);
 }

 
 function BMICal(weight, height) {
	if (!!weight && !!height) {
	  return weight / ((height / 100) * (height / 100));
	}
  }
  
  function BMIInt(BMI) {
	switch (true) {
	  case (BMI < 15):
		return "<? $GLOBALS['comvisitBMIVSU']  ?>";
		break;
	  case (BMI >= 15 && BMI < 16):
		return "<? $GLOBALS['comvisitBMISU']  ?>";
		break;
	  case (BMI >= 16 && BMI < 18.5):
		return "<? $GLOBALS['comvisitBMIU']  ?>";
		break;
	  case (BMI >= 18.5 && BMI < 25):
		return "<? $GLOBALS['comvisitBMIN']  ?>";
		break;
	  case (BMI >= 25 && BMI < 30):
		return "<? $GLOBALS['comvisitBMIO']  ?>";
		break;
	  case (BMI >= 30 && BMI < 35):
		return "<? $GLOBALS['comvisitBMIOI']  ?>";
		break;
	  case (BMI >= 35 && BMI < 40):
		return "<? $GLOBALS['comvisitBMIOII']  ?>";
		break;
	  case (BMI >= 40):
		return "<? $GLOBALS['comvisitBMIOIII']  ?>";
		break;
	}
  }
  
  $('#stat_weight').keyup(function() {
	BMI();
  });
  
  function BMI() {
	var Wg = parseInt($('#stat_weight').val());
	var Hg = parseInt($('#stat_height').val());
	var BMI = BMICal(Wg, Hg);
	$('#stat_BMI').val(BMI.toFixed(2));
	$('#side_stat_BMI').text(BMI.toFixed(2));
	var BMII = BMIInt(BMI);
	$('#stat_BMI_int').val(BMII);
	$('#side_stat_BMI_int').text(BMII);
  }
  
  function BP_GoGo(Sys,Dia){
	Sys = parseInt(Sys);
	if(parseInt(Dia)) { Dia = parseInt(Dia); }
	if(Sys < 30  ){
		Sys *= 10;
	}
	if  (Dia < 30){
		Dia *= 10;
	}
	var BP = Sys +' / '+ Dia +' mmHg';
	$('#side_vt_BP').text(BP);
	$('#vt_BP_Sys').val(Sys);
	$('#vt_BP_Dia').val(Dia);
  }

  $('#vt_BP').keyup(function(){
	  var BPSS= $(this).val();
	  var BPSS= BPSS.split('/');
  if(BPSS[1]){
	  BP_GoGo(BPSS[0],BPSS[1]);
  }
  });
	
	$('#ob_dx_location').keyup(function(e) {
		if (e.which == 13) {
			DXJumper(2);
			e.preventDefault();
		}
	});
	
	$('#ob_dx_type').keyup(function(e) {
		if (e.which == 13) {
			DXJumper(3);
			e.preventDefault();
		}
	});
	
	$('#ob_dx_grade').keyup(function(e) {
		if (e.which == 13) {
			DXJumper(4);
			e.preventDefault();
		}
	});
	
	$('#ob_dx_stage').keyup(function(e) {
		if (e.which == 13) {
			DXJumper(5);
			e.preventDefault();
		}
	});
	$('#ob_dx_causa').keyup(function(e) {
		if (e.which == 13) {
			DXJumper(6)
			e.preventDefault();
		}
	});
	
	$('#ob_dx_note').keyup(function(e) {
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
			$(tuk).each(function(i, v) {
				if (lobo) {
					lobo = lobo + v;
				} else {
					lobo = v;
				}
				
			});
			Pokeball(lobo, '#DXD', '#DXF', atuk);
			HideEraseAll(['#ob_dx_location', '#ob_dx_type', '#ob_dx_grade', '#ob_dx_stage', '#ob_dx_causa', '#ob_dx_note']);
	
			
		}
	});
	function DXF(e, n) {

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
	$('#History').keydown(function(e) {
		if (e.which == 13) {
			DXF('#History', 1);
			e.preventDefault();
		}
	});

	//---------------------------Patient Register--------------------


	$('#allergies').keyup(function(e){
		if (e.which==13){
			$('#allergies_reaction').focus();
			e.preventDefault();
		}
	})
	$('#allergies_reaction').keyup(function(e) {
    if (e.which == 13) {
			e.preventDefault();
			var tuk = $('#allergies').val();
			var tak = $('#allergies_reaction').val();
			var tok = {'all':tuk,'reac':tak};
			tok = JSON.stringify(tok);
			tuk = tuk +"-->"+ tak;
			Pokeball(tuk, '#AllD', '#allergies', tok);
			$('#allergies_reaction').val('');
			console.log("6");
    }
  });

	$('#gita_patient').on('keypress', function(e) {
    return e.which !== 13;
});
	$('#gita_patient').submit(function() {
		var dxjonson = $('#DXD .jonson').toArray();
		jonson = jQuery.map(dxjonson, function(a) {
			return $(a).text();
		});
		 var oye2 = JSON.stringify(jonson);
			$('#DXH').val(oye2);

			var alljonson = $('#AllD .jonson').toArray();
		jonson = jQuery.map(alljonson, function(a) {
			return $(a).text();
		});

		 var oye3 = JSON.stringify(jonson);
			$('#AllH').val(oye3);

			var fdxjonson = $('#FDXD .jonson').toArray();
		jonson = jQuery.map(fdxjonson, function(a) {
			return $(a).text();
		});
		 var oye3 = JSON.stringify(jonson);
			$('#FDXH').val(oye3);
		});


		$('#FDXF').keyup(function(e) {
			if (e.which == 13) {
				DXF('#DXF', 1);
				e.preventDefault();
			}
		});
	
	
	
		$('#ob_fdx_location').keyup(function(e) {
			if (e.which == 13) {
				FDXJumper(2);
				e.preventDefault();
			}
		});
		
		$('#ob_fdx_type').keyup(function(e) {
			if (e.which == 13) {
				FDXJumper(3);
				e.preventDefault();
			}
		});
		
		$('#ob_fdx_grade').keyup(function(e) {
			if (e.which == 13) {
				FDXJumper(4);
				e.preventDefault();
			}
		});
		
		$('#ob_fdx_stage').keyup(function(e) {
			if (e.which == 13) {
				FDXJumper(5);
				e.preventDefault();
			}
		});
		$('#ob_fdx_causa').keyup(function(e) {
			if (e.which == 13) {
				FDXJumper(6)
				e.preventDefault();
			}
		});
		$('#ob_fdx_note').keyup(function(e) {
			if (e.which == 13) {
				FDXJumper(7)
				e.preventDefault();
			}
		});
		
		$('#ob_fdx_who').keyup(function(e) {
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
				$(tuk).each(function(i, v) {
					if (lobo) {
						lobo = lobo + v;
					} else {
						lobo = v;
					}
					
				});
				Pokeball(lobo, '#FDXD', '#FDXF', atuk);
				HideEraseAll(['#ob_fdx_location', '#ob_fdx_type', '#ob_fdx_grade', '#ob_fdx_stage', '#ob_fdx_causa', '#ob_fdx_note', '#ob_fdx_who']);
				
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
					if (x.causa) {
						FieldSwitch('#ob_fdx_note');
						RevealAll(['#ob_fdx_location', '#ob_fdx_type', '#ob_fdx_grade', '#ob_fdx_stage', '#ob_fdx_causa', '#ob_fdx_who']);
						break;
					}
				default:
					FieldSwitch('#ob_fdx_who');
					break;
			}
		}
		$('#History').keyup(function(e) {
			if (e.which == 13) {
				DXF('#History', 1);
				e.preventDefault();
			}
		});
		$('#FDXF').keyup(function(e) {
			if (e.which == 13) {
				FDXF('#FDXF', 1);
				e.preventDefault();
			}
		});


		//---Template

function myFunction(id) {
    var x = document.getElementById(id);
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else {
        x.className = x.className.replace(" w3-show", "");
    }
}