<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 10/05/16
 * Time: 20:28
 */

namespace AppBundle\Command;

use AppBundle\Crawler\WeatherProviderException;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WeatherNotificationCommand extends ContainerAwareCommand
{
    /**
     * Configure Command
     */
    public function configure()
    {
        $this
            ->setName('weather:send:notification')
            ->setAliases(array('weather:notifications:send'))
            ->setDescription('Send notifications by email')
            ->setHelp(<<<EOF
The <info>weather:send:notifications</info> command sends notification email for users.

<comment>Samples:</comment>
    To send notification for all subscribed users:
    <info>php app/console weather:send:notification</info>
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
        $logger->info('Start WeatherNotificationCommand->execute()');

        $entityManager = $this->getContainer()->get('doctrine')->getManager();
        // Get forecasts from DB
        $forecasts = $entityManager->getRepository('AppBundle:Forecast')->getForecastsForSubscribers();
        // Get users from DB
        $users = $entityManager->getRepository('AppBundle:User')->findBy(['notifications' => 1]);

        $twig = $this->getContainer()->get('templating');

        $mailerServiceName = 'swiftmailer.mailer.default';
        $mailer = $this->getContainer()->get($mailerServiceName);
        $date = new \DateTime('now');
        $numSent = 0;
        foreach ($users as $user) {
            // Sending email for user
            $logger->info('Sending notifications for '.$user->getEmail());

            $message = \Swift_Message::newInstance()
                ->setSubject('Forecasts '.$date->format('Y-m-d'))
                ->setFrom('visumeistrugalas@gmail.com')
                ->setTo('visumeistrugalas@gmail.com')
                ->setContentType('text/html')
                ->setBody($twig->render(
                    'AppBundle:Email:forecasts.html.twig',
                    [
                        'forecasts' => $forecasts
                    ]
                ));

            $numSent += $mailer->send($message);
        }
        $logger->info(sprintf('Sent %d messages', $numSent));
        $logger->info('Finish WeatherNotificationCommand->execute()');

        return 0;
    }
}
