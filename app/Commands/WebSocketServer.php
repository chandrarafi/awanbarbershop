<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\Server as Reactor;
use App\Libraries\BookingWebSocket;

/**
 * Command untuk menjalankan WebSocket server
 */
class WebSocketServer extends BaseCommand
{
    /**
     * Command group
     *
     * @var string
     */
    protected $group = 'WebSocket';

    /**
     * Command name
     *
     * @var string
     */
    protected $name = 'websocket:serve';

    /**
     * Command description
     *
     * @var string
     */
    protected $description = 'Runs the WebSocket server for realtime booking updates';

    /**
     * Command usage
     *
     * @var string
     */
    protected $usage = 'websocket:serve [options]';

    /**
     * Command arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * Command options
     *
     * @var array
     */
    protected $options = [
        '-p' => 'Port to run the WebSocket server on',
        '-h' => 'Host to run the WebSocket server on',
    ];

    /**
     * Run the command
     *
     * @param array $params
     * @return void
     */
    public function run(array $params)
    {
        // Get the port from the command line or use 8080 as default
        $port = $params['-p'] ?? 8080;
        $host = $params['-h'] ?? '0.0.0.0';

        CLI::write('Starting WebSocket server...', 'green');
        CLI::write("Host: {$host}", 'yellow');
        CLI::write("Port: {$port}", 'yellow');

        // Create the event loop
        $loop = Factory::create();

        // Set up a periodic timer to check for expired bookings
        $websocket = new BookingWebSocket();
        $loop->addPeriodicTimer(10, function () use ($websocket) {
            CLI::write('Checking for expired bookings...', 'blue');
            $websocket->checkExpiredBookings();
        });

        // Create the socket
        $socket = new Reactor("{$host}:{$port}", $loop);

        // Set up the server
        $server = new IoServer(
            new HttpServer(
                new WsServer($websocket)
            ),
            $socket,
            $loop
        );

        CLI::write('WebSocket server started!', 'green');
        CLI::write("Listening on {$host}:{$port}", 'yellow');
        CLI::write('Press Ctrl+C to stop the server', 'yellow');

        // Run the server
        $server->run();
    }
}
