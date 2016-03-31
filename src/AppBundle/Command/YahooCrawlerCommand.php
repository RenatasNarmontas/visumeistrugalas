<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class YahooCrawlerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('crawler:yahoo')
            ->setDescription('Import data forecast by cities from developer.yahoo.com')
            ->addOption(
                'city',
                null,
                InputOption::VALUE_REQUIRED,
                'City name'
            )
            ->setHelp(<<<EOF
The <info>crawler:yahoo</info> command import forecast data.

<info>php app/console crawler:wunderground --city=Vilnius</info>

EOF
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cityName = $this->normalizeCityName($input->getOption('city'));


        $json_string = file_get_contents(
            'https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20weather.forecast%20where%20woeid%20in%20(select%20woeid%20from%20geo.places(1)%20where%20text%3D%22'.$cityName.'%22)%20and%20u%3D%22C%22&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys'
        );
        $parsed_json = json_decode($json_string);

        $city = $parsed_json->{'query'}->{'results'}->{'channel'}->{'location'}->{'city'};
        $temp_c = $parsed_json->{'query'}->{'results'}->{'channel'}->{'item'}->{'condition'}->{'temp'};

        $output->writeln('City: '.$city);
        $output->writeln('Temperature: '.$temp_c);
    }

    /**
     * Normalizes city name
     *
     * @param string $cityName City name for normalization
     * @return string
     */
    private function normalizeCityName(string $cityName)
    {
        return str_replace(' ', '_', trim($cityName));
    }
}
