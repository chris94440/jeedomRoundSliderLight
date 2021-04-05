<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('roundSliderLight');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">    
   	<div class="col-xs-12 eqLogicThumbnailDisplay">
  		<legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoPrimary" data-action="add">
				<i class="fas fa-plus-circle"></i>
				<br>
				<span>{{Ajouter}}</span>
			</div>
      			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
      				<i class="fas fa-wrench"></i>
    				<br>
    				<span>{{Configuration}}</span>
  			</div>
  		</div>
  		<legend><i class="fas fa-table"></i> {{Mes lampes connectées}}</legend>
	   	<input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
		<div class="eqLogicThumbnailContainer">
    		<?php
			foreach ($eqLogics as $eqLogic) {
				$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
				echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
				echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
				echo '<br>';
				echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
				echo '</div>';
			}
		?>
		</div>
	</div>
	<div class="col-xs-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure">
					<i class="fa fa-cogs"></i>
					 {{Configuration avancée}}
				</a>
				<a class="btn btn-default btn-sm eqLogicAction" data-action="copy">
					<i class="fas fa-copy"></i>
					 {{Dupliquer}}
				</a>
				<a class="btn btn-sm btn-success eqLogicAction" data-action="save">
					<i class="fas fa-check-circle"></i>
					 {{Sauvegarder}}
				</a>
				<a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove">
					<i class="fas fa-minus-circle"></i>
					 {{Supprimer}}
				</a>
			</span>
		</div>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation">
				<a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay">
					<i class="fa fa-arrow-circle-left"></i>
				</a>
			</li>
			<li role="presentation" class="active">
				<a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab">
				<i class="fa fa-tachometer"></i> 
					{{Equipement}}
				</a>
			</li>
			<li role="presentation">
				<a href="#configTabLight" aria-controls="home" role="tab" data-toggle="tab">
				<i class="fa fa-tachometer"></i> 
					{{Lampe}}
				</a>
			</li>
			<li role="presentation" >
				<a href="#configTabDesign" aria-controls="home" role="tab" data-toggle="tab">
				<i class="fa fa-tachometer"></i> 
					{{Design tuile}}
				</a>
			</li>
			<li role="presentation">
				<a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab">
					<i class="fa fa-list-alt"></i> 
					{{Commandes}}
				</a>
			</li>
  		</ul>
		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<div class="col-sm-6">
    					<form class="form-horizontal">
						<legend>Général</legend>
							<fieldset>
								<div class="form-group ">
									<label class="col-sm-3 control-label">{{Nom de la lampe}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="{{Indiquer le nom de votre lampe}}" style="font-size : 1em;color:grey;"></i>
										</sup>
									</label>
									<div class="col-sm-3">
										<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
										<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom lampe}}"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" >{{Objet parent}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="{{Indiquer l'objet dans lequel le widget de cette zone apparaîtra sur le Dashboard}}" style="font-size : 1em;color:grey;"></i>
										</sup>
									</label>
									<div class="col-sm-3">
										<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
											<option value="">{{Aucun}}</option>
											<?php
												foreach (jeeObject::all() as $object) 
													echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
											?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">
										{{Catégorie}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="{{Choisir une catégorie. Cette information n'est pas obigatoire mais peut être utile pour filtrer les widgets}}" style="font-size : 1em;color:grey;"></i>
										</sup>
									</label>
									<div class="col-sm-9">
										<?php
										foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
											echo '<label class="checkbox-inline">';
											echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
											echo '</label>';
										}
										?>

									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" >
										{{Etat du widget}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="{{Choisir les options de visibilité et d'activation. Si l'équipement n'est pas activé, il ne sera pas utilisable dans Jeedom ni visible sur le Dashboard. Si l'équipement n'est pas visible, il sera caché sur le Dashboard}}" style="font-size : 1em;color:grey;"></i>
										</sup>
									</label>
									<div class="col-sm-9">
										<label class="checkbox-inline">
											<input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>
											{{Activer}}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>
											{{Visible}}
										</label>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane" id="configTabLight">
					<br/>
					<div class="row">
						<div class="col-sm-7">
							<form class="form-horizontal">
							<fieldset>
								<legend>Contrôle de la lampe connectée</legend>
								<div class="form-group">
									<label class="col-md-6 control-label">{{Commande de gestion de la luminosité}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="{{Sélectionner la commande permettant de gérer la luminosité}}"></i>
										</sup>
									</label>
									<div class="col-md-6">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control CmdAction" data-l1key="configuration" data-l2key="cfgCmdSliderLight" placeholder="{{Séléctionner une commande}}"/>
											<span class="input-group-btn">
												<a class="btn btn-success btn-sm listCmdAction" data-type="action">
													<i class="fa fa-list-alt"></i>
												</a>
											</span>
										</div>
									</div>
									<label class="col-md-6 control-label">{{Etat de la luminosite}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="{{Sélectionner la commande permettant de récupérer l'état de la luminosité}}"></i>
										</sup>
									</label>
									
									<div class="col-md-6">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control CmdAction" data-l1key="configuration" data-l2key="cfgLightState" placeholder="{{Séléctionner une commande}}"/>
											<span class="input-group-btn">
												<a class="btn btn-success btn-sm listCmdAction" data-type="info">
													<i class="fa fa-list-alt"></i>
												</a>
											</span>
											
										</div>
									</div>	
								</div>	
								<div class="form-group">
									<label class="col-md-6 control-label">{{Commande de gestion de la témpérature}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="{{Sélectionner la commande permettant de gérer la température}}"></i>
										</sup>
									</label>
									<div class="col-md-6">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control CmdAction" data-l1key="configuration" data-l2key="cfgCmdSliderTemp" placeholder="{{Séléctionner une commande}}"/>
											<span class="input-group-btn">
												<a class="btn btn-success btn-sm listCmdAction" data-type="action">
													<i class="fa fa-list-alt"></i>
												</a>
											</span>
										</div>
									</div>
									<label class="col-md-6 control-label">{{Etat de la température}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="{{Sélectionner la commande permettant de récupérer l'état de la température}}"></i>
										</sup>
									</label>
									
									<div class="col-md-6">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control CmdAction" data-l1key="configuration" data-l2key="cfgTempState" placeholder="{{Séléctionner une commande}}"/>
											<span class="input-group-btn">
												<a class="btn btn-success btn-sm listCmdAction" data-type="info">
													<i class="fa fa-list-alt"></i>
												</a>
											</span>
											
										</div>
									</div>	
								</div>
								<div class="form-group">
									<label class="col-md-6 control-label">{{Commande de gestion de la couleur}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="{{Sélectionner la commande permettant de gérer la couleur}}"></i>
										</sup>
									</label>
									<div class="col-md-6">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control CmdAction" data-l1key="configuration" data-l2key="cfgCmdcolor" placeholder="{{Séléctionner une commande}}"/>
											<span class="input-group-btn">
												<a class="btn btn-success btn-sm listCmdAction" data-type="action">
													<i class="fa fa-list-alt"></i>
												</a>
											</span>
											
										</div>
									</div>
									<label class="col-md-6 control-label">{{Etat de la couleur}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="{{Sélectionner la commande permettant de récupérer l'état de la couleur}}"></i>
										</sup>
									</label>
									
									<div class="col-md-6">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control CmdAction" data-l1key="configuration" data-l2key="cfgColorState" placeholder="{{Séléctionner une commande}}"/>
											<span class="input-group-btn">
												<a class="btn btn-success btn-sm listCmdAction" data-type="info">
													<i class="fa fa-list-alt"></i>
												</a>
											</span>
											
										</div>
									</div>	
								</div>
								<div class="form-group" >
									<label class="col-md-6 control-label">{{La lampe est elle accessible}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="{{La notion d'accessibilité de la lampe .. permet de savoir si on peut la piloter}}"></i>
										</sup>
									</label>
									<div class="col-md-6">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control CmdAction" data-l1key="configuration" data-l2key="cfgIsReachable" placeholder="{{Séléctionner une commande}}"/>
											<span class="input-group-btn">
												<a class="btn btn-success btn-sm listCmdAction" data-type="info">
													<i class="fa fa-list-alt"></i>
												</a>
											</span>
											
										</div>
									</div>
								</div>
								<div class="form-group" data-l1key="configuration" data-l2key="cfgGrpLightOnOff">
									<label class="col-md-6 control-label">{{Statut de la lampe}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="{{Sélectionner la commande permettent de récupérer l'état de la lampe (allumée ou éteinte)}}"></i>
										</sup>
									</label>
									<div class="col-md-6">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control CmdAction" data-l1key="configuration" data-l2key="cfgCmdState" placeholder="{{Séléctionner une commande}}"/>
											<span class="input-group-btn">
												<a class="btn btn-success btn-sm listCmdAction" data-type="info">
													<i class="fa fa-list-alt"></i>
												</a>
											</span>
											
										</div>
									</div>
									<label class="col-md-6 control-label">{{Allumage lampe}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="{{Sélectionner la commande permettent d'allumer la lampe}}"></i>
										</sup>
									</label>
									<div class="col-md-6">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control CmdAction" data-l1key="configuration" data-l2key="cfgCmdlightOn" placeholder="{{Séléctionner une commande}}"/>
											<span class="input-group-btn">
												<a class="btn btn-success btn-sm listCmdAction" data-type="action">
													<i class="fa fa-list-alt"></i>
												</a>
											</span>
											
										</div>
									</div>
									<label class="col-md-6 control-label">{{Eteindre lampe}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="{{Sélectionner la commande permettent d'éteindre la lampe}}"></i>
										</sup>
									</label>
									<div class="col-md-6">
										<div class="input-group">
											<input type="text" class="eqLogicAttr form-control CmdAction" data-l1key="configuration" data-l2key="cfgCmdlightOff" placeholder="{{Séléctionner une commande}}"/>
											<span class="input-group-btn">
												<a class="btn btn-success btn-sm listCmdAction" data-type="action">
													<i class="fa fa-list-alt"></i>
												</a>
											</span>
											
										</div>
									</div>							
								</div>
								<div class="form-group" data-l1key="configuration" data-l2key="cfgGrpPrefColor">
									<label class="col-md-6 control-label">{{Préférence couleur 1}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="{{Saisissez la couleur de préférence 1}}"></i>
										</sup>
									</label>
									<div class="col-md-1">											
										 <input type="color" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="cfgPrefColor1" value="">
									</div>
									<label class="col-md-6 control-label">{{Préférence couleur 2}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="{{Saisissez la couleur de préférence 2}}"></i>
										</sup>
									</label>
									<div class="col-md-1">											
										 <input type="color" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="cfgPrefColor2" value="">
									</div>	
									<label class="col-md-6 control-label">{{Préférence couleur 3}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="{{Saisissez la couleur de préférence 3}}"></i>
										</sup>
									</label>
									<div class="col-md-1">											
										 <input type="color" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="cfgPrefColor3" value="">
									</div>	
									<label class="col-md-6 control-label">{{Préférence couleur 4}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="{{Saisissez la couleur de préférence 4}}"></i>
										</sup>
									</label>
									<div class="col-md-1">											
										 <input type="color" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="cfgPrefColor4" value="">
									</div>	
								</div>
								</br>
								</br>
								<div class="form-group" >
									<label class="col-sm-3 control-label" >
										{{Mode debug ?}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="{{Permet d'afficher plus de traces dans la log du plugin et dans la console du navigateur}}"></i>
										</sup>
									</label>
									<div class="col-sm-5">
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="cfgDebug"/>
									</div>
								</div>
							</fieldset>
						</form>
						</div>
						<div id="infoNodeLampe" class="col-sm-4">
							<fieldset>
								<legend>{{Informations}}</legend>
								<div class="form-group">
									<div class="alert alert-info">
										{{Prérequis : }}</div>
									<div id="div_instruction_lampe"></div>
								</div>								
							</fieldset>					
						</div> 
					</div>
				</div>
				<div role="tabpanel" class="tab-pane" id="configTabDesign">
					<br/>
					<div class="row">
						<div class="col-sm-7">
							<form class="form-horizontal">
								<fieldset>
									<div class="form-group">
										<label class="col-sm-3 control-label" >
											{{Afficher label de l'équipement}}
											<sup>
												<i class="fa fa-question-circle tooltips" title="{{Voulez-vous afficher un label sur le design de l'équipement ?}}"></i>
											</sup>
										</label>
										<div class="col-sm-5">
											<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="cfgHasEqLabel"/>
										</div>
									</div>
									<div class="form-group" data-l1key="configuration" data-l2key="cfgFormLabel">
										<label class="col-md-6 control-label">{{Label équipement}}
											<sup>
												<i class="fa fa-question-circle tooltips" title="{{Saisissez le nom à afficher sur la tuile}}"></i>
											</sup>
										</label>
										<div class="col-md-6">
											<div class="input-group">
												<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="cfgLabel" placeholder="{{Saisir le nom à afficher}}"/>
											</div>
										</div>
									</div>
									<div class="form-group" data-l1key="configuration" data-l2key="cfgFormColorLabel">
										<label class="col-md-6 control-label">{{Couleure du label}}
											<sup>
												<i class="fa fa-question-circle tooltips" title="{{Saisissez la couleur du label}}"></i>
											</sup>
										</label>
										<div class="col-md-1">											
											 <input type="color" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="cfgColorLabel" value="">
										</div>
									</div>
									<div class="form-group" >
										<label class="col-sm-3 control-label" >
											{{Afficher bordure équipement}}
											<sup>
												<i class="fa fa-question-circle tooltips" title="{{Voulez-vous afficher une bordure autour de votre équipement}}"></i>
											</sup>
										</label>
										<div class="col-sm-5">
											<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="cfgHasEqBorder"/>
										</div>
									</div>
									<div class="form-group" data-l1key="configuration" data-l2key="cfgFormColorBorder">
										<label class="col-md-6 control-label">{{Couleur bordure équipement}}
											<sup>
												<i class="fa fa-question-circle tooltips" title="{{Saisissez la couleur de la bordure}}"></i>
											</sup>
										</label>
										<div class="col-md-1">											
											 <input type="color" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="cfgColorBorderEq" value="">
										</div>										
									</div>
									<div class="form-group" data-l1key="configuration" data-l2key="cfgFormBorder">
										<label class="col-md-6 control-label">{{Type bordure équipement}}
											<sup>
												<i class="fa fa-question-circle tooltips" title="{{Saisissez la type de bordure}}"></i>
											</sup>
										</label>
										<div class="col-md-6">
											<div class="input-group">
												<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="cfgTypeBorderEq" placeholder="{{Saisir le type de bordure}}"/>
											</div>
										</div>
									</div>		
									<div class="form-group" data-l1key="configuration" data-l2key="cfgFormWidthBorder">
										<label class="col-md-6 control-label">{{Epaisseur bordure équipement}}
											<sup>
												<i class="fa fa-question-circle tooltips" title="{{Saisissez l'épaisseur de la bordure}}"></i>
											</sup>
										</label>
										<div class="col-md-6">
											<div class="input-group">
												<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="cfgWidthBorderEq" placeholder="{{Saisir le type de bordure}}"/>
											</div>
										</div>
									</div>						
									</br>
									<div class="form-group">
										<label class="col-md-6 control-label">{{Image de fond}}	</label>
										<div class="col-md-6">
												<div class="col-md-6">
												<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="cfgLightType">
													<option value="ampoule">{{Ampoule}}</option>                  
													<option value="lampadaire">{{Lampadaire}}</option>                  
													<option value="applique1">{{Applique modèle 1}}</option>   
													<option value="applique2">{{Applique modèle 2}}</option>   
													<option value="lampe">{{Plafonier modèle 1}}</option>   
													<option value="lampe2">{{Plafonier modèle 2}}</option>
													<option value="lampe3">{{Plafonier modèle 3}}</option> 												
													<option value="spot">{{Spot}}</option>   
												</select>
											</div>
										</div>
									</div>
									</br>
									</br>
									<div class="form-group">
										<label class="col-sm-3 control-label" >
											{{Utilisation en mode modal}}
											<sup>
												<i class="fa fa-question-circle tooltips" title="{{Voulez-vous afficher en mode modal les fonctions de l'équipements ?}}"></i>
											</sup>
										</label>
										<div class="col-sm-5">
											<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="cfgModalMode"/>
										</div>
									</div>	
								</fieldset>
							</form>
						</div>
						<div id="infoNodeDesign" class="col-sm-4">
							<fieldset>
								<legend>{{Informations}}</legend>
								<div class="form-group">
									<div class="alert alert-info">
										{{Prérequis : }}</div>
									<div id="div_instruction_design"></div>
								</div>
								<center>
								</br>
								<img src="core/img/no_image.gif" data-original=".png" id="img_type" class="img-responsive" style="max-height : 200px;"  onerror=""/>
								</center>								
							</fieldset>					
						</div> 
					</div>

				</div>
<div role="tabpanel" class="tab-pane" id="commandtab">
<legend>
<center class="title_cmdtable">{{Tableau de commandes <?php echo ' - '.$plugName.': ';?>}}
	<span class="eqName"></span>
</center>
</legend>

<legend><i class="fas fa-info-circle"></i>  {{Infos}}</legend>
	
	<table id="table_cmdi" class="table table-bordered table-condensed ">

		<thead>
			<tr>
				<th style="width: 40px;">Id</th>
				<th style="width: 280px;">{{Nom}}</th>
				<th style="width: 100px;">{{Type}}</th>
				<th style="width: 220px;">{{Options}}</th>
				<th style="width: 80px;">{{Action}}</th>
				 
			</tr>
		</thead>
		<tbody></tbody>
	</table>

	<legend><i class="fas fa-list-alt"></i>  {{Actions}}</legend>
	<table id="table_cmda" class="table table-bordered table-condensed">
		
		<thead>
			<tr>
				<th style="width: 40px;">Id</th>
				<th style="width: 280px;">{{Nom}}</th>
				<th style="width: 100px;">{{Type}}</th>
				<th style="width: 220px;">{{Options}}</th>
				<th style="width: 80px;">{{Action}}</th>
				 
			</tr>
		</thead>
		<tbody></tbody>
	</table>


</div>
			</div>
		</div>
</div>

<?php include_file('desktop', 'roundSliderLight', 'js', 'roundSliderLight'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>