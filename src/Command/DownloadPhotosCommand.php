<?php
declare(strict_types=1);

namespace App\Command;

use App\Traits\CurrentMatchdayTrait;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Http\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @property \App\Model\Table\SeasonsTable $Seasons
 * @property \App\Model\Table\MatchdaysTable $Matchdays
 * @property \App\Model\Table\MembersTable $Members
 */
class DownloadPhotosCommand extends Command
{
    use CurrentMatchdayTrait;

    /**
     * {@inheritDoc}
     *
     * @throws \Cake\Datasource\Exception\MissingModelException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Seasons');
        $this->loadModel('Matchdays');
        $this->loadModel('Members');
        $this->getCurrentMatchday();
    }

    /**
     * {@inheritDoc}
     *
     * @throws \RuntimeException
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $io->out('Download photos task');
        $baseUrl = 'www.guido8975.it';
        $url = '/index.php?ctg=15';
        $referer = 'http://' . $baseUrl . $url;

        $path = IMG_PLAYERS . 'season-new' . DS;

        /** @var \App\Model\Entity\Member[] $members */
        $members = $this->Members->find()
            ->contain(['Players'])
            ->where(['season_id' => $this->currentSeason->id])->all();
        foreach ($members as $member) {
            $client = new Client(['host' => $baseUrl, 'headers' => ['Referer' => $referer]]);
            $io->out('Searching user ' . $member->player->full_name);
            $response = $client->post($url, ['PanCal' => $member->player->full_name]);
            if ($response->isOk()) {
                $response->getCookie('PHPSESSID');
                /** @var array $values */
                foreach ($response->getHeaders() as $name => $values) {
                    $io->out($name . ': ' . implode(', ', $values));
                }

                //$this->out($response->getStringBody());
                $crawler = new Crawler();
                $crawler->addContent($response->getStringBody());
                $trs = $crawler->filter('table.Result tr a');
                $io->out('Trovati ' . $trs->count());
                if ($trs->count() >= 1) {
                    $trs->first();
                    $href = $trs->attr('href');
                    if ($href != null && $href != '') {
                        $io->out('Found ' . $href);
                        $href = 'http://' . $baseUrl . '/' . $href;
                        $io->out('Url ' . $href);
                        $client = new Client();
                        $response = $client->get($href, [], ['headers' => ['Referer' => $referer]]);
                        //$this->out($response->getStringBody());
                        if ($response->isOk()) {
                            $file = $path . (string)$member->code_gazzetta . '.jpg';
                            $io->out('Savings ' . '/' . $href . ' => ' . $file);
                            file_put_contents($file, $response->getStringBody());
                        }
                    }
                }
            }
        }

        return CommandInterface::CODE_SUCCESS;
    }
}
