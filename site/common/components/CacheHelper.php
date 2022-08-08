<?php

namespace common\components;

use yii\caching\FileDependency;

abstract class CacheHelper
{
    public static  function getGlobalDependency()
    {
        return new FileDependency(['fileName' => '@runtime/test']);
    }

    public static function refreshGlobalCache()
    {
        file_put_contents(\Yii::getAlias('@runtime/test'), rand());
    }
}