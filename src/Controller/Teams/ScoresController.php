<?php
namespace App\Controller\Teams;

use Cake\Event\Event;

class ScoresController extends \App\Controller\ScoresController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('last', 'Crud.View');
        $this->Crud->mapAction('viewByMatchday', 'Crud.View');
    }
    
    public function last()
    {
        $this->viewByMatchday();
    }

    public function viewByMatchday($matchdayId = null)
    {
        $conditions = [
            'team_id' => $this->getRequest()->getParam('team_id')
        ];
        if($matchdayId) {
            $conditions['matchday_id'] = $matchdayId;
        }
        $this->Crud->on('beforeFind', function(Event $event) use($conditions) {
            return $event->getSubject()->query
                ->where($conditions, [], true)
                ->order(['matchday_id' => 'desc']);
        });
        return $this->view(null);
    }
    
    
}
