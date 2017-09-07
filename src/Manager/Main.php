<?php

namespace Manager;

class Main
{
    /**
     * @param \Aura\Web\Request $request
     * @param \Aura\Web\Response $response
     * @param \Aura\View\View $view
     */
    public function __construct($request, $response, $view)
    {
        $message = 'Main Page';

        $view->setData([
            'message' => $message,
        ]);

        $response->content->set($view());
    }
}
