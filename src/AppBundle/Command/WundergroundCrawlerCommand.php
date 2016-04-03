<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 28/03/16
 * Time: 17:49
 */

namespace AppBundle\Command;

use AppBundle\Crawler\WundergroundCrawler;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class WundergroundCrawlerCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('crawler:wunderground')
            ->setDescription('Import data forecast by cities from api.wunderground.com')
            ->setHelp(<<<EOF
The <info>crawler:wunderground</info> command imports weather data to DB from wunderground.com.

<comment>Samples:</comment>
    To get weather information and save it to DB:
    <info>php app/console crawler:wunderground</info>
EOF
            );

    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logIt($output, 'wunderground crawler start');

        $wuCrawler = new WundergroundCrawler($this->getContainer());
        $wuCrawler->crawl();

        $this->logIt($output, 'wunderground crawler finish');
    }

    /**
     * Helper function to log messages
     * @param OutputInterface $output
     * @param string $message
     */
    private function logIt(OutputInterface $output, string $message)
    {
        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_NORMAL) {
            $output->writeln(date('Y-m-d H:i:s').' - '.$message);
        }
    }
}
