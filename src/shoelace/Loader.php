<?php

namespace Shoelace;

interface Loader {
    function loader($key);
    
    function find($pattern, $count=-1);
}
