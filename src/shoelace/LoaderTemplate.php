<?php

namespace Shoelace;

class LoaderTemplate implements Loader {
    private $key;
    
    private $paths;
    
    private $next;
    
    public function __construct($key, $paths, Loader $next=null) {
        $this->key = $key;
        
        $this->paths = $this->paths($paths);
        
        $this->next = $next;
    }
    
    public function paths($paths) {
        $array = array_shift($paths);

        while ($current = array_shift($paths)) {
            $values = array();

            foreach ((array)$array as $a) {
                foreach ((array)$current as $b) {
                    $values[] = $a.$b;
                }
            }

            $array = $values;
        }

        return $values;
    }

    public function glob($pattern) {
        return glob($pattern, GLOB_BRACE);
    }
    
    public function find($pattern, $count = -1) {
        $files = array();
            
        foreach ($this->paths as $path) {
            if ($count >= 0 && $count - count($files) <= 0) {
                break;
            }
            
            echo $path.$pattern;
            
            $files = array_merge($files, $this->glob($path.$pattern));
        }
        
        return $files;
    }

    public function loader($key) {
        if ($this->key === $key) {
            return $this;
        }
        
        if ($this->next !== null) {
            return $this->next->loader($key);
        }
        
        return null;
    }
}
