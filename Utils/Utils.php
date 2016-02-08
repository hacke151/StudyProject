<?php

namespace Utils;

use PDO;

class Utils {

    const host = "127.0.0.1";
    const db = "test1c";
    const user = "root";
    const pass = "2505";
    const charset = "utf8";

    private static $pdo = null;

    public function __construct() {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
    }

    public static function getConnect() {
        if (self::$pdo == null) {
            $dsn = "mysql:host=".self::host.";dbname=".self::db.";charset=".self::charset;
            $opt = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            );

            try {
                self::$pdo = new PDO($dsn, self::user, self::pass, $opt);
            } catch (Exception $ex) {
                echo $ex;
                exit();
            }
        }
        return self::$pdo;
    }

    public static function simplePage($title, $text) {
        include("Templates/justText.html");
    }

     public static function writeFile($file, $content){
        $dir = dirname($file);
        if (!is_dir($dir)) {
            if (false === mkdir($dir, 0777, true)){
                throw new \RuntimeException(sprintf("Unable to create the %s directory", $dir));
            }
        } elseif (!is_writable($dir)) {
            throw new \RuntimeException(sprintf("Unable to write in %s directory", $dir));
        }
        
        $tmpFile = tempnam($dir, basename($file));
        
        if (false !== file_put_contents($tmpFile, $content) && rename($tmpFile, $file)){
            chmod($file, 0666 & ~umask());
        } else {
            throw new \RuntimeException(sprintf('Failed to write cache file "%s".', $file));
        }
    }
}
