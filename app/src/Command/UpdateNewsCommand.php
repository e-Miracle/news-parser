<?php


namespace App\Command;


use App\Message\NewsParse;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UpdateNewsCommand extends Command
{
    protected static $defaultName = "news:parse";

    private MessageBusInterface $bus;

    public function __construct(/*...,*/ MessageBusInterface $bus)
    {
        //...
        $this->bus = $bus;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription("Parse news feed to store in database")
            ->addArgument('url', InputArgument::OPTIONAL, "News RSS Feed URL", "https://highload.today/feed");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getArgument('url');
        $load = simplexml_load_file($url);
        $this->bus->dispatch(new NewsParse($load));
    }
}