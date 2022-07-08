<?php

namespace App\Command;

use App\Entity\Currency;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\CustomNormalizer;
use Symfony\Component\Serializer\Serializer;

use Doctrine\ORM\EntityManagerInterface;

class RefreshCurrenciesCommand extends Command
{
    protected static $defaultName = 'app:refresh-currencies';
    protected static $defaultDescription = 'Обновляет таблицу курсов валют';

    private EntityManagerInterface $manager;

    private $currencyUrl = 'http://cbr.ru/scripts/XML_daily.asp';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct(self::$defaultName);

        $this->manager = $manager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $client = HttpClient::create();

        $normalizer = new CustomNormalizer();
        $xmlEncoder = new XmlEncoder();
        $serializer = new Serializer([$normalizer], [$xmlEncoder]);

        $response = $client->request('GET', $this->currencyUrl);
        $currenciesXML = $response->getContent();

        $currencies = $serializer->decode($currenciesXML, 'xml');

        //
        foreach ($currencies['Valute'] as $item)
        {
            $id = $item['CharCode'];
            $name = $item['Name'];
            $rate = (int)$item['Value'] / (int)$item['Nominal'];

            $currency =
                $this->
                    manager->
                        getRepository(Currency::class)->
                            find($id);

            if (null === $currency)
            {
                $currency = new Currency($id, $name, $rate);

            }
            else
            {
                $currency->setRate($rate);
            }

            $this->manager->persist($currency);
            $this->manager->flush();

        }
        //

        $io->success('Job done!');

        return Command::SUCCESS;
    }
}
