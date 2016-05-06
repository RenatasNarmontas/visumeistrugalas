<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 02/05/16
 * Time: 16:28
 */

namespace AppBundle\Command;

use AppBundle\DatabaseManager\CalculateForecastDeviations;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class CalculateWeatherDeviationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('weather:calculate:deviation')
            ->setAliases(array('calculate:weather:deviation'))
            ->setDescription('Calculates forecast deviation and saves them to database')
            ->addOption(
                'start-date',
                null,
                InputOption::VALUE_REQUIRED,
                'Start calculation from the date. <info>[format: Y-m-d]</info> <comment>[default: 1970-01-01]</comment>'
            )
            ->addOption(
                'end-date',
                null,
                InputOption::VALUE_REQUIRED,
                'Start calculation to the date. <info>[format: Y-m-d]</info> <comment>[default: current date]</comment>'
            )
            ->setHelp(<<<EOF
The <info>%command.name%</info> command calculates forecast deviation and saves them to database.

<comment>Samples:</comment>
    To calculate all forecast deviations and save them to database:
    <info>php app/console %command.full_name%</info>
    
    To calculate range of forecast deviations and save them to database:
    <info>php app/console %command.full_name% --start-date=2015-09-23 --end-date=2016-10-24</info>
EOF
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Log to app/logs
        $logger = $this->getContainer()->get('logger');
        $logger->info('Start CalculateWeatherDeviationCommand->execute()');
        
        // Check parameters
        $startDate = $this->verifyStartDate($input->getOption('start-date'));
        $endDate = $this->verifyEndDate($input->getOption('end-date'));

        $output->writeln(sprintf('Start date: "<info>%s</info>"', $startDate));
        $output->writeln(sprintf('End date: "<info>%s</info>"', $endDate));

        $forecastDeviation = new CalculateForecastDeviations(
            $this->getContainer()->get('doctrine'),
            $startDate,
            $endDate
        );

        $logger->info(
            sprintf(
                'Calculate and update forecast temperature deviations. Period: %s - %s',
                $startDate,
                $endDate
            )
        );
        $forecastDeviation->updateForecastTemperatureDeviations();

        $logger->info('Finish CalculateWeatherDeviationCommand->execute()');

        return 0;
    }

    /**
     * Verifies start date
     * @param string|null $startDate
     * @return string
     */
    private function verifyStartDate($startDate): string
    {
        if ((null === $startDate) || (!$this->validateDate($startDate))) {
            // Set date to 1970-01-01
            return '1970-01-01';
        }
        return $startDate;
    }

    /**
     * Verifies end date
     * @param string|null $endDate
     * @return string
     */
    private function verifyEndDate($endDate): string
    {
        if ((null === $endDate) || (!$this->validateDate($endDate))) {
            // Set date to current date
            return date('Y-m-d');
        }
        return $endDate;
    }

    /**
     * Validates date correctness
     * @param string $date
     * @param string $format
     * @return bool
     */
    private function validateDate(string $date, string $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}
