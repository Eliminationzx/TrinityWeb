<?php

namespace core\modules\i18n\controllers\actions;

use Yii;
use core\modules\i18n\models\searches\LanguageSearch;
use core\modules\i18n\bundles\LanguageAsset;
use core\modules\i18n\bundles\LanguagePluginAsset;

/**
 * Class that creates a list of languages.
 *
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.0
 */
class ListAction extends \yii\base\Action
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        LanguageAsset::register($this->controller->view);
        LanguagePluginAsset::register($this->controller->view);
        parent::init();
    }

    /**
     * List of languages
     *
     * @return string
     */
    public function run()
    {
        $searchModel = new LanguageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->sort = ['defaultOrder' => ['status' => SORT_DESC]];

        return $this->controller->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}
