<?php
namespace Segment\Config;

return array(

    'segment_ignore_load' => [

        'dev'           => false,
        'production'    => false

    ],

    'segment_ignore_above_user_level'   => [

        'dev'           => '11',
        'production'    => '11'

    ],

    'segment_environment_urls'  => [

        'dev'   => [

            '.creativefuse.io',
            '.local',
            '.dev',
            'localhost'

        ]

    ],

);