<?php

namespace nodoxi\craftsimplevideosizes;

use Craft;
use yii\base\Event;
use craft\base\Plugin;
use craft\services\Gql;
use craft\events\RegisterGqlDirectivesEvent;
use nodoxi\craftsimplevideosizes\gql\directives\VideoWidth;
use nodoxi\craftsimplevideosizes\gql\directives\VideoHeight;

/**
 * Simple Video Sizes plugin
 *
 * @method static SimpleVideoSizes getInstance()
 * @author nodoxi <nodoxi@gmail.com>
 * @copyright nodoxi
 * @license MIT
 */
class SimpleVideoSizes extends Plugin
{
    public string $schemaVersion = '1.0.2';

    public function init()
    {
        parent::init();

        // Custom initialization code goes here...
        Event::on(
            Gql::class,
            Gql::EVENT_REGISTER_GQL_DIRECTIVES,
            function(RegisterGqlDirectivesEvent $event) {
                $event->directives[] = VideoWidth::class;
                $event->directives[] = VideoHeight::class;
            }
        );
    }
}
