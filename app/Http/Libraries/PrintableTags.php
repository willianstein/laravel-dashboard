<?php

namespace App\Http\Libraries;

class PrintableTags {

    public const TEMPLATES = [
        'tags.modelOne'     => 'Modelo 1'
    ];


    public static function template(string $template):? string {
        return (array_search($template,self::TEMPLATES)??null);
    }

}
