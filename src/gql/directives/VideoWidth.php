<?php

namespace nodoxi\craftsimplevideosizes\gql\directives;

use Yii;
use craft\gql\base\Directive;
use craft\gql\GqlEntityRegistry;
use GraphQL\Language\DirectiveLocation;
use GraphQL\Type\Definition\Directive as GqlDirective;
use GraphQL\Type\Definition\FieldArgument;
use GraphQL\Type\Definition\ResolveInfo;

class VideoWidth extends Directive
{
    public function __construct(array $config)
    {
        $args = &$config['args'];

        foreach ($args as &$argument) {
            $argument = new FieldArgument($argument);
        }

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public static function create(): GqlDirective
    {
        if ($type = GqlEntityRegistry::getEntity(self::class)) {
            return $type;
        }

        $type = GqlEntityRegistry::createEntity(static::name(), new self([
            'name' => static::name(),
            'locations' => [
                DirectiveLocation::FIELD,
            ],
            'args' => array(),
            'description' => 'This directive is used to return a video dimensions.'
        ]));

        return $type;
    }

    /**
     * @inheritdoc
     */
    public static function name(): string
    {
        return 'get_width';
    }

    /**
     * @inheritdoc
     */
    public static function apply($source, $value, array $arguments, ResolveInfo $resolveInfo): mixed
    {
        $file = str_replace(Yii::getAlias('@web'), CRAFT_BASE_PATH . '/web', $value);
        
        // check if file exists
        if (!file_exists($file)) {
          return 'NO FILE: ' . $file;
        }
        $getID3 = new \getID3();
        $videoInfo = $getID3->analyze($file);
        if (!is_array($videoInfo)) {
          return null;
        }       
        if (empty($videoInfo['video']) || empty($videoInfo['video']['resolution_x'])) {
            return null;
        } 
        return $videoInfo['video']['resolution_x'];
    }
}