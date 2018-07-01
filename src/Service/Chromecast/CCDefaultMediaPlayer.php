<?php

namespace App\Service\Chromecast;

use App\Service\ChromecastManager;

class CCDefaultMediaPlayer {
	public $appId = '892551FD';
	//public $appId = 'CC1AD845';
	public $appStatusText = 'Cellar';
	//public $appStatusText = 'Default Media Receiver';
	public $mediaid;

	/** @var ChromecastManager */
	public $chromecast; // The chromecast the initiated this instance.

	public function __construct($hostchromecast) {
		$this->chromecast = $hostchromecast;
	}
	
	public function play($url, $streamType = 'BUFFERED', $contentType = 'video/mp4', $autoPlay = true, $currentTime = 0) {
	    // Start a playing
		// First ensure there's an instance of the DMP running
		$this->launch();

		$message = [
		    'requestId' => $this->chromecast->requestId,
            'type' => 'LOAD',
            'autoPlay' => true,
            'currentTime' => 0,
            'media' => [
                'contentId' => $url,
                'streamType' => $streamType,
                'contentType' => $contentType
            ]
        ];
		$json = json_encode($message);
		$this->chromecast->sendMessage("urn:x-cast:com.google.cast.media", $json);
		$r = '';
		while (!(preg_match('/"playerState":"PLAYING"/', $r) || preg_match('/"playerState":"IDLE"/', $r))) {
			$r = $this->chromecast->getCastMessage();
			sleep(1);
		}
		// Grab the mediaSessionId
		preg_match('/"mediaSessionId":([^\,]*)/', $r, $m);
		$this->mediaid = $m[1];
	}

	public function launch() {
		// Launch the player or connect to an existing instance if one is already running
		// First connect to the chromecast
		$this->chromecast->transportid = '';
		$this->chromecast->cc_connect();
		$s = $this->chromecast->getStatus();
		// Grab the appId
		preg_match('/"appId":"([^"]*)/', $s, $m);
		$appId = $m[1];
		if ($appId == $this->appId) {
			// Default Media Receiver is live
			$this->chromecast->connect();
			$this->getStatus();
		} else {
			// Default Media Receiver is not currently live, start it.
			$this->chromecast->launch($this->appId);
			$this->chromecast->transportid = '';
			$r = '';
			while (!preg_match('/"appId":"' . $this->appId . '"/', $r) && !preg_match('/"statusText":"' . $this->appStatusText . '"/', $r)) {
				$r = $this->chromecast->getStatus();
				echo $r;
				sleep(1);
			}
			$this->chromecast->connect();
		}
	}
	
	public function pause() {
		// Pause
		$this->launch(); // Auto-reconnects
		$this->chromecast->sendMessage("urn:x-cast:com.google.cast.media",'{"type":"PAUSE", "mediaSessionId":' . $this->mediaid . ', "requestId":1}');
		$this->chromecast->getCastMessage();
	}

	public function restart() {
		// Restart (after pause)
		$this->launch(); // Auto-reconnects
		$this->chromecast->sendMessage("urn:x-cast:com.google.cast.media",'{"type":"PLAY", "mediaSessionId":' . $this->mediaid . ', "requestId":1}');
		$this->chromecast->getCastMessage();
	}
	
	public function seek($secs) {
		// Seek
		$this->launch(); // Auto-reconnects
		$this->chromecast->sendMessage("urn:x-cast:com.google.cast.media",'{"type":"SEEK", "mediaSessionId":' . $this->mediaid . ', "currentTime":' . $secs . ',"requestId":1}');
		$this->chromecast->getCastMessage();
	}
	
	public function stop() {
		// Stop
		$this->launch(); // Auto-reconnects
		$this->chromecast->sendMessage("urn:x-cast:com.google.cast.media",'{"type":"STOP", "mediaSessionId":' . $this->mediaid . ', "requestId":1}');
		$this->chromecast->getCastMessage();
	}
	
	public function getStatus() {
		$this->chromecast->sendMessage("urn:x-cast:com.google.cast.media",'{"type":"GET_STATUS", "requestId":1}');
		$r = $this->chromecast->getCastMessage();
		// Grab the mediaSessionId
		preg_match('/"mediaSessionId":([^\,]*)/', $r, $m);
		$this->mediaid = $m[1];
	}
	
	public function Mute() {
		// Mute a video
		$this->launch(); // Auto-reconnects
		$this->chromecast->sendMessage("urn:x-cast:com.google.cast.receiver", '{"type":"SET_VOLUME", "volume": { "muted": true }, "requestId":1 }');
		$this->chromecast->getCastMessage();
	}
	
	public function UnMute() {
		// Mute a video
		$this->launch(); // Auto-reconnects
		$this->chromecast->sendMessage("urn:x-cast:com.google.cast.receiver", '{"type":"SET_VOLUME", "volume": { "muted": false }, "requestId":1 }');
		$this->chromecast->getCastMessage();
	}
	
	public function SetVolume($volume) {
		// Mute a video
		$this->launch(); // Auto-reconnects
		$this->chromecast->sendMessage("urn:x-cast:com.google.cast.receiver", '{"type":"SET_VOLUME", "volume": { "level": ' . $volume . ' }, "requestId":1 }');
		$this->chromecast->getCastMessage();
	}
}

?>