<form class="form-inline" action="<?php echo $this->router->generate('trasferimento_index'); ?>" method="post">
    <fieldset>
        <div class="form-group">
            <input type="hidden" name="p" value="<?php echo $this->request->get('p'); ?>" />
            <label for="id" class="no-margin">Seleziona la squadra:</label>
            <select class="form-control" name="id" onchange="this.form.submit();">
                <?php foreach ($this->elencoSquadre as $val): ?>
                    <option<?php if ($this->filterId == $val->id) echo ' selected="selected"'; ?> value="<?php echo $val->id; ?>"><?php echo $val->nomeSquadra; ?></option>
                <?php endforeach ?>
            </select>
        </div>
    </fieldset>
</form>