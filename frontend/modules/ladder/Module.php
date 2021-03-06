<?php

namespace frontend\modules\ladder;

use Yii;
use yii\base\BootstrapInterface;
use yii\web\GroupUrlRule;

class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @var string
     */
    public $controllerNamespace = 'frontend\modules\ladder\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->layout = 'main';
        $this->setAliases(['@ladder' => __DIR__]);
    }

    public function bootstrap($app)
    {
        if ($app instanceof \yii\web\Application) {
            $this->setDefaultSettings($app);
            $this->addUrlManagerRules($app);
        }
    }

    protected function setDefaultSettings($app)
    {
        /* @var \BaseApplication $app */
        if(Yii::$app->TrinityWeb::isAppInstalled()) {
            if (!$app->settings->get($app->settings::APP_MODULE_LADDER_STATUS))
                $app->settings->set($app->settings::APP_MODULE_LADDER_STATUS, $app->settings::ENABLED);

            if (!$app->settings->get($app->settings::APP_MODULE_LADDER_CACHE_DURATION))
                $app->settings->set($app->settings::APP_MODULE_LADDER_CACHE_DURATION, 120);

            if (!$app->settings->get($app->settings::APP_MODULE_LADDER_PER_PAGE))
                $app->settings->set($app->settings::APP_MODULE_LADDER_PER_PAGE, 5);
        }
    }

    public function beforeAction($action) {
        Yii::$app->view->params['breadcrumbs'][] = ['label' => Yii::t('ladder','Ladder'),'url' => ['/ladder/default/index']];
        if(Yii::$app->settings->get(Yii::$app->settings::APP_MODULE_LADDER_STATUS) !== Yii::$app->settings::ENABLED) {
            return Yii::$app->response->redirect(Yii::$app->homeUrl);
        }
        return parent::beforeAction($action);
    }

    protected function addUrlManagerRules($app)
    {
        $app->urlManager->addRules([new GroupUrlRule([
            'prefix' => $this->id,
            'rules' => require __DIR__ . '/url-rules.php',
        ])]);
    }
}
