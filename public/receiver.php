<?php
use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\Event\MessageEvent\TextMessage;

require_once '../vendor/autoload.php';
require_once '../config.php';

$contents = file_get_contents('php://input');
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($env["line"]["token"]);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $env["line"]["secret"]]);

if (empty($_SERVER["HTTP_X_LINE_SIGNATURE"])) exit();
$events = $bot->parseEventRequest($contents, $_SERVER["HTTP_X_LINE_SIGNATURE"]);

foreach ($events as $event) {
  if (!($event instanceof MessageEvent) || !($event instanceof TextMessage) || !$event->isGroupEvent()) {
      continue;
  }
  $profile = $bot->getGroupMemberProfile($event->getGroupId(), $event->getUserId());
  $profile = $profile->getJSONDecodedBody();
  $text = $event->getText();

  sendToDiscord([
    "content" => $text,
    "username" => $profile['displayName'],
    "avatar_url" => $profile['pictureUrl']
  ]);
}

function sendToDiscord($data) {
  global $env;
  if (empty($env["discord"]["webhook"])) {
      return false;
  }
  $options = ['http' => [
      'method' => 'POST',
      'content' => json_encode($data),
      'header' => implode(PHP_EOL, ['Content-Type: application/json'])
  ]];
  $options = stream_context_create($options);
  return file_get_contents($env["discord"]["webhook"], false, $options) !== false;
}