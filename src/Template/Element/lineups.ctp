<?php if (!empty($dispositions)): ?>
    <table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--4dp">
        <caption><?= $caption ?></caption>
        <thead>
            <tr>
                <th class="mdl-data-table__cell--non-numeric"><?= __('Nome') ?></th>
                <th class="mdl-data-table__cell--non-numeric"><?= __('Ruolo') ?></th>
                <th class="mdl-data-table__cell--non-numeric"><?= __('Club') ?></th>
                <th><abbr title="<?= __('Titolare') ?>"><?= __('Tit') ?></abbr></th>
                <th><abbr title="<?= __('Ammonito') ?>"><?= __('Amm') ?></abbr></th>
                <th><abbr title="<?= __('Espulso') ?>"><?= __('Esp') ?></abbr></th>
                <th><?= __('Assist') ?></th>
                <th><?= __('Gol') ?></th>
                <th><?= __('Punti') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dispositions as $key => $disposition): ?>
                <tr<?php if($disposition->position <= 11 && $disposition->consideration == 0) echo ' class="alert-danger"'; elseif($disposition->position > 11 && $disposition->consideration == 1) echo ' class="alert-success"' ?>>
                    <td class="mdl-data-table__cell--non-numeric">
                        <a class="mdl-navigation__link" href="<?php echo $this->Url->build(['controller' => 'Players', 'action' => 'view', $disposition->member->player_id],$full) ?>"><?= ($disposition->considerato == 2) ? $disposition->member->player->fullName . '<span id="cap">(C)</span>' : $disposition->member->player->fullName; ?></a>
                    </td>
                    <td class="mdl-data-table__cell--non-numeric"><?php echo $disposition->member->role; ?></td>
                    <td class="mdl-data-table__cell--non-numeric">
                        <a href="<?= $this->Url->build(['controller' => 'Clubs', 'action' => 'view', $disposition->member->club->id]) ?>">
                            <?= $this->Html->image('clubs/' . $disposition->member->club->id . '.png',['height'=> 32, 'alt' => $disposition->member->club->abbreviation]) ?>
                        </a>
                    </td>
                    <td class="hidden-phone"><?php if ($disposition->member->ratings[0]->regular): ?><i class="material-icons">check</i><?php endif; ?></td>
                    <td class="hidden-phone"><?php if ($disposition->member->ratings[0]->yellow_card): ?><i class="material-icons">check</i><?php endif; ?></td>
                    <td class="hidden-phone"><?php if ($disposition->member->ratings[0]->red_card): ?><i class="material-icons">check</i><?php endif; ?></td>
                    <td class="hidden-phone"><?= ($disposition->member->ratings[0]->assist != 0) ? $disposition->member->ratings[0]->assist : ""; ?></td>
                    <td class="hidden-phone"><?= ($disposition->member->ratings[0]->gol != 0) ? $disposition->member->ratings[0]->gol : ""; ?></td>
                    <td><?php if (!empty($disposition->member->ratings[0]->points)) echo ($disposition->member->ratings[0]->consideration == 2) ? $disposition->member->ratings[0]->points * 2 : $disposition->member->ratings[0]->points; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>