<div class="titolo-pagina">
	<div class="column logo-tit">
		<img src="<?php echo IMGSURL.'rose-big.png'; ?>" alt="Logo Squadre" />
	</div>
	<h2 class="column">Le squadre</h2>
</div>
<div id="squadre" class="main-content">
<?php foreach($this->elencosquadre as $key => $val): ?>
	<div class="box-squadra column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
			<?php if(file_exists(UPLOADDIR. $val['idUtente'].'-small.jpg')): ?>
				<a rel="group" title="<?php echo $val['nome']; ?>" class="fancybox" href="<?php echo UPLOADIMGURL.$val['idUtente'].'-original.jpg'; ?>" >
				<?php 
				$image = imagecreatefromjpeg(UPLOADDIR.$val['idUtente'].'-med.jpg');
				$width = imagesx ($image); 
				imagedestroy($image);
				if($width > 101)
					$appo = floor(($width - 100) / 2);
				?>
					<img class="logo" alt="<?php echo $val['idUtente']; ?>" src="<?php echo UPLOADIMGURL. $val['idUtente'].'-med.jpg'; ?>" title="Clicca per vedere l'immagine ingrandita"<?php if(isset($appo)) echo ' style="margin-left:-'.$appo.'px"'; ?> />
					<img class="logo reflex" alt="<?php echo $val['idUtente']; ?>" src="<?php echo UPLOADIMGURL. $val['idUtente'].'-med-reflex.jpg'; ?>" title="Clicca per vedere l'immagine ingrandita"<?php if(isset($appo)) echo ' style="margin-left:-'.$appo.'px"'; ?>  />
				</a>
			<?php else: ?>
				<img class="logo column last" alt="<?php echo $val['idUtente']; ?>" src="<?php echo IMGSURL.'no-foto.png'; ?>" title="<?php echo $val['nome']; ?>" />
			<?php endif; ?>
			<h3><a href="<?php echo $this->linksObj->getLink('dettaglioSquadra',array('squadra'=>$val['idUtente'])); ?>" title="Maggiori informazioni"><?php echo $val['nome']; ?></a></h3>	
			<div>Proprietario: <?php echo $val['username'] ?></div>
			<div>Pos. in classifica: <?php echo $this->posizioni[$val['idUtente']] ?></div>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
<?php unset($appo); endforeach; ?>
</div>
	<?php if($_SESSION['logged'] == TRUE): ?>
	<div id="squadradett" class="column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
	<?php else: ?>
		<div class="right">&nbsp;</div>
	<?php endif; ?>
<script type="text/javascript">
	$(document).ready(function() { 
		$(".fancybox").fancybox({
			'zoomSpeedIn': 500,
			'zoomSpeedOut' : 500,
			'imageScale' : true,
			'zoomOpacity' : true,
			'overlayShow' : true,
			'overlayOpacity' : 0.6,
			'centerOnScroll' : true,
			'padding' : 0,
			'hideOnContentClick' : false
			})
	}); 
</script>