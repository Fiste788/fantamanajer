<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\Event;
use Cake\Event\EventInterface;

/**
 *
 * @property \App\Model\Table\TeamsTable $Teams
 */
class TeamsController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('edit', 'Crud.Edit');
        $this->Crud->mapAction('add', 'Crud.Add');
        $this->Crud->mapAction('upload', 'Crud.Edit');
    }

    /**
     * View
     *
     * @param int $id Id
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function view($id)
    {
        $this->Crud->on(
            'beforeFind',
            function (Event $event) {
                $event->getSubject()->query->contain([
                    'Users',
                    'PushNotificationSubscriptions',
                    'EmailNotificationSubscriptions',
                ]);
            }
        );

        return $this->Crud->execute();
    }

    /**
     * Add
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function add()
    {
        $this->Crud->action()->saveOptions(['accessibleFields' => ['user' => true]]);
        $this->Crud->action()->saveMethod('saveWithoutUser');

        return $this->Crud->execute();
    }
}
