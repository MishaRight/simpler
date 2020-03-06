<?php

namespace classes;

/**
 * Logging is on, all errors and script actions are recorded.
 */
defined('LOG_TYPE_ENABLED')     or define('LOG_TYPE_ALL', 1);

/**
 * Logging is disabled, but critical errors 
 * and exceptions will be recorded.
 */
defined('LOG_TYPE_DISABLED')    or define('LOG_TYPE_DISABLED', 0);

/**
 * Date output format, 
 * broken so that you can sort the logs by month and date.
 */
defined('LOG_DATE_TYPE')        or define('LOG_DATE_TYPE', 'd-m-Y ');

/**
 * Log generation time.
 */
defined('LOG_TIME_TYPE')        or define('LOG_TIME_TYPE', 'G-i ');

/**
 * Logs file name
 */
defined('LOG_FILE_NAME')        or define('LOG_FILE_NAME', 'logs.txt');


/**
 * Logging and documentation of the script.
 * It is used to debug, search and correct errors.
 * 
 * @author Misha Right <mikhail.lacroix@gmail.com>
 * @copyright (c) 2020, Misha Right <mikhail.lacroix@gmail.com>
 */
class logsUtils {
    
    /**
     * Directories in which the logs will be recorded 
     * and catalogs will be created.
     * @var String
     * @access private
     */
    private static $_directory;
    
    /**
     * Logging settings:
     * 0 - Only critical errors will be recorded.
     * 1 - All important events will be recorded.
     * 2 - Full logging of actions, up to a unique connection.
     * @var Integer
     * @access private
     */
    private static $_type;
    
    /**
     *
     * If true, the logs will be sorted by month in a separate folder.
     * If false, only one file will be created 
     * and everything will be written to it
     * @var Boolean 
     * @access private
     */
    private static $_method = false;
    
    /**
     * Initialization and initial settings of logging.
     * Before working with logging, you need to call this method, 
     * otherwise the record will be kept by default.
     * 
     * @param String $dir Directory where the logs will be stored
     * @param Integer $type Logging Options
     * @param Boolean $method recording method
     */
    public static function Init($dir, $type = null, $method = false){
        is_dir($dir)    ? self::$_directory = $dir 
                        : self::$_directory = __DIR__ ;
        $type == null   ? self::$_type = LOG_TYPE_DISABLED
                        : self::$_type = $type;
        //Folder generation by date
        if($method) self::$_method = true;
    }
    
    /**
     * Returns the folder in which the log file should be written.
     * 
     * @return String
     * @access private
     * @throws type
     */
    private static function getDirectory( ) : string {
        $dir = date(LOG_DATE_TYPE);
        if(is_dir(self::$_directory . $dir) == false){
            if(!mkdir(self::$_directory . $dir, 0700))
                    throw('Failed to create log folder!');
        }
        return self::$_directory . $dir . DIRECTORY_SEPARATOR;
    }
    
    /**
     * Opening and writing information to a file.
     * If you managed to successfully create, open and write a file, 
     * it will return true
     * @param string $fileName File in which to write information
     * @param string $text The text to be written to the file
     * @return bool
     */
    private static function writeLog($fileName, $text) : bool {
        if((!$fp = fopen($fileName, "a")) || !fwrite($fp, $text)) return false;
        fclose($fp);
        return true;
    }
    
    /**
     * Formatting text and messages.
     * By default adds date and time and line break at the end.
     * @param string $text String message
     * @return string formatting message
     */
    private static function format($text) : string {
        $text = date(LOG_DATE_TYPE . LOG_TIME_TYPE) . " " . $text . "\n";
        return $text;  
    }
    
    /**
     * Write information in the logs.
     * @param String $text The message to be recorded.
     * @param Boolean $exit If after recording you need to stop the script
     */
    public static function Log($text, $exit = false)
    {
        $text = self::format($text);
        self::$_method  ?   trim($path = self::getDirectory()    . LOG_FILE_NAME)
                        :   trim($path = self::$_directory       . LOG_FILE_NAME);
        if(self::writeLog($path, $text)) echo 'Логи записаны в файл!';
        
    }
    
    
    
    
    
}
