<?php

namespace chrum\yii2\translations;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'chrum\yii2\translations\controllers';

    public $defaultRoute = 'manage';

    public $defaultLang = 'en';

    public $translationsModelClass = 'common\models\Translation';
    /**
     * @var array Array with available languages
     */
    public $langs = array(
        'en' => 'English'
    );

    /**
     * Should contain an alias pointing to presets dir and selected preset
     * for example: '@vendor/chrum/yii2-translations/components/codeMirrorPresets'
     * @var string|null
     */
    public $codeMirror = [
        'presetsDir' => null,
        'preset'     => 'html'
    ];

    public function __construct($id, $parent = null, $config = [])
    {
        // In order deal with identityClass issue
        // Both in apiController and migrations
        \Yii::$container->set('user', ['class' => 'yii\web\User', 'identityClass' => 'common\models\User']);
        if (\Yii::$app->has('user')) {
            \Yii::$app->set('user', [
                'class' => 'yii\web\User',
                'identityClass' => 'common\models\User'
            ]);
        }

        // If access is limited, open these for all api methods
        if (isset($config['as access'])) {
            $config['as access']['except'] = [];
            foreach($config['langs'] as $code => $name) {
                $config['as access']['except'][] = $code;
            }
        }
        parent::__construct($id, $parent, $config);
    }

    public function init()
    {
        parent::init();
        if (\Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'chrum\yii2\translations\commands';

        } else {
            $this->setAliases([
                '@yii2-translations-assets' => __DIR__ . '/assets'
            ]);

            foreach($this->langs as $code => $name) {
                $this->controllerMap[$code] = 'chrum\yii2\translations\controllers\ApiController';
            }
        }
    }
}
