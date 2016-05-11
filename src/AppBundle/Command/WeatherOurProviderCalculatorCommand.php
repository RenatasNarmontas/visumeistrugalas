<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 07/05/16
 * Time: 15:52
 */

namespace AppBundle\Command;

use AppBundle\Crawler\WeatherProviderException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class WeatherOurProviderCalculatorCommand
 * @package AppBundle\Command
 */
class WeatherOurProviderCalculatorCommand extends ContainerAwareCommand
{
    use DateVerificationTrait;

    /**
     * Configure Command
     */
    public function configure()
    {
        $this
            ->setName('weather:provider:calculate')
            ->setAliases(array('weather:calculate:provider'))
            ->setDescription('Calculate our provider data')
            ->addOption(
                'start-date',
                null,
                InputOption::VALUE_REQUIRED,
                'Start calculation from the date. <info>[format: Y-m-d]</info> <comment>[default: yesterday]</comment>'
            )
            ->addOption(
                'end-date',
                null,
                InputOption::VALUE_REQUIRED,
                'Start calculation to the date. <info>[format: Y-m-d]</info> <comment>[default: current date]</comment>'
            )
            ->setHelp(<<<EOF
The <info>weather:provider:calculate</info> command calculates our weather data from all other weather providers.

<comment>Samples:</comment>
    To calculate our weather data and save it to DB:
    <info>php app/console weather:provider:calculate</info>
EOF
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws WeatherProviderException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        // Log to app/logs
        $logger = $this->getContainer()->get('logger');
        $logger->info('Start WeatherOurProviderCalculatorCommand->execute()');

        // Check parameters
        $startDate = $this->verifyStartDate($input->getOption('start-date'));
        $endDate = $this->verifyEndDate($input->getOption('end-date'));

        $logger->info(
            sprintf(
                'Calculate our weather provider. Period: %s - %s',
                $startDate,
                $endDate
            )
        );

        // Add our provider
        $ourProvider = $this->getContainer()->get('our.provider.service');
        try {
            $ourProvider->addOurProviderForecast($startDate, $endDate);
        } catch (WeatherProviderException $we) {
            $logger->error($we->getMessage());
        }

        $logger->info('Finish WeatherOurProviderCalculatorCommand->execute()');

        return 0;
    }
}
