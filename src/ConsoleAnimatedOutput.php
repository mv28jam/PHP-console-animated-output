<?php

/*
 * Console live output utilite.
 * Progress spiner out.
 * One line output.
 * Multiple lines utput - like table draw.
 */

namespace ConsoleAnimated;

/**
 * Description of ConsoleAnimatedOutput
 *
 * @author mv28jam <mv28jam@yandex.ru>
 */

class ConsoleAnimatedOutput extends \stdClass {
    /**
     * @link www.inwap.com/pdp10/ansicode.txt
     * console commands
     */
    const DELETE_LINE = "[0K";
    const CURSOR_BEGIN = "[0G";
    const CURSOR_UP = "[1A";
    const BACKSPACE = "[D";
    const COLOR_START = "[0;%m";
    const COLOR_END = "[0m";
    
    /**@var int command symbol */
    protected $symbol=27;
    /**@var array $simple_spin*/
    protected $simple_spin=['|', '/', '-', '\\'];
    /**@var array $simple_time spin move time + delay*/
    protected $spin_time=0.0;
    
    
    /**
     * @param void
     * @return self
     * @throws Exception
     */
    public function __construct()
    {
        if(!defined('STDIN')){
            new \Exception('Only in console mode.');
        }
    }
    
    /**
     * @param string $line line to output
     * @return bool false on newline in input
     * echo one line // delete progress for example
     */
    public function echoLine(string $line):bool
    {
        //trim ending new line 
        $line=rtrim ($line, "\n" );
        //check for new lines in body
        if(strpos($line, "\n")!==false){
            return false;
        }
        //echo actions
        echo chr($this->symbol) .self::CURSOR_BEGIN;
        echo chr($this->symbol) .self::DELETE_LINE;
        echo $line;
        //
        return true;
    }
    
    /**
     * @param array $lines array of lines to output
     * @param int $skip skip delete of line up
     * @return int count of echo
     * erase and echo several lines
     * erased lines = count($lines) 
     */
    public function echoMultipleLine(array $lines, int $skip=0):int
    {
        //lines to print
        $cnt = count($lines);
        //
         if($skip >= 0){
            for($i=0; $i<$cnt+$skip; $i++){
                 echo chr($this->symbol) .self::CURSOR_UP;
                 if($i >= $skip){
                     echo chr($this->symbol) .self::DELETE_LINE;
                 }
            }
        }
        foreach($lines as $line){
            //remove new lines from output
             echo str_replace("\n", ' ', $line)."\n";
        }
        for($i=0; $i<$skip; $i++){
             echo "\n";
        }
        //
        return $cnt;
    }
    
    /**
     * @param void
     * @return void
     * echo spiner move by function call
     */
    public function echoSpiner()
    {
        $elem=current($this->simple_spin);
        //if out of array rewind
        if(!$elem){
            reset($this->simple_spin);
            $elem=current($this->simple_spin);
        }else{
            next($this->simple_spin);
        }
        //
        echo $elem;
        echo chr($this->symbol) .self::BACKSPACE;
    }
    
     /**
     * @param float $delay
     * @return void
     * echo spiner move by delay
     */
    public function echoSpinerDelay(float $delay)
    {
        if($this->spin_time < microtime(true)){
            $this->spin_time = microtime(true)+$delay;
            $this->echoSpiner();
        }
    }
    
    /**
     * @param string $line
     * @param int $color 
     * @return void
     * echo colored console output
     */
    public function echoColor(string $line, int $color=0)
    {
        echo chr($this->symbol) .str_replace('%', $color, self::COLOR_START);
        echo $line;
        echo chr($this->symbol) .self::COLOR_END;
    }
    
    /**
     * @param int $step how many lines up
     * @return void
     * cursor up
     */
    public function cursorUp(int $step=1)
    {
        for($i=0; $i < $step; $i++){
            echo chr($this->symbol) .self::CURSOR_UP;
        }
    }
    
    /**
     * @param int $step how many new lines 
     * @return void
     * new empty line
     */
    public function echoEmptyLine(int $step=1)
    {
        for($i=0; $i < $step; $i++){
            echo "\n";
        }
    }
    
    
   
}
