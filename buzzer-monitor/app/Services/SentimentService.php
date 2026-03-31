<?php

namespace App\Services;

class SentimentService
{

    private $positive = [
        'bagus','mantap','keren','good','nice','love','suka'
    ];

    private $negative = [
        'jelek','buruk','bodoh','hate','bad','parah'
    ];

    public function analyze($text)
    {

        $text = strtolower($text);

        foreach($this->positive as $p){

            if(str_contains($text,$p)){
                return "positif";
            }

        }

        foreach($this->negative as $n){

            if(str_contains($text,$n)){
                return "negatif";
            }

        }

        return "netral";

    }

}