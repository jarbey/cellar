<?php

namespace App\Service;

use App\Service\Chromecast\CastMessage;
use App\Service\Chromecast\CCDefaultMediaPlayer;
use Psr\Log\LoggerInterface;

class ChromecastManager extends AbstractManager {
    /** @var string */
    private $ip;

    /** @var integer */
    private $port;

    // Sends a picture or a video to a Chromecast using reverse
    // engineered castV2 protocol
    public $socket;
    // Socket to the Chromecast
    public $requestId = 1;
    // Incrementing request ID parameter
    public $transportid;
    // The transportid of our connection
    public $sessionid;
    // Session id for any media sessions
    public $appid;

    /** @var CCDefaultMediaPlayer */
    public $DMP;

    // Represents an instance of the Plex player
    public $lastip;
    // Store the last connected IP
    public $lastport;
    // Store the last connected port
    public $lastactivetime;
    // store the time we last did something


    public function __construct(LoggerInterface $logger, $ip, $port) {
        parent::__construct($logger);
        $this->ip = $ip;
        $this->port = $port;

        //$this->cc_connect();

        // Create an instance of the DMP for this CCDefaultMediaPlayer
        $this->DMP = new CCDefaultMediaPlayer($this);
    }

    public function cc_connect() {
        // If there is a difference of 10 seconds or more between $this->lastactivetime and the current time, then we've been kicked off and need to reconnect
        if ((!$this->socket) || ((time() - $this->lastactivetime) > 9)) {
            // Reconnect
            $contextOptions = ['ssl' => ['verify_peer' => false, 'verify_peer_name' => false,]];
            $context = stream_context_create($contextOptions);
            if ($this->socket = stream_socket_client('ssl://' . $this->ip . ":" . $this->port, $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context)) {
                $this->getLogger()->debug('Socket open !');

                $this->lastactivetime = time();

                $this->sendMessage('urn:x-cast:com.google.cast.tp.connection', '{"type":"CONNECT"}');
            } else {
                throw new \Exception("Failed to connect to remote Chromecast");
            }
        }
    }

    public function sendMessage($urn, $message, $receiver_id = null) {
        $this->cc_connect();

        $c = new CastMessage();
        $c->source_id = 'sender-0';
        $c->receiver_id = ($receiver_id == null) ? (($this->transportid == null) ? 'receiver-0' : $this->transportid) : $receiver_id;
        $c->urnnamespace = $urn;
        $c->payloadtype = 0;
        $c->payloadutf8 = $message;

        $this->getLogger()->debug('TX => ' . $c);
        fwrite($this->socket, $c->encode());
        fflush($this->socket);

        $this->lastactivetime = time();
        $this->requestId++;
    }

    public function getCastMessage() {
        $this->cc_connect();

        $response = fread($this->socket, 2000);
        $this->getLogger()->debug('RX => ' . $response . "\n");

        while (preg_match('/urn:x-cast:com.google.cast.tp.heartbeat/', $response) && preg_match('/"PING"/', $response)) {
            $this->sendMessage('urn:x-cast:com.google.cast.tp.heartbeat', '{"type":"PONG"}');

            sleep(3);
            $response = fread($this->socket, 2000);
            $this->getLogger()->debug('RX => ' . $response . "\n");

            // Wait infinitely for a packet.
            set_time_limit(30);
        }
        if (preg_match('/transportId/s', $response)) {
            preg_match('/transportId"\:"([^"]*)/', $response, $matches);
            $matches = $matches[1];
            $this->transportid = $matches;
        }
        if (preg_match('/sessionId/s', $response)) {
            preg_match('/"sessionId"\:"([^"]*)/', $response, $r);
            $this->sessionid = $r[1];
        }
        if (preg_match('/appId/s', $response)) {
            preg_match('/"appId"\:"([^"]*)/', $response, $r);
            $this->appid = $r[1];
        }

        return $response;
    }

    function connect() {
        $this->cc_connect();
        $this->sendMessage('urn:x-cast:com.google.cast.tp.connection', '{"type":"CONNECT"}');
    }

    function getStatus() {
        $this->cc_connect();

        $this->sendMessage('urn:x-cast:com.google.cast.receiver', '{"type":"GET_STATUS","requestId":' . $this->requestId . '}');

        $r = $this->getCastMessage();
        while (!$this->transportid) {
            $r = $this->getCastMessage();
        }
        return $r;
    }

    public function launch($appid) {
        $this->cc_connect();

        $this->sendMessage('urn:x-cast:com.google.cast.receiver', '{"type":"LAUNCH","appId":"' . $appid . '","requestId":' . $this->requestId . '}', 'receiver-0');

        $oldtransportid = $this->transportid;
        while ((!$this->transportid) || ($this->transportid == $oldtransportid)) {
            $this->getCastMessage();
            sleep(1);
        }
    }

    public function stop() {
        $this->cc_connect();

        $this->sendMessage('urn:x-cast:com.google.cast.receiver', '{"type":"STOP","requestId":' . $this->requestId . '}', 'receiver-0');

        $oldtransportid = $this->transportid;
        while ((!$this->transportid) || ($this->transportid == $oldtransportid)) {
            $this->getCastMessage();
            sleep(1);
        }
    }

    public function pingpong() {
        $this->sendMessage('urn:x-cast:com.google.cast.tp.heartbeat', '{"type":"PING"}');
        $this->getCastMessage();
    }
}

?>