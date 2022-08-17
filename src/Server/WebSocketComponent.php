<?php
namespace App\Server;


use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Psr\Log\LoggerInterface;

class WebSocketComponent implements MessageComponentInterface
{
	/** @var \SplObjectStorage */
	private $clients;

	/** @var array */
	private $last_messages = [];

    /** @var LoggerInterface */
    private $logger;

	public function __construct(LoggerInterface $logger)
	{
	    $this->logger = $logger;
		$this->clients = new \SplObjectStorage();
	}
    /**
     * @return LoggerInterface
     */
    protected function getLogger() {
        return $this->logger;
    }

	public function onOpen(ConnectionInterface $conn)
	{
        $this->getLogger()->info('New client..');

		$this->clients->attach($conn);
		if (count($this->last_messages)) {
            $this->getLogger()->info('Send last messages');
            foreach ($this->last_messages as $message) {
                $conn->send($message);
            }
		}
	}

	public function onClose(ConnectionInterface $closedConnection)
	{
		$this->clients->detach($closedConnection);
	}

	public function onError(ConnectionInterface $conn, \Exception $e)
	{
		$conn->send('An error has occurred: ' . $e->getMessage());
		$conn->close();
	}

	public function onMessage(ConnectionInterface $from, $message)
	{
        if ($message) {
            $this->getLogger()->info('Message : ' . $message);
            $json_data = json_decode($message);

            $this->last_messages[$json_data['db_id']] = $message;
            foreach ($this->clients as $client) {
                if ($from !== $client) {
                    $client->send($message);
                }
            }
        }

	}
}