<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Burzum\Cake\Service\ServiceAwareTrait;
use Cake\Event\EventInterface;

/**
 *
 * @property \App\Model\Table\TransfertsTable $Transferts
 * @property \App\Service\TransfertService $Transfert
 */
class TransfertsController extends AppController
{
    use ServiceAwareTrait;

    /**
     * @inheritDoc
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Crud\Error\Exception\MissingActionException
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('add', 'Crud.Add');
        $this->loadService('Transfert');
    }

    /**
     * Add
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Crud\Error\Exception\ActionNotConfiguredException
     * @throws \Exception
     */
    public function add()
    {
        /** @var \Crud\Action\AddAction $action */
        $action = $this->Crud->action();
        $action->saveOptions(['associated' => []]);
        $this->Crud->on('afterSave', function (EventInterface $event) {
            if ($event->getSubject()->success) {
                /** @var \App\Model\Entity\Transfert $transfert */
                $transfert = $event->getSubject()->entity;
                $this->Transfert->substituteMembers($transfert);
            }
        });

        return $this->Crud->execute();
    }
}
