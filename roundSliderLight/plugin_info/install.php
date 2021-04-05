<?php
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
function roundSliderLight_install(){
}
function roundSliderLight_update(){
	log::add('roundSliderLight','debug','Lancement du script de mise a jours'); 
	foreach(eqLogic::byType('roundSliderLight') as $voletProp){
		$roundSliderLight->save();
	}
	log::add('roundSliderLight','debug','Fin du script de mise a jours');
}
function roundSliderLight_remove(){
}
?>
