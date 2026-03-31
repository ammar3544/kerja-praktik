<?php

namespace App\Services;

class EntropyAnalyzer
{

    public function calculate($username)
    {

        $length = strlen($username);

        $freq = [];

        for($i=0;$i<$length;$i++){

            $char = $username[$i];

            if(!isset($freq[$char])){
                $freq[$char] = 0;
            }

            $freq[$char]++;

        }

        $entropy = 0;

        foreach($freq as $count){

            $p = $count/$length;

            $entropy -= $p * log($p,2);

        }

        return $entropy;

    }

}