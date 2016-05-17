<?php

class Tools_Viewhelper {

    public static function formatSummary($summary) {
        $summary = str_replace(array("<em>","</em>"),array("<b>","</b>"),$summary);
        return $summary;
    }

    private function _rand ($min, $max) {
        return rand($min, $max);
    }
}

