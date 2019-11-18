<?php
namespace common\components\helper;

use Yii;

class StaticAssetsHelper
{

    public static function includeAppStatic($type, $path, $depend){
        $release_version = defined("RELEASE_VERSION") ? RELEASE_VERSION : "20190910223200";
        if (stripos($path, "?") !== false) {
            $path = $path . "&version={$release_version}";
        } else {
            $path = $path . "?version={$release_version}";
        }

        if ($type == "css") {
            Yii::$app->getView()->registerCssFile($path, ['depends' => $depend]);
        } else {
            Yii::$app->getView()->registerJsFile($path, ['depends' => $depend]);
        }
    }

    public static function includeAppJsStatic($path, $depend)
    {
        self::includeAppStatic("js", $path, $depend);
    }

    public static function includeAppCssStatic($path, $depend)
    {
        self::includeAppStatic("css", $path, $depend);
    }

    public static function getReleaseVersion(){
        $release_version = defined("RELEASE_VERSION") ? RELEASE_VERSION : "20190910223200";
        return $release_version;
    }

} 