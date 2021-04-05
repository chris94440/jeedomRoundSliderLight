$("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});
$('#tab_zones a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');
});
$("body").on('click', ".listCmdAction", function() {
	var el = $(this).closest('.input-group').find('.CmdAction');
	var type=$(this).attr('data-type');
	jeedom.cmd.getSelectModal({cmd: {type: type}}, function (result) {
		el.value(result.human);
	});
});

$('.eqLogicAttr[data-l1key=configuration][data-l2key=cfgLightType]').on('change', function () {
	setImg();
});

setInstructions();

function setInstructions() {
	
	$('#div_instruction_desgin').empty();
	$('#div_instruction_lampe').empty();

	
  
	var instruction_design ='testttttt instruction';
	
	$('#div_instruction_lampe').html('<div class="alert alert-info">'+getInstructionLampe()+'</div>');
	$('#div_instruction_design').html('<div class="alert alert-info">'+getInstructionDesign()+'</div>');
	

}

function getInstructionLampe() {
  	var instruction ="<span><u>Notion accessibilité de la lampe : </u></span>";
  	instruction += "</br>";
  	instruction += "<span>&nbsp;&nbsp;&nbsp;&nbsp;- permet au plugin de savoir si la lampe est joingnable</span>";
  	instruction += "</br>";
  	instruction += "<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ex : lampe de chevet avec bouton sur off est injoingnable</span>";
  	instruction += "</br>";
  	instruction += "</br>";
	instruction += "<span><u>Statut de la lampe : </u></span>";
	instruction += "</br>";	
  	instruction += "<span>&nbsp;&nbsp;&nbsp;&nbsp;- permet de savoir si la lampe est allumée ou éteinte</span>";
	instruction += "</br>";	
  	instruction += "<span>&nbsp;&nbsp;&nbsp;&nbsp;- si cette notion n\'existe pas sur votre équipement la lampe est considérée comme éteinte lorsque la valeur de l\'état de la luminosité est à 0</span>";
	instruction += "</br>";	
  	instruction += "</br>";
  	instruction += "<span><u>Comment allumer la lampe sur le design ? </u></span>";
	instruction += "</br>";	
  	instruction += "<span>&nbsp;&nbsp;&nbsp;&nbsp;- cliquez sur l\'image</span>";
	instruction += "</br>";	
  	instruction += "</br>";
  	instruction += "<span><u>Couleurs préférées ? </u></span>";
	instruction += "</br>";	
  	instruction += "<span>&nbsp;&nbsp;&nbsp;&nbsp;- permet de choisir jusqu\'a 4 couleurs préférées (raccourci vers des ambiances récurrentes)</span>";
	instruction += "</br>";	
  	instruction += "</br>";
  	instruction += "<span><u>Mode debug ? </u></span>";
	instruction += "</br>";	
  	instruction += "<span>&nbsp;&nbsp;&nbsp;&nbsp;- permet d\'activer un niveau de trace supplémentaire dans le fichier de log du plugin et dans la console web du navigateur</span>"; 
  
  
  	return instruction;
}

function getInstructionDesign(){
  	var instruction ="<span><u>Label : </u></span>";
  	instruction += "</br>";
  	instruction += "<span>&nbsp;&nbsp;&nbsp;&nbsp;- Permet d'afficher ou non le nom défini de l'\équipement</span>";
  	instruction += "</br>";
  	instruction += "<span>&nbsp;&nbsp;&nbsp;&nbsp;- couleur de l\'entourage du texte </span>";
  	instruction += "</br>";
  	instruction += "</br>";
	instruction += "<span><u>Bordure : </u></span>";
	instruction += "</br>";	
  	instruction += "<span>&nbsp;&nbsp;&nbsp;&nbsp;- permet d\'afficher ou non une bordure (uniquement si la couleur de la bordure est choisie)</span>";
  	instruction += "</br>";	
  	instruction += "<span>&nbsp;&nbsp;&nbsp;&nbsp;- permet de choisir la couleur de la bordure</span>";
  	instruction += "</br>";	
  	instruction += "<span>&nbsp;&nbsp;&nbsp;&nbsp;- permet de choisir le type de la bordure (<a href=\"https://www.pierre-giraud.com/html-css-apprendre-coder-cours/bordure-border-width-style-color/\"><span><u>les bordures en html</u>)</span></a>)</span>";
	instruction += "</br>";	
  	instruction += "<span>&nbsp;&nbsp;&nbsp;&nbsp;- permet de choisir l\'épaisseur de la bordure (en pixel)</span>";
  
  	return instruction;
}

function setImg() {
	var img = $('.eqLogicAttr[data-l1key=configuration][data-l2key=cfgLightType]').value();
	var newImg='plugins/roundSliderLight/core/template/img/'+img+'_ON.png';

	$('#img_type').attr("src", newImg);
	$('#img_type').css('border-radius', '50%');
}



//Event on tab "Design"
isDisplayDesign($('.eqLogicAttr[data-l1key=configuration][data-l2key=cfgHasEqLabel]').prop("checked"),"label");
isDisplayDesign($('.eqLogicAttr[data-l1key=configuration][data-l2key=cfgHasEqBorder]').prop("checked"),"border");

$('.eqLogicAttr[data-l1key=configuration][data-l2key=cfgHasEqLabel]').on('change', function () {
	isDisplayDesign($(this).prop("checked"),"label");
});


$('.eqLogicAttr[data-l1key=configuration][data-l2key=cfgHasEqBorder]').on('change', function () {
	isDisplayDesign($(this).prop("checked"),"border");
});


function isDisplayDesign(isDisplay,element) {
	//console.log("isDisplayDesign : " + isDisplay + "| element : " + element);
	if (isDisplay) {
		if (element == "label") {
			$('.form-group[data-l1key=configuration][data-l2key=cfgFormLabel]').show();
			$('.form-group[data-l1key=configuration][data-l2key=cfgFormColorLabel]').show();		
		} else if (element == "border") {
			$('.form-group[data-l1key=configuration][data-l2key=cfgFormBorder]').show();		
			$('.form-group[data-l1key=configuration][data-l2key=cfgFormColorBorder]').show();	
			$('.form-group[data-l1key=configuration][data-l2key=cfgFormWidthBorder').show();	
		}
		
	} else {
		if (element == "label") {
			$('.form-group[data-l1key=configuration][data-l2key=cfgFormLabel]').hide();
			$('.form-group[data-l1key=configuration][data-l2key=cfgFormColorLabel]').hide();
			
          	$('.form-control[data-l1key=configuration][data-l2key=cfgColorLabel]').val('');
          	$('.form-control[data-l1key=configuration][data-l2key=cfgLabel]').val('');
			
		} else if (element == "border") {
			$('.form-group[data-l1key=configuration][data-l2key=cfgFormBorder]').hide();			
			$('.form-group[data-l1key=configuration][data-l2key=cfgFormColorBorder]').hide();
			$('.form-group[data-l1key=configuration][data-l2key=cfgFormWidthBorder').hide();	
			
			$('.form-control[data-l1key=configuration][data-l2key=cfgColorBorderEq]').val('');
          	$('.form-control[data-l1key=configuration][data-l2key=cfgTypeBorderEq]').val('');
			$('.form-group[data-l1key=configuration][data-l2key=cfgFormWidthBorder').val('');
		}
	}
}


function addCmdToTable(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }
   		var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
		tr += '<legend><i class="fas fa-info"></i> Commandes Infos</legend>';
		tr += '<td>';
		tr += '<span class="cmdAttr" data-l1key="id" ></span>';
		tr += '</td>';
		
		tr += '<td>';
		tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" >';
		tr += '</td>';
	   
		tr += '<td>';
		//tr += '<span class="cmdAttr" data-l1key="type"></span>';
		//tr += '   /   ';
		tr += '<span class="cmdAttr" data-l1key="subType"></span>';
		tr += '</td>';
	   
		tr += '<td>';
		tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label></span> ';
		if (init(_cmd.subType) == 'numeric' || init(_cmd.subType) == 'binary') {
			tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
		}
	  
		tr += '</td>';
		tr += '<td>';
		if (is_numeric(_cmd.id)) {
			tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fas fa-cogs"></i></a> ';
			tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Evaluer}}</a>';
		}
    
    tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
    tr += '</tr>';
  
  	if (init(_cmd.type) == 'info') {
    	$('#table_cmdi tbody').append(tr);
    	$('#table_cmdi tbody tr:last').setValues(_cmd, '.cmdAttr');
    }
  	if (init(_cmd.type) == 'action') {
    	$('#table_cmda tbody').append(tr);
    	$('#table_cmda tbody tr:last').setValues(_cmd, '.cmdAttr');
    }
	//$('#table_cmd tbody').append(tr);
    //$('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    if (isset(_cmd.type)) {
        $('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type));
    }
    jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
}