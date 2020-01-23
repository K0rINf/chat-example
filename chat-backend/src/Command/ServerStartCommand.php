<?php

namespace App\Command;

use App\Server\Chat;
use App\Server\ChatServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\Wamp\WampServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Команда запускает WS сервер.
 * Class ServerStartCommand
 * @package App\Command
 */
class ServerStartCommand extends Command
{
    protected static $defaultName = 'chat:server:start';
    /**
     * @var ChatServer
     */
    private $chat;

    /**
     * ServerStartCommand constructor.
     *
     * @param ChatServer $chat
     */
    public function __construct(ChatServer $chat)
    {
        parent::__construct(self::$defaultName);
        $this->chat = $chat;
    }

    protected function configure()
    {
        $this
            ->setDescription('This command start Ratchet websocket server')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        error_reporting(0);
        $io = new SymfonyStyle($input, $output);
        $this->chat->setIO($io);
        $io->writeln('Запуск сервера...');

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    $this->chat
                )
            ),
            $this->chat->getPort()
        );

        $server->run();

        return 0;
    }
}
