<?php
/**
 * Created by PhpStorm.
 * User: marina
 * Date: 16.3.29
 * Time: 21.31
 */

namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\Client;

class OpenWeatherMapCrawlerCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this ->setName('crawler:openweathermap:crawl')
              ->setDescription('Gets weather data from openweathermap.org')
              ->addArgument('city', InputArgument::REQUIRED,'City')
              ->setHelp(<<<EOT
The <info>crawler:openweathermap:crawl</info> command crawls data for a certain city
<info>app/console crawler:openweathermap:crawl Vilnius</info>
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $city = $input->getArgument('city');
        $data = $this->getData($this->getJsonData($city));

        foreach ($data as $key => $value)
        {
            $output->writeln($key.': '.$value);

        }
    }

    private function getJsonData($city)
    {
        $apiKey = $this->getContainer()->getParameter('api_key');
        $client = new Client(['base_uri' => 'http://api.openweathermap.org/data/2.5/forecast/']);
        $request = $client->request('GET',"daily?q=".$city.'&mode=json&units=metric&cnt=5&appid='.$apiKey);
        $response = $request->getBody();

        return json_decode($response, true);
    }

    private function getData($jsonData)
    {
        return ['date' => $jsonData['list'][0]['dt'],
                'temperature high' => $jsonData['list'][0]['temp']['day'],
                'temperature low' => $jsonData['list'][0]['temp']['night'],
                'humidity' => $jsonData['list'][0]['humidity'],
                'pressure' => $jsonData['list'][0]['pressure']
               ];
    }
}