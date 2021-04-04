<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
class roundSliderLight extends eqLogic {
	
	public static function writeLog($logType,$message,$equipmentName) {
		
		if ($logType != '') {
			$nameOfEquipment='';
			if ($equipmentName !='') {
					$nameOfEquipment= '( ' . $equipmentName . ' )';
			}
			switch ($logType) {
				case 'info':
					log::add('roundSliderLight','info',$message .  $nameOfEquipment );
					break;
				case 'debug':
					log::add('roundSliderLight','debug',$message . $nameOfEquipment);
					break;
				case 'error':
					log::add('roundSliderLight','error',$message .  $nameOfEquipment);
					break;
				default:
					break;
			}
		} else {
			log::add('roundSliderLight','debug',"pas de log type pour ce message : " . $message);
		}
	}
	
	
	public function AddCommande($Name,$_logicalId,$Type="info", $SubType='binary',$visible,$Value=null,$icon=null,$generic_type=null,$buildCalcul=false,$order,$minValue=0,$maxValue=100) {
		$Commande = $this->getCmd(null,$_logicalId);
		if (!is_object($Commande)){
          	$this->writeLog('info','*** Creation cmd :' . $Name . ' - id : ' . $_logicalId . ' ***',$this->getHumanName());
			$Commande = new roundSliderLightCmd();
			$Commande->setId(null);
			$Commande->setName($Name);
			$Commande->setIsVisible($visible);
			$Commande->setLogicalId($_logicalId);
			$Commande->setEqLogic_id($this->getId());
			$Commande->setType($Type);
			$Commande->setSubType($SubType);
			
	        if($Value != null) {
				$Commande->setValue($Value);
            }
          
           	if($Value != null) {
				if ($buildCalcul) {
					$Commande->setConfiguration('calcul',$Value);
				}
              	$Commande->setValue($Value);
        	}
			
			if ($SubType == 'slider') {
				$this->writeLog('info','*** Max cmd :' . $Name . ' - id : ' . $_logicalId . ' :' . $minValue . '|' .$maxValue ,$this->getHumanName());
				$Commande->setConfiguration('minValue',$minValue);
				$Commande->setConfiguration('maxValue',$maxValue);
			}
          
			if($icon != null) {
				$Commande->setDisplay('icon', $icon);
            }
          
			if($generic_type != null) {
              	$Commande->setDisplay('generic_type', $generic_type);
            }
          
          	if ($order !=null) {
              	$Commande->setOrder($order);
            }
			
          
			$Commande->save();
		}
      

		
      	$Commande->save();
		return $Commande;
	}
  
  	private function deleteCommande($_logicalId){
      	$this->writeLog('info',' - Function delete : '.$_logicalId,$this->getHumanName());	
      	$Commande = $this->getCmd(null,$_logicalId);
		if (is_object($Commande)){
          	$this->writeLog('info',' - Suppression de la commande inutilisée : '.$_logicalId,$this->getHumanName());	
          	$Commande->remove();
        }
    }
	
	public function preSave() {
	
	}
	//public function postSave() {
  	public function postUpdate() {
					
		if ($this->getConfiguration('cfgCmdSliderLight') !='' && $this->getConfiguration('cfgLightState') !='') {			
			$cmdLightSlider=cmd::byId(str_replace('#','',$this->getConfiguration('cfgCmdSliderLight')));
			$cmdLightState=cmd::byId(str_replace('#','',$this->getConfiguration('cfgLightState')));
			if (is_object($cmdLightSlider) && is_object($cmdLightState)){			
				//$this->writeLog('info','*** Creation cmd slider luminosite et son etat***',$this->getHumanName());
				$lightState=$this->AddCommande("Etat luminosité","lightState","info",'numeric',1,$this->getConfiguration('cfgLightState'),null,'LIGHT_BRIGHTNESS',true,2);
				
				$minValue=($cmdLightSlider->getConfiguration()['minValue'] !=null ? $cmdLightSlider->getConfiguration()['minValue'] : 0);
				$maxValue=($cmdLightSlider->getConfiguration()['maxValue'] !=null ? $cmdLightSlider->getConfiguration()['maxValue'] : 100);
				$this->AddCommande("Luminosite","lightSlider","action",'slider',1,$lightState->getId(),null,'LIGHT_SLIDER',false,3,$minValue,$maxValue);
			} else {
          		$this->deleteCommande('lightState');
          		$this->deleteCommande('lightSlider');
        	}
		} else {
          	$this->deleteCommande('lightState');
          	$this->deleteCommande('lightSlider');
        }
					
		if ($this->getConfiguration('cfgCmdSliderTemp') !='' && $this->getConfiguration('cfgTempState') !='') {			
			$cmdTempSlider=cmd::byId(str_replace('#','',$this->getConfiguration('cfgCmdSliderTemp')));
			$cmdTempState=cmd::byId(str_replace('#','',$this->getConfiguration('cfgTempState')));
			if (is_object($cmdTempSlider) && is_object($cmdTempState)){			
				//$this->writeLog('info','*** Creation cmd slider température et son etat***',$this->getHumanName());
				$lightState=$this->AddCommande("Etat température","tempState","info",'numeric',1,$this->getConfiguration('cfgTempState'),null,'LIGHT_COLOR_TEMP',true,3);
				
				$minValue=($cmdTempSlider->getConfiguration()['minValue'] !=null ? $cmdTempSlider->getConfiguration()['minValue'] : 0);
				$maxValue=($cmdTempSlider->getConfiguration()['maxValue'] !=null ? $cmdTempSlider->getConfiguration()['maxValue'] : 100);
				$this->AddCommande("Temperature","tempSlider","action",'slider',1,$cmdTempState->getId(),null,'LIGHT_SET_COLOR_TEMP',false,4,$minValue,$maxValue);
			} else {
              $this->deleteCommande('tempState');
              $this->deleteCommande('tempSlider');
        	}
		} else {
          	$this->deleteCommande('tempState');
          	$this->deleteCommande('tempSlider');
        }
		
		if ($this->getConfiguration('cfgCmdcolor') !='') {			
			$cmdColor=cmd::byId(str_replace('#','',$this->getConfiguration('cfgCmdcolor')));
			if (is_object($cmdColor)){			
				//$this->writeLog('info','*** Creation cmd action couleur et son etat***',$this->getHumanName());
				$this->AddCommande("Couleur","setColor","action",'color',1,null,null,'LIGHT_SET_COLOR',false,5);
            } else {
              $this->deleteCommande('setColor');
        	}
		} else {
          	$this->deleteCommande('setColor');
        }
      
      	if ($this->getConfiguration('cfgColorState') !='') {
          	$cmdColorState=cmd::byId(str_replace('#','',$this->getConfiguration('cfgColorState')));
          	if (is_object($cmdColorState)) {
              	$colorState=$this->AddCommande("Etat couleur","colorState","info",'string',1,$this->getConfiguration('cfgColorState'),null,'LIGHT_COLOR',true,4);
            } else {
              	$this->deleteCommande('colorState');
            }
          
        } else {
          	$this->deleteCommande('colorState');
        }
		
		if ($this->getConfiguration('cfgCmdState') !='') {
			//$this->writeLog('info','*** Creation cmd action state ***',$this->getHumanName());
			$state=$this->AddCommande("Etat","state","info",'other',1,$this->getConfiguration('cfgCmdState'),null,'LIGHT_STATE',true,1);
			
			if ($this->getConfiguration('cfgCmdlightOn') !='') {
				//$this->writeLog('info','*** Creation cmd action on ***',$this->getHumanName());
				$this->AddCommande("Allumer","lightOn","action", 'other',1,$state->getId(),null,'LIGHT_ON',false,1);
			} else {
          		$this->deleteCommande('lightOn');
        	}
			
			if ($this->getConfiguration('cfgCmdlightOff') !='') {
				//$this->writeLog('info','*** Creation cmd action off ***',$this->getHumanName());
				$this->AddCommande("Eteindre","lightOff","action", 'other',1,$state->getId(),null,'LIGHT_OFF',false,2);
			} else {
          		$this->deleteCommande('lightOff');
        	}
		} else {
			if ($this->getConfiguration('cfgCmdlightOn') !='') {
				//$this->writeLog('info','*** Creation cmd action on ***',$this->getHumanName());
				$this->AddCommande("Allumer","lightOn","action", 'other',1,null,null,'LIGHT_ON',false,1);
			} else {
          		$this->deleteCommande('lightOn');
        	}
			
			if ($this->getConfiguration('cfgCmdlightOff') !='') {
				//$this->writeLog('info','*** Creation cmd action off ***',$this->getHumanName());
				$this->AddCommande("Eteindre","lightOff","action", 'other',1,null,null,'LIGHT_OFF',false,2);
			} else {
          		$this->deleteCommande('lightOff');
        	}
          	
          	$this->deleteCommande('state');
		}
		
		if ($this->getConfiguration('cfgIsReachable') !='') {
			//$this->writeLog('info','*** Creation cmd action isReachable ***',$this->getHumanName());
			$this->AddCommande("Accessible","isReachable","info",'binary',1,$this->getConfiguration('cfgIsReachable'),null,null,true,5);
		} else {
          	$this->deleteCommande('isReachable');
        }
	}	
	
	 public function toHtml($_version = 'dashboard') {
      $replace = $this->preToHtml($_version);
      //$replace=array();
      /*
      log::add('alarmeIMA_V2', 'debug',  "Function toHtml - ap pretohtml");
      if (!is_array($replace)) {
        log::add('alarmeIMA_V2', 'debug',  "Function toHtml - dans le if");
        return $replace;
        log::add('alarmeIMA_V2', 'debug',  "Function toHtml - return replace");
        
      }
      */
      	$version = jeedom::versionAlias($_version);
		$replace['#' . $replace['#id#'] . '_cfgLabel#'] = $this->getConfiguration('cfgLabel');
		$replace['#' . $replace['#id#'] . '_cfgColorLabel#'] = $this->getConfiguration('cfgColorLabel');
		$replace['#' . $replace['#id#'] . '_cfgColorBorderEq#'] = $this->getConfiguration('cfgColorBorderEq');
		$replace['#' . $replace['#id#'] . '_cfgTypeBorderEq#'] = $this->getConfiguration('cfgTypeBorderEq');
		$replace['#' . $replace['#id#'] . '_cfgLightType#'] = $this->getConfiguration('cfgLightType');
		$replace['#' . $replace['#id#'] . '_cfgDebug#'] = $this->getConfiguration('cfgDebug');
		$replace['#' . $replace['#id#'] . '_cfgWidthBorderEq#'] = $this->getConfiguration('cfgWidthBorderEq');
		$replace['#' . $replace['#id#'] . '_cfgPrefColor1#'] = $this->getConfiguration('cfgPrefColor1');
		$replace['#' . $replace['#id#'] . '_cfgPrefColor2#'] = $this->getConfiguration('cfgPrefColor2');
		$replace['#' . $replace['#id#'] . '_cfgPrefColor3#'] = $this->getConfiguration('cfgPrefColor3');
		$replace['#' . $replace['#id#'] . '_cfgPrefColor4#'] = $this->getConfiguration('cfgPrefColor4');

       
       	//get min and max value
       	$cmd=cmd::byId(str_replace('#','',$this->getConfiguration('cfgCmdSliderLight')));
		if (is_object($cmd)){
       			$replace['#' . $replace['#id#'] . '_lightSlider_MinValue#'] = ($cmd->getConfiguration()['minValue'] !=null ? $cmd->getConfiguration()['minValue'] : 0);
				$replace['#' . $replace['#id#'] . '_lightSlider_MaxValue#'] = ($cmd->getConfiguration()['maxValue'] !=null ? $cmd->getConfiguration()['maxValue'] : 100);
        }
       
		$cmd=cmd::byId(str_replace('#','',$this->getConfiguration('cfgCmdSliderTemp')));
		if (is_object($cmd)){
       			$replace['#' . $replace['#id#'] . '_tempSlider_MinValue#'] = ($cmd->getConfiguration()['minValue'] !=null ? $cmd->getConfiguration()['minValue'] : 0);
				$replace['#' . $replace['#id#'] . '_tempSlider_MaxValue#'] = ($cmd->getConfiguration()['maxValue'] !=null ? $cmd->getConfiguration()['maxValue'] : 100);
        }
         
      	$cmdis=$this->getCmd('info', null);
      	foreach ($cmdis as $cmd) {
          	$cmd_LogId=$cmd->getLogicalId(); 
          	$replace['#' . $cmd_LogId . '#'] = $cmd->execCmd();
			$replace['#' . $cmd_LogId . '_id#'] = $cmd->getId();
			$replace['#' . $cmd_LogId . '_collectDate#'] =date('d-m-Y H:i:s',strtotime($cmd->getCollectDate()));
			$replace['#' . $cmd_LogId . '_updatetime#'] =date('d-m-Y H:i:s',strtotime( $this->getConfiguration('updatetime')));
			
		}
      
      	$cmdas=$this->getCmd('action', null);
      	foreach ($cmdas as $cmd) {
          	$cmd_LogId=$cmd->getLogicalId(); 
            $replace['#' . $cmd_LogId . '_id#'] = $cmd->getId();
            if ($cmd->getConfiguration('listValue', '') != '') {
				$listOption = '';
				$elements = explode(';', $cmd->getConfiguration('listValue'));
				$foundSelect = false;
				foreach ($elements as $element) {
					$coupleArray = explode('|', $element);
                  	$item_val = $coupleArray[0];
                  	$item_text  = (isset($coupleArray[1])) ? $coupleArray[1]: $item_val;
                  
					$cmdValue = $cmd->getCmdValue();
					
                  	if (is_object($cmdValue) && $cmdValue->getType() == 'info') {
						if ($cmdValue->execCmd() == $item_val) {
                          	$valSelected=$item_text;
							$listOption .= '<option value="' . $item_val . '" selected>' . $item_text . '</option>';
							$foundSelect = true;
						} else {
							$listOption .= '<option value="' . $item_val . '">' . $item_text . '</option>';
						}
					} else {
						$listOption .= '<option value="' . $item_val . '">' . $item_text . '</option>';
					}
				}
				if (!$foundSelect) {
					$listOption = '<option value="" selected>Aucun</option>' . $listOption;
                  	$replace['#' . $cmd->getLogicalId() . '_Value#'] = 'Aucun';
				}else{
                  	$replace['#' . $cmd->getLogicalId() . '_Value#'] = $valSelected;
                }
                  
				
              	$replace['#' . $cmd->getLogicalId() . '_listValue#'] = $listOption;
			}
        }
		
       	if ($this->getConfiguration('cfgModalMode') === '1') {
          	$this->writeLog('info','#### datas evoyés a l\'affichage : mode modal ####',$this->getHumanName());
          	$html = template_replace($replace, getTemplate('core', $_version, 'default_roundSliderLight_modal', 'roundSliderLight'));
        } else {
          	$this->writeLog('info','#### datas evoyés a l\'affichage : mode normal  ####',$this->getHumanName());
          	$html = template_replace($replace, getTemplate('core', $_version, 'default_roundSliderLight', 'roundSliderLight'));
        }
          //$html = template_replace($replace, getTemplate('core', $_version, 'default_roundSliderLight', 'roundSliderLight'));
          cache::set('widgetHtml' . $_version . $this->getId(), $html, 1);
		  if ($this->getConfiguration('cfgDebug') == '1') {
			$this->writeLog('info','**************************************************************************************************************************************************','');	
			$this->writeLog('info','#### datas evoyés a l\'affichage : '.json_encode($replace). ' ####',$this->getHumanName());
			$this->writeLog('info','**************************************************************************************************************************************************','');	
		  }
          return $html;
	}
}


class roundSliderLightCmd extends cmd {
   
	public function preSave() {
		if ($this->getType() == 'info' && $this->getConfiguration('calcul') != '') {
          	$this->getEqLogic()->writeLog('info','		=> PreSave cmd : ' . $this->getName() . ' -> evaluation : ' . $this->getConfiguration('calcul'),$this->getEqLogic()->getHumanName());	
      		$this->event($this->execute());
        }
	}
	
  
    public function postSave() {
      	if ($this->getType() == 'info' && $this->getConfiguration('calcul') != '') {
          	$this->getEqLogic()->writeLog('info','		=> PostSave cmd : ' . $this->getName() . ' -> evaluation : ' . $this->getConfiguration('calcul'),$this->getEqLogic()->getHumanName());	
      		$this->event($this->execute());
        }
      
	}
  
    public function execute($_options = null) {
			
		$eqlogic = $this->getEqLogic();
		$logicalId=$this->getLogicalId();
		$eqlogic->writeLog('info','          ','');	
		$eqlogic->writeLog('info','**************************************************************************************************************************************************','');	
		$eqlogic->writeLog('info','Execution commande : ' . $this->getLogicalId() . '| id eqlogic : ' . $eqlogic->getId() .'| type : ' .$this->getType(),$this->getEqLogic()->getHumanName());	

      switch($this->getLogicalId()) {
			case "lightOn":				
				$cmd=cmd::byId(str_replace('#','',$this->getEqLogic()->getConfiguration('cfgCmdlightOn')));
				if(is_object($cmd)){
					$this->getEqLogic()->writeLog('info',' - Execution de la commande '.$cmd->getHumanName(),$this->getEqLogic()->getHumanName());	
					$cmd->execute(null);
				} else {
					$this->getEqLogic()->writeLog('error',' - Commande '.$cmd->getHumanName() . ' non définie dans le plugin',$this->getEqLogic()->getHumanName());
				}
				break;
			case "lightOff":				
				$cmd=cmd::byId(str_replace('#','',$this->getEqLogic()->getConfiguration('cfgCmdlightOff')));
				if(is_object($cmd)){
					$this->getEqLogic()->writeLog('info',' - Execution de la commande '.$cmd->getHumanName(),$this->getEqLogic()->getHumanName());	
					$cmd->execute(null);
				} else {
					$this->getEqLogic()->writeLog('error',' - Commande '.$cmd->getHumanName() . ' non définie dans le plugin',$this->getEqLogic()->getHumanName());
				}
				break;
			case "lightSlider":	
				$cmd=cmd::byId(str_replace('#','',$this->getEqLogic()->getConfiguration('cfgCmdSliderLight')));
				if(is_object($cmd)){
					$options = array('slider'=>$_options['slider']);
					$cmd->execCmd($options, $cache=0);
				} else {
					$this->getEqLogic()->writeLog('error',' - Commande '.$cmd->getHumanName() . ' non définie dans le plugin',$this->getEqLogic()->getHumanName());
				}
				break;
			case "tempSlider":	
				$cmd=cmd::byId(str_replace('#','',$this->getEqLogic()->getConfiguration('cfgCmdSliderTemp')));
				if(is_object($cmd)){
					$options = array('slider'=>$_options['slider']);
					$cmd->execCmd($options, $cache=0);
				} else {
					$this->getEqLogic()->writeLog('error',' - Commande '.$cmd->getHumanName() . ' non définie dans le plugin',$this->getEqLogic()->getHumanName());
				}
				break;
			case "setColor":
          		$cmd=cmd::byId(str_replace('#','',$this->getEqLogic()->getConfiguration('cfgCmdcolor')));
          		
				if(is_object($cmd)){
                  	$oldValue = $cmd->execCmd();
					
                  	if ($oldValue == $_options['color']) {
                      	$this->getEqLogic()->writeLog('error',' - Commande '.$cmd->getHumanName() . ' non executée car la nouvelle valeur est la même que l\'ancienne',$this->getEqLogic()->getHumanName());
                    } else {
                      	$options = array('color'=>$_options['color']);
                      	log::add('roundSliderLight','debug',' - couleur demandee : ' . json_encode($options));
                      	$cmd->execCmd($options, $cache=0);
                    }
					
				} else {
					$this->getEqLogic()->writeLog('error',' - Commande '.$cmd->getHumanName() . ' non définie dans le plugin',$this->getEqLogic()->getHumanName());
				}
				break;
			case "lightState":
          	case "tempState":
          	case "colorState":
          	case "state":
				$result = jeedom::evaluateExpression($this->getConfiguration('calcul'));
				$this->getEqLogic()->checkAndUpdateCmd($this->getLogicalId(),$result);
          		return $result;
				break;
			case "isReachable":
				$result = jeedom::evaluateExpression($this->getConfiguration('calcul'));
				if ($result == '0') {
					$this->getEqLogic()->checkAndUpdateCmd('isReachable',false);
					return false;
				} else {
					$this->getEqLogic()->checkAndUpdateCmd('isReachable',true);
					return true;
				}
				break;
        }
    }
}
?>