<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 28/03/16
 * Time: 17:49
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class WundergroundCrawlerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('crawler:wunderground')
            ->setDescription('Import data forecast by cities from api.wunderground.com')
            ->addOption(
                'city',
                null,
                InputOption::VALUE_REQUIRED,
                'City name'
            )
            ->setHelp(<<<EOF
The <info>crawler:wunderground</info> command import forecast data.

<info>php app/console crawler:wunderground --city=Vilnius</info>

EOF
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        $wuApi = $this->getContainer()->get('weather_underground.data_features');
//        $wuApi->setFeatures(array('forecast'));
//        $cityName = $input->getOption('city');
//
//        if (!$cityName) {
//            throw new \Exception('Enter city name');
//        }
//
//        $wuApi->setQuery('/Lithuania/'.$cityName, true);
//        $data = $wuApi->getData();
//
//        var_dump($data); die;

        $cityName = $this->normalizeCityName($input->getOption('city'));
        $wuApi = $this->getContainer()->getParameter('wundergroung_api');

        $json_string = file_get_contents(
            'http://api.wunderground.com/api/'.$wuApi.'/geolookup/conditions/q/IA/'.$cityName.'.json'
        );
        $parsed_json = json_decode($json_string);

        $city = $parsed_json->{'location'}->{'city'};
        $temp_c = $parsed_json->{'current_observation'}->{'temp_c'};
        $fullLocation = $parsed_json->{'current_observation'}->{'display_location'}->{'full'};

        $output->writeln('City: '.$city);
        $output->writeln('Temperature: '.$temp_c);
        $output->writeln('Full location: '.$fullLocation);
    }

    /**
     * Normalizes city name
     *
     * @param string $cityName City name for normalization
     * @return string
     */
    private function normalizeCityName($cityName)
    {
        return str_replace(' ', '_', trim($cityName));
    }
}
