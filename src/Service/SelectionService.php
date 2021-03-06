<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Selection;
use App\Model\Entity\Transfert;
use App\Utility\WebPush\WebPushMessage;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Core\Configure;
use Cake\Datasource\ModelAwareTrait;
use Cake\Mailer\Mailer;
use WebPush\Notification;

/**
 * @property \App\Model\Table\MembersTeamsTable $MembersTeams
 * @property \App\Model\Table\SelectionsTable $Selections
 * @property \App\Service\PushNotificationService $PushNotification
 */
class SelectionService
{
    use ModelAwareTrait;
    use ServiceAwareTrait;

    /**
     * Construct
     *
     * @throws \Cake\Datasource\Exception\MissingModelException
     * @throws \UnexpectedValueException
     */
    public function __construct()
    {
        $this->loadModel('MembersTeams');
        $this->loadModel('Selections');
        $this->loadService('PushNotification');
    }

    /**
     * @param \App\Model\Entity\Selection $selection Selection
     * @return void
     * @throws \ErrorException
     * @throws \Cake\Mailer\Exception\MissingMailerException
     * @throws \Cake\Mailer\Exception\MissingActionException
     * @throws \BadMethodCallException
     */
    public function notifyLostMember(Selection $selection): void
    {
        /** @var \App\Model\Entity\Selection $selection */
        $selection = $this->Selections->loadInto($selection, ['Teams' => [
            'EmailNotificationSubscriptions',
            'PushNotificationSubscriptions',
            'Users.PushSubscriptions',
        ], 'NewMembers.Players']);
        if ($selection->team->isEmailSubscripted('lost_member')) {
            $email = new Mailer();
            $email->setViewVars(
                [
                    'player' => $selection->new_member->player,
                    'baseUrl' => 'https://fantamanajer.it',
                ]
            )
                ->setSubject('Un altra squadra ti ha soffiato un giocatore selezionato')
                ->setEmailFormat('html')
                ->setTo($selection->team->user->email)
                ->viewBuilder()->setTemplate('lost_member');
            $email->deliver();
        }
        if ($selection->team->isPushSubscripted('lost_member')) {
            foreach ($selection->team->user->push_subscriptions as $subscription) {
                $pushSubscription = $subscription->getSubscription();
                if ($pushSubscription != null) {
                    $message = WebPushMessage::create((array)Configure::read('WebPushMessage.default'))
                        ->title('Un altra squadra ti ha soffiato un giocatore selezionato')
                        ->body('Hai perso il giocatore ' . $selection->new_member->player->full_name)
                        ->tag('lost-player-' . $selection->id);
                    $messageString = json_encode($message);
                    if ($messageString != false) {
                        $notification = Notification::create()
                            ->withTTL(3600)
                            ->withTopic('player-lost')
                            ->withPayload($messageString);
                        $this->PushNotification->send($notification, $pushSubscription);
                    }
                }
            }
        }
    }

    /**
     * Transform selection to transfert
     *
     * @param \App\Model\Entity\Selection $selection Selection
     * @return \App\Model\Entity\Transfert
     */
    public function toTransfert(Selection $selection): Transfert
    {
        $transfert = new Transfert();
        $transfert->team_id = $selection->team_id;
        $transfert->matchday_id = $selection->matchday_id;
        $transfert->old_member_id = $selection->old_member_id;
        $transfert->new_member_id = $selection->new_member_id;

        return $transfert;
    }

    /**
     * Save selection
     *
     * @param \App\Model\Entity\Selection $entity selection
     * @return void
     */
    public function save(Selection $entity): void
    {
        /** @var \App\Model\Entity\MembersTeam $memberTeam */
        $memberTeam = $this->MembersTeams->find()
            ->contain(['Members'])
            ->where([
                'team_id' => $entity->team_id,
                'member_id' => $entity->old_member_id,
            ])->first();
        $memberTeam->member_id = $entity->new_member_id;
        $this->MembersTeams->save($memberTeam);
    }
}
