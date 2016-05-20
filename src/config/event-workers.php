<?php

return [
  'twitter' => [
      'delay'    => 1,
      'throttle' => '1:1',
      'workers'  => 1,
      'threads'  => [
          'min'     => 1,
          'max'     => 3,
          'timeout' => 30,
      ],
      'class' => \App\Events\FetchTwitterEvent::class,
  ],
  'twitter_user' => [
      'delay'    => .25,
      'throttle' => '1:1',
      'workers'  => 2,
      'threads'  => [
          'min'     => 5,
          'max'     => 10,
          'timeout' => 30,
      ],
      'class' => \App\Events\FetchTwitterEvent::class,
  ],
];
