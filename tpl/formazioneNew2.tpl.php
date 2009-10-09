<?php $j =0; $k = 0;$ruolo="" ?>
<?php echo "<pre>" . print_r($_POST,1) . "</pre>"; ?>
<div id="formazione" class="main-content" style="position:relative;">
	<?php  if(TIMEOUT): ?>
		<h3>Giornata <?php echo GIORNATA; ?></h3>
		<form action="<?php echo $this->linksObj->getLink('formazioneNew2') ?>" method="post">
			<div id="campo" class="column">
				<div id="portiere" class="droppable" name="P"></div>
				<div id="difensori" class="droppable" name="D"></div>
				<div id="centrocampisti" class="droppable" name="C"></div>
				<div id="attaccanti" class="droppable" name="A"></div>
			</div>
				<div id="giocatori" class="column" >
				<?php foreach($this->giocatori as $key=>$val): ?>
					<?php if($ruolo != $val['ruolo']) echo '<div style="clear:both;line-height:1px">&nbsp;</div>' ?>
					<div id="<?php echo $val['idGioc'] ?>" name="<?php echo $val['ruolo']; ?>" class="draggable giocatore <?php if((!empty($this->titolari) && in_array($val['idGioc'],$this->titolari)) || (!empty($this->panchinari) && in_array($val['idGioc'],$this->panchinari))) echo 'hidden' ?> <?php echo $val['ruolo']; ?>">
						<img width="40" src="imgs/foto/<?php echo $val['idGioc']; ?>.jpg" />
						<p><?php echo $val['cognome'] . ' ' . $val['nome']; ?></p>
					</div>
					<?php if($ruolo != $val['ruolo'])$ruolo = $val['ruolo']; ?>
				<?php $j++; endforeach; ?>
				</div>
				<div id="panchina" class="column">
					<?php for($i=0;$i<6;$i++): ?>
						<div id="panch-<?php echo $i ?>" class="droppable"></div>
					<?php endfor; ?>
				</div>
				<div id="field">
					<?php for($i=0;$i<11;$i++): ?>
						<input <?php if(isset($this->titolari[$i])) echo 'value="' . $this->titolari[$i] . '" title="' . $this->giocatori[$this->titolari[$i]]['cognome'] . ' ' . $this->giocatori[$this->titolari[$i]]['nome'] . '" rel="' . $this->giocatori[$this->titolari[$i]]['ruolo'] . '"' ?> id="gioc-<?php echo $i; ?>" type="hidden" name="gioc[<?php echo $i; ?>]" />
					<?php endfor; ?>
				</div>
				<div id="panchina-field">
					<?php for($i=0;$i<7;$i++): ?>
						<input <?php if(isset($this->panchinari[$i])) echo 'value="' . $this->panchinari[$i] . '" title="' . $this->giocatori[$this->panchinari[$i]]['cognome'] . ' ' . $this->giocatori[$this->panchinari[$i]]['nome'] . '" rel="' . $this->giocatori[$this->panchinari[$i]]['ruolo'] . '"' ?> id="panch-<?php echo $i; ?>" type="hidden" name="panch[<?php echo $i; ?>]" />
					<?php endfor; ?>
				</div>
		<?php endif; ?>
		<script type="text/javascript">
		$(document).ready(function(){
				var modulo = Array();
				modulo['P'] = <?php echo $this->modulo[0]; ?>;
				modulo['D'] = <?php echo $this->modulo[1]; ?>;
				modulo['C'] = <?php echo $this->modulo[2]; ?>;
				modulo['A'] = <?php echo $this->modulo[3]; ?>;
				ruolo = 'P';
				j = 0;
				k = 0;
				list = $("#field").find("input");
				list.each(function (i) {
					if($(list[i]).attr('rel') != ruolo)
					{
						j++;
						k = 0;
					}
					$("#campo div.droppable[name=" +$(list[i]).attr('rel')+"]").append('<div style="position:absolute;left:' + ((((554-(70 * modulo[$(list[i]).attr('rel')])) / (modulo[$(list[i]).attr('rel')] + 1 )) * (k+1)) + ((k+1) * 70)-80) + 'px;top:' + ((140 * j) + 60) + 'px" id="'+ $(list[i]).attr('value') +'" name="'+ $(list[i]).attr('rel') +'" class="embed giocatore draggable ui-draggable '+ $(list[i]).attr('rel') +'"><img width="40" src="imgs/foto/' + $(list[i]).attr('value') + '.jpg" /><p>' + $(list[i]).attr('title') + '</p></div>');
					ruolo = $(list[i]).attr('rel');
					k++;
				});
				list = $("#panchina-field").find("input[value!='']");
				list.each(function (i) {
					$("#panchina .droppable[id=panch-" + i + "]").append('<div id="'+ $(list[i]).attr('value') +'" name="'+ $(list[i]).attr('rel') +'" class="embed giocatore draggable ui-draggable '+ $(list[i]).attr('rel') +'"><img width="40" src="imgs/foto/' + $(list[i]).attr('value') + '.jpg" /><p>' + $(list[i]).attr('title') + '</p></div>');
				});
			$(".draggable").draggable({
				helper:"clone",opacity:0.5,revert:true
			});
			var data = new Array();
					data['P']=1;
					data['D']=5;
					data['C']=5;
					data['A']=3;
			$('#campo .droppable').droppable({
				accept: function(draggable) {
					var nome = $(this).attr('name');
					if($(draggable).attr('name') == nome) {
					var nPor = 0;
					var nDif = 0;
					var nCen = 0;
					var nAtt = 0;
					var nTot = 0;
					var n = 0;
				
					$(this).find("div").each(function () {
						n++;
					});
					$(this).parent().find("div.embed").each(function () {
						nTot++;
					});
					$(this).parent().find("div.P").each(function () {
						nPor++;
					});
					$(this).parent().find("div.D").each(function () {
						nDif++;
					});
					$(this).parent().find("div.C").each(function () {
						nCen++;
					});
					$(this).parent().find("div.A").each(function () {
						nAtt++;
					});
						if(nDif <= 2)
						{ 
							data['D'] = 5;
							data['C'] = 5;
							data['A'] = 3;
							if(nCen == 4)
							{
								if(nAtt == 3)
								{
									data['D'] = 3;
									data['C'] = 4;
									data['A'] = 3;
								}
								
							}
						}
						if(nDif == 3)
						{ 
							data['D'] = 5;
							data['C'] = 5;
							data['A'] = 3;
							if(nCen == 5)
							{
								data['D'] = 4;
								data['C'] = 5;
								data['A'] = 2;
								if(nAtt == 2)
								{
									data['D'] = 3;
									data['C'] = 5;
									data['A'] = 2;
								}
								
							}
							if(nAtt == 3)
							{
								data['D'] = 4;
								data['C'] = 4;
								data['A'] = 3;
								if(nCen == 4)
								{
									data['D'] = 3;
									data['C'] = 4;
									data['A'] = 3;
								}
							}
						}
						if(nDif == 4)
						{
							data['D'] = 5;
							data['C'] = 5;
							data['A'] = 3;
							if(nCen == 5)
							{
								data['D'] = 4;
								data['C'] = 5;
								data['A'] = 1;
							}
							else 
							{
								if(nCen == 4)
								{
									data['D'] = 5;
									data['C'] = 5;
									data['A'] = 2;
									if(nAtt == 2)
									{
										data['D'] = 4;
										data['C'] = 4;
										data['A'] = 2;
									}
								}
								else
								{
									if(nCen <= 3)
									{
										data['D'] = 5;
										data['C'] = 5;
										data['A'] = 3;
										if(nAtt == 3)
										{
											data['D'] = 4;
											data['C'] = 3;
											data['A'] = 3;
										}
									}
								}
							}
						}
						if(nDif == 5)
						{ 
							data['D'] = 5;
							data['C'] = 4;
							data['A'] = 2;
							if(nCen == 4)
							{
								data['D'] = 5;
								data['C'] = 4;
								data['A'] = 1;
							}
							else
							{
								if(nAtt == 2)
								{
									data['D'] = 5;
									data['C'] = 3;
									data['A'] = 2;
								}
							}
						}
				
					$("#pp").empty();
					$("#pp").append(nPor);
					$("#dd").empty();
					$("#dd").append(nDif);
					$("#cc").empty();
					$("#cc").append(nCen);
					$("#aa").empty();
					$("#aa").append(nAtt);
					$("#tt").empty();
					$("#tt").append(nTot);
				
				
				
				
					var numMax = data[($(this).attr('name'))];
					$("#nn").empty();
					$("#nn").append(numMax);
					if(n < numMax && nTot < 11 && $(draggable).attr('name') == nome || $(draggable).is('.embed'))
						return true;
					}
				},
				activeClass: 'droppable-active',
				hoverClass: 'droppable-hover',
				/*activate:  function(draggable) { $('.embed').droppable('disable'); },
				deactivate:  function(draggable) { $('.embed').droppable('enable'); },
				over:function(draggable) { $('.embed').droppable('enable'); },
				greedy: false,*/
				drop: function(ev,ui) {
							$(this).append('<div id="'+ui.draggable.attr('id') +'" name="'+ ui.draggable.attr('name') +'" style="'+ ui.helper.attr('style') +'" class="embed '+ui.draggable.attr('class')+'"><img width="40" src="imgs/foto/' + ui.draggable.attr('id') + '.jpg" /><p>' + $(ui.draggable).text() + '</p></div>');
							$(this).children('div').css('opacity','1');
							if((ui.draggable).parent().attr('id') == 'giocatori')
								$(ui.draggable).addClass('hidden');
							else
								$(ui.draggable).remove();
							$(ui.helper).remove();
							list = $("#field").find("input");
							list.each(function (i) {
								$(list[i]).removeAttr('value');
							});
							lista = $("#campo").find("div.embed");
							lista.each(function (i) {
								$("#field #gioc-" + i).attr('value',$(lista[i]).attr('id'));
							});
							$(".draggable").draggable({
								helper:"clone",opacity:0.5,revert:true
							});
						
					}
				});
				$('#giocatori').droppable({
				activeClass: 'droppable-active',
				hoverClass: 'droppable-hover',
				/*activate:  function(draggable) { $('.embed').droppable('disable'); },
				deactivate:  function(draggable) { $('.embed').droppable('enable'); },
				over:function(draggable) { $('.embed').droppable('enable'); },
				greedy: false,*/
				drop: function(ev,ui) {
							if((ui.draggable).parent().attr('id') == 'giocatori')
								$(ui.draggable).addClass('hidden');
							else
								$(ui.draggable).remove();
							$(ui.helper).remove();
							$("#giocatori #" + ui.draggable.attr('id')).removeClass("hidden");
							list = $("#field").find("input");
							list.each(function (i) {
								$(list[i]).removeAttr('value');
							});
							lista = $("#campo").find("div.embed");
							lista.each(function (i) {
								$("input[name=gioc[" + i + "]]").attr('value',$(lista[i]).attr('id'));
							});
							list = $("#panchina-field").find("input");
							list.each(function (i) {
								$(list[i]).removeAttr('value');
							});
							lista = $("#panchina").find("div.embed");
							lista.each(function (i) {
								$("input[name=panch[" + i + "]]").attr('value',$(lista[i]).attr('id'));
							});
							$(".draggable").draggable({
								helper:"clone",opacity:0.5,revert:true
							});
						
					}
				});
			
				$('#panchina .droppable').droppable({
				accept: function(draggable) {
					var n = 0;
					$(this).find("div").each(function () {
						n++;
					});
					if(n== 0)
						return true;
				},
				activeClass: 'droppable-active',
				hoverClass: 'droppable-hover',
				/*activate:  function(draggable) { $('.embed').droppable('disable'); },
				deactivate:  function(draggable) { $('.embed').droppable('enable'); },
				over:function(draggable) { $('.embed').droppable('enable'); },
				greedy: false,*/
				drop: function(ev,ui) {
							ui.draggable.removeClass('embed');
								$(this).append('<div id="'+ui.draggable.attr('id') +'" name="'+ ui.draggable.attr('name') +'" style="margin:auto;float:none;" class="embed '+ui.draggable.attr('class')+'"><img width="40" src="imgs/foto/' + ui.draggable.attr('id') + '.jpg" /><p>' + $(ui.draggable).children('p').text() + '</p></div>');
							$(this).children('div').css('opacity','1');
							if((ui.draggable).parent().attr('id') == 'giocatori')
								$(ui.draggable).addClass('hidden');
							else
								$(ui.draggable).remove();
							$(ui.helper).remove();
							list = $("#panchina-field").find("input");
							list.each(function (i) {
								$(list[i]).removeAttr('value');
							});
							lista = $("#panchina").find("div.embed");
							lista.each(function (i) {
								$("input[name=panch[" + i + "]]").attr('value',$(lista[i]).attr('id'));
							});
							$(".draggable").draggable({
								helper:"clone",opacity:0.5,revert:true
							});
						
					}
				});
			});
		</script>
		<input name="button" type="submit" class="button" value="Invia">
		</form>
</div>
<div id="squadradett" class="column last">
	<div class="box2-top-sx column last">
	<div class="box2-top-dx column last">
	<div class="box2-bottom-sx column last">
	<div class="box2-bottom-dx column last">
	<div class="box-content column last">
		<?php if(!TIMEOUT): //NON PIÙ NECESSARIO QUESTO IF PER L'HEADER LOCATION NELLA CODE' ?>
		<div id="messaggio" class="messaggio neut column last" >
			<img alt="!" src="<?php echo IMGSURL.'attention-big.png'; ?>" />
			<span>Non puoi effettuare operazioni in questo momento.Aspetta la fine delle partite</span>
		</div>
		<?php endif; ?>
		<?php if(isset($this->message) && $this->message[0] == 0): ?>
		<div id="messaggio" class="messaggio good column last">
			<img alt="OK" src="<?php echo IMGSURL.'ok-big.png'; ?>" />
			<span><?php echo $this->message[1]; ?></span>
		</div>
		<?php elseif(isset($this->message) && $this->message[0] == 1): ?>
		<div id="messaggio" class="messaggio bad column last">
			<img alt="!" src="<?php echo IMGSURL.'attention-bad-big.png'; ?>" />
			<span><?php echo $this->message[1]; ?></span>
		</div>
		<?php elseif($this->issetForm != FALSE):  ?>
			<div id="messaggio" class="messaggio neut column last">
				<img alt="!" src="<?php echo IMGSURL.'attention-big.png'; ?>" title="Attenzione!" />
				<span>Hai già impostato la formazione. Se la rinvii quella vecchia verrà sovrascritta</span>
			</div>
		<?php endif; ?>
		<?php if($this->issetForm != FALSE || isset($this->message)): ?> 
		<script type="text/javascript">
		window.onload = (function(){
 			$("#messaggio").effect("pulsate", { times: 3 }, 1000);
			$("#messaggio").click(function () {
				$("div#messaggio").fadeOut("slow");
			});
 		});
		</script>
		<?php endif; ?>
		<?php if($_SESSION['logged'] == TRUE): ?>
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
		<?php endif; ?>
		<form class="column last" name="form_modulo" action="<?php echo $this->linksObj->getLink('formazione'); ?>" method="post">
			<fieldset id="modulo" class="no-margin fieldset">
				<h3 class="no-margin">Seleziona il modulo:</h3>
				<select name="mod" onchange="document.form_modulo.submit();">
					<?php if(!isset($this->mod)): ?><option></option><?php endif; ?>
					<option value="1-4-4-2" <?php if ($this->mod == '1-4-4-2') echo "selected=\"selected\""?>>4-4-2</option>
					<option value="1-3-5-2" <?php if ($this->mod == '1-3-5-2') echo "selected=\"selected\""?>>3-5-2</option>
					<option value="1-3-4-3" <?php if ($this->mod == '1-3-4-3') echo "selected=\"selected\""?>>3-4-3</option>
					<option value="1-4-5-1" <?php if ($this->mod == '1-4-5-1') echo "selected=\"selected\""?>>4-5-1</option>
					<option value="1-4-3-3" <?php if ($this->mod == '1-4-3-3') echo "selected=\"selected\""?>>4-3-3</option>
					<option value="1-5-4-1" <?php if ($this->mod == '1-5-4-1') echo "selected=\"selected\""?>>5-4-1</option>
					<option value="1-5-3-2" <?php if ($this->mod == '1-5-3-2') echo "selected=\"selected\""?>>5-3-2</option>
				</select>
			</fieldset>
		</form>
		<form class="right last" name="formazione_other" action="<?php echo $this->linksObj->getLink('altreFormazioni'); ?>" method="post">
			<fieldset class="no-margin fieldset">
			  <input type="hidden" name="p" value="formazioniAll" />
				<h3 class="no-margin">Guarda le altre formazioni</h3>
				<?php if(empty($this->formazioniImpostate)): ?>
					<select name="squadra" disabled="disabled">
						<option>Nessuna form. impostata</option>
				<?php else:?>
					<select name="squadra" onchange="document.formazione_other.submit();">
						<option value="<?php echo $_SESSION['idSquadra']; ?>"></option>
					<?php foreach($this->formazioniImpostate as $key => $val): ?>
						<option <?php if($this->squadra == $val['idUtente']) echo "selected=\"selected\"" ?> value="<?php echo $val['idUtente']?>"><?php echo $val['nome']?></option>
					<?php endforeach;?>
				<?php endif; ?>
				</select>
			</fieldset>
			<fieldset class="no-margin fieldset max-large">
				<h3 class="no-margin">Guarda la formazione della giornata</h3>
					<select name="giorn" onchange="document.formazione_other.submit();">
						<?php for($j = GIORNATA ; $j  > 0 ; $j--): ?>
							<option <?php if(GIORNATA == $j) echo "selected=\"selected\"" ?>><?php echo $j; ?></option>
						<?php endfor; ?>
				</select>
			</fieldset>
		</form>
	</div>
	</div>
	</div>
	</div>
	</div>
</div>