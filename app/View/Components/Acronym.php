<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Acronym extends Component
{
    public $text;

    public static function createAcronym($name)
    {
        $words = explode(' ', $name);

        $firstChar = substr($words[0], 0, 1);

        $lastChar = count($words) > 1 ? substr($words[count($words) - 1], 0, 1) : '';

        $acronym = strtoupper($firstChar . $lastChar);

        return $acronym;
    }

    /**
     * Create a new component instance.
     */
    public function __construct($text)
    {
        $this->text = $this->createAcronym($text);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.acronym');
    }
}
