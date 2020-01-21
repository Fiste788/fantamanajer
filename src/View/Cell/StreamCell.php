<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\View\Cell;
use StreamCake\Enrich;
use StreamCake\FeedManager;

class StreamCell extends Cell
{
    /**
     * Undocumented function
     *
     * @param string $feedName Feedname
     * @param string $id Id
     * @param bool $aggregated Aggregated
     * @return void
     */
    public function display(string $feedName, string $id, $aggregated = false): void
    {
        $feedManager = new FeedManager();
        $feed = $feedManager->getFeed($feedName, $id);
        $enrich = new Enrich();

        /**
         * @psalm-suppress MixedArrayAccess
         * @psalm-suppress MixedAssignment
         */
        $results = $feed->getActivities()['results'];
        if ($aggregated) {
            /** @psalm-suppress MixedArgument */
            $enriched = $enrich->enrichAggregatedActivities($results);
        } else {
            /** @psalm-suppress MixedArgument */
            $enriched = $enrich->enrichActivities($results);
        }

        $this->set([
            'stream' => $enriched,
            '_serialize' => false,
        ]);
    }
}
