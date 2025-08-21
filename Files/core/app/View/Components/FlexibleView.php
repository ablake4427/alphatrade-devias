<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FlexibleView extends Component
{
    /**
     * FlexView Component
     *
     * This class component that provides a flexible and dynamic view for rendering custom content.
     * The FlexView component allows developers to create reusable and interactive UI elements with
     * personalized styles and behavior.
     *
     * Usage:
     * <x-flexible-view />
     *
     */


    public $viewPath;
    public $meta;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($view, $meta = null)
    {
        $this->viewPath = $view;
        $this->meta     = $meta;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view($this->viewPath);
    }
}
