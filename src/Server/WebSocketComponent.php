<?php
namespace App\Server;


use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Psr\Log\LoggerInterface;

class WebSocketComponent implements MessageComponentInterface
{
	/** @var \SplObjectStorage */
	private $clients;

	/** @var string */
	private $last_message;

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
		if ($this->last_message) {
            $this->getLogger()->info('Send last message');
			$conn->send($this->last_message);
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
        $this->getLogger()->info('Message : ' . $message);

		$this->last_message = $message;
		foreach ($this->clients as $client) {
			if ($from !== $client) {
				$client->send($message);
			}
		}
	}
}