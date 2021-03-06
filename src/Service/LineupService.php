<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Disposition;
use App\Model\Entity\Lineup;
use App\Model\Entity\Matchday;
use Cake\Datasource\ModelAwareTrait;

/**
 * @property \App\Model\Table\TeamsTable $Teams
 * @property \App\Model\Table\LineupsTable $Lineups
 */
class LineupService
{
    use ModelAwareTrait;

    /**
     * Constructor
     *
     * @throws \Cake\Datasource\Exception\MissingModelException
     * @throws \UnexpectedValueException
     */
    public function __construct()
    {
        $this->loadModel('Teams');
        $this->loadModel('Lineups');
    }

    /**
     * Return a copy of lineup
     *
     * @param \App\Model\Entity\Lineup $lineup Lineup
     * @param int $teamId Team id
     * @param \App\Model\Entity\Matchday $matchday Matchday id
     * @return \App\Model\Entity\Lineup
     */
    public function duplicate(Lineup $lineup, int $teamId, Matchday $matchday): Lineup
    {
        if ($lineup->team_id == $teamId && $lineup->matchday_id != $matchday->id) {
            $lineup = $this->copy($lineup, $matchday, true, false);
        }
        $lineup->modules = Lineup::$modules;

        return $lineup;
    }

    /**
     * Return new empty lineup
     *
     * @param int $teamId Team id
     * @return \App\Model\Entity\Lineup
     */
    public function getEmptyLineup(int $teamId): Lineup
    {
        $lineup = new Lineup();
        $lineup->team = $this->Teams->get($teamId, ['contain' => ['Members' => ['Roles', 'Players']]]);
        $lineup->modules = Lineup::$modules;

        return $lineup;
    }

    /**
     * Return new empty lineup with matchday
     *
     * @param int $teamId Team id
     * @param int $matchdayId Matchday id
     * @return \App\Model\Entity\Lineup
     */
    public function newLineup(int $teamId, int $matchdayId): Lineup
    {
        $lineup = $this->getEmptyLineup($teamId);
        $lineup->team_id = $teamId;
        $lineup->matchday_id = $matchdayId;

        return $lineup;
    }

    /**
     * Substitute member in lineup
     *
     * @param \App\Model\Entity\Lineup $lineup Lineup
     * @param int $old_member_id Old member id
     * @param int $new_member_id New memeber id
     * @return bool
     */
    public function substitute(Lineup $lineup, int $old_member_id, int $new_member_id): bool
    {
        foreach ($lineup->dispositions as $key => $disposition) {
            if ($old_member_id == $disposition->id) {
                $lineup->dispositions[$key]->id = $new_member_id;
                $lineup->setDirty('dispositions', true);
            }
        }
        if ($old_member_id == $lineup->captain_id) {
            $lineup->captain_id = $new_member_id;
        }
        if ($old_member_id == $lineup->vcaptain_id) {
            $lineup->vcaptain_id = $new_member_id;
        }
        if ($old_member_id == $lineup->vvcaptain_id) {
            $lineup->vvcaptain_id = $new_member_id;
        }

        return $lineup->isDirty();
    }

    /**
     * Return a not saved copy of the entity with the specified matchday
     *
     * @param \App\Model\Entity\Lineup $lineup the matchday to use
     * @param \App\Model\Entity\Matchday $matchday if false empty the captain. Default: true
     * @param bool $isCaptainActive if true the lineup was missing. Default true
     * @param bool $cloned if true the lineup is cloned. Default true
     * @return \App\Model\Entity\Lineup
     */
    public function copy(Lineup $lineup, Matchday $matchday, $isCaptainActive = true, $cloned = true): Lineup
    {
        $lineup->setAccess('*', true);
        $lineup->team->setAccess('members', true);
        $lineup->team->setAccess('*', true);
        $lineupCopy = $this->Lineups->newEntity(
            $lineup->toArray(),
            [
                'associated' => [
                    'Teams' => [
                        'guard' => false,
                        'accessibleFields' => ['*' => true],
                        'associated' => ['Championships', 'Members' => [
                            'guard' => false,
                            'accessibleFields' => ['*' => true],
                        ]],
                    ],
                    'Dispositions' => [
                        'guard' => false,
                        'accessibleFields' => ['*' => true],
                        'associated' => [
                            'Members' => [
                                'guard' => false,
                                'accessibleFields' => ['*' => true],
                                'associated' => ['Ratings'],
                            ],
                        ],
                    ],
                ],
                'guard' => false,
            ]
        );
        $lineupCopy->unset('id');
        $lineupCopy->jolly = false;
        $lineupCopy->cloned = $cloned;
        $lineupCopy->matchday_id = $matchday->id;
        if (!$isCaptainActive) {
            $lineupCopy->captain_id = null;
            $lineupCopy->vcaptain_id = null;
            $lineupCopy->vvcaptain_id = null;
        }
        $lineupCopy->dispositions = array_map(function (Disposition $disposition) use ($lineupCopy) {
            /** @var \App\Model\Entity\Member $member */
            $member = $this->Lineups->Dispositions->Members
                ->find('listWithRating', ['matchday_id' => $lineupCopy->matchday_id])
                ->where(['Members.id' => $disposition->member_id])->firstOrFail();
            $disposition->member = $member;

            return $this->reset($disposition);
        }, $lineupCopy->dispositions);

        return $lineupCopy;
    }

    /**
     * Reset the entity to default value and new
     *
     * @param \App\Model\Entity\Disposition $disposition Disposition
     * @return \App\Model\Entity\Disposition
     */
    private function reset(Disposition $disposition): Disposition
    {
        unset($disposition->id);
        unset($disposition->lineup_id);
        $disposition->consideration = 0;
        $disposition->points = null;

        return $disposition;
    }

    /**
     * Reset disposition in a lineup
     *
     * @param \App\Model\Entity\Lineup $lineup Lineup
     * @return void
     */
    public function resetDispositions(Lineup $lineup): void
    {
        foreach ($lineup->dispositions as $key => $disposition) {
            $disposition->consideration = 0;
            $lineup->dispositions[$key] = $disposition;
        }
    }
}
