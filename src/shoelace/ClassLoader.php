<?php

namespace Shoelace;

use \Exception;

class ClassLoader extends LoaderTemplate {
    private $translates = array('\\'=>'/');
    
    public function __construct($key, $paths, \Loader $next = null) {
        parent::__construct($key, $paths, $next);
        
        foreach (range('a', 'z') as $i) {
            $j = strtoupper($i);
            $this->translates[$i] = "{{$i},{$j}}";
            $this->translates[$j] = "{{$i},{$j}}";
        }
    }
    
    public function register() {
        spl_autoload_register(array($this, 'autoload'), true);
    }
    
    public function autoload($class) {
        $pattern = strtr($class, $this->translates);
        
        $file = array_shift($this->find($pattern.'.php', 1));
        
        if ($file === null) {
            throw new Exception;
        }
        
        require_once($file);
    }
}
