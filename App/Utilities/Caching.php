<?php

namespace App\Utilities;

class Caching
{
    private static $cachExpireTime = 3600;
    private static $cachEnable = 1;
    private static $cachFile;

    public static function init()
    {
        self::$cachFile = CACHE_DIR . "/" . md5($_SERVER['REQUEST_URI']) . ".json";
        if ($_SERVER['REQUEST_METHOD'] != "GET")
            self::$cachEnable = 0;
    }

    public static function isExistCacheFile(): bool
    {
        Caching::init();
        return (file_exists(self::$cachFile) && (time() - self::$cachExpireTime) < filemtime(self::$cachFile));
    }

    public static function start()
    {
        if (!self::$cachEnable)
            return;
        if (self::isExistCacheFile()) {
            readfile(self::$cachFile);
            exit;
        }
        ob_start();
    }
    public static function end()
    {
        if (!self::$cachEnable)
            return;
        file_put_contents(self::$cachFile, ob_get_contents());
        ob_end_flush();
    }
    public static function flush()
    {
        $cachFiles = glob(CACHE_DIR . "*");
        foreach ($cachFiles as $file)
            if (file_exists(self::$cachFile))
                unlink(self::$cachFile);
    }
}
