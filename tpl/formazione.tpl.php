<?php $j =0; $k = 0; $ruolo = ""; ?>
<?php  if(!PARTITEINCORSO): ?>
<h3>Giornata <?php echo GIORNATA; ?></h3>
<form action="<?php echo $this->linksObj->getLink('formazione'); ?>" method="post">
	<fieldset class="no-margin no-padding">
		<div id="campo" class="column">
			<div id="PP" class="droppable"></div>
			<div id="DD" class="droppable"></div>
			<div id="CC" class="droppable"></div>
			<div id="AA" class="droppable"></div>
		</div>
		<div id="giocatori" class="column">
		<?php foreach($this->giocatori as $key=>$val): ?>
			<?php if($ruolo != $val->ruolo) echo '<div class="' . $val->ruolo . '">'; ?>
			<div class="draggable giocatore <?php if((!empty($this->titolari) && in_array($val->idGioc,$this->titolari)) || (!empty($this->panchinari) && in_array($val->idGioc,$this->panchinari))) echo 'hidden'; ?> <?php echo $val->ruolo; ?>">
				<a class="hidden" rel="<?php echo $val->idGioc; ?>"></a>
				<img alt="<?php echo $val->idGioc; ?>" width="40" src="<?php echo IMGSURL . "foto/" . $val->idGioc; ?>.jpg" />
				<p><?php echo $val->cognome . ' ' . $val->nome; ?></p>
			</div>
			<?php if((isset($this->giocatori[$key + 1]) && $val->ruolo != $this->giocatori[$key + 1]->ruolo) || !isset($this->giocatori[$key + 1])) echo '</div>'; ?>
			<?php if($ruolo != $val->ruolo) $ruolo = $val->ruolo; ?>
		<?php $j++; endforeach; ?>
		</div>
		<div id="panchina" class="column">
			<h4>Panchinari</h4>
			<?php for($i = 0;$i < 7;$i++): ?>
				<div id="panch-<?php echo $i; ?>" class="droppable"></div>
			<?php endfor; ?>
		</div>
		<div id="capitani" class="column">
			<h4>Capitani</h4>
			<?php foreach ($this->elencoCap as $key => $val): ?>
				<div id="cap-<?php echo $val; ?>" class="droppable"></div>
			<?php endforeach; ?>
		</div>
		<div id="titolari-field">
			<?php for($i = 0;$i < 11;$i++): ?>
				<input<?php if(isset($this->titolari[$i])) echo ' value="' . $this->titolari[$i] . '" title="' . $this->giocatoriId[$this->titolari[$i]]->ruolo . $this->giocatoriId[$this->titolari[$i]]->ruolo . '-' . $this->giocatoriId[$this->titolari[$i]]->cognome . ' ' . $this->giocatoriId[$this->titolari[$i]]->nome . '"'; ?> id="gioc-<?php echo $i; ?>" type="hidden" name="gioc[<?php echo $i; ?>]" />
			<?php endfor; ?>
		</div>
		<div id="panchina-field">
			<?php for($i = 0;$i < 7;$i++): ?>
				<input<?php if(isset($this->panchinari[$i]) && !empty($this->panchinari[$i])) echo ' value="' . $this->panchinari[$i] . '" title="' . $this->giocatoriId[$this->panchinari[$i]]->ruolo . $this->giocatoriId[$this->panchinari[$i]]->ruolo . '-' . $this->giocatoriId[$this->panchinari[$i]]->cognome . ' ' . $this->giocatoriId[$this->panchinari[$i]]->nome . '"'; ?> id="panchField-<?php echo $i; ?>" type="hidden" name="panch[<?php echo $i; ?>]" />
			<?php endfor; ?>
		</div>
		<div id="capitani-field">
			<?php foreach ($this->elencoCap as $key => $val): ?>
				<input<?php if(isset($this->cap->$val) && !empty($this->cap->$val)) echo ' value="' . $this->cap->$val . '" title="' . $this->giocatoriId[$this->cap->$val]->ruolo . $this->giocatoriId[$this->cap->$val]->ruolo . '-' . $this->giocatoriId[$this->cap->$val]->cognome . ' ' . $this->giocatoriId[$this->cap->$val]->nome . '"'; ?> id="<?php echo $val; ?>" type="hidden" name="cap[<?php echo $val; ?>]" />
			<?php endforeach; ?>
		</div>
		<input name="button" type="submit" class="button" value="Invia" />
	</fieldset>
</form>
<?php endif; ?>
<?php if(!empty($this->modulo)): ?>
<script type="text/javascript">
// <![CDATA[
	var modulo = Array();
	modulo['PP'] = <?php echo $this->modulo[0]; ?>;
	modulo['DD'] = <?php echo $this->modulo[1]; ?>;
	modulo['CC'] = <?php echo $this->modulo[2]; ?>;
	modulo['AA'] = <?php echo $this->modulo[3]; ?>;
// ]]>
</script>
<?php endif; ?>
<script type="text/javascript" src="<?php echo JSURL . 'custom/formazione.js'; ?>"></script>
