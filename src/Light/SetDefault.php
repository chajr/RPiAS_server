<?php

namespace Light;

use Command\Manage;

class SetDefault extends LightSwitcher
{
    /**
     * setData constructor.
     *
     * @param \Aura\Web\Request $request
     * @param \Aura\Web\Response $response
     * @param \Aura\View\View $view
     */
    public function __construct($request, $response, $view)
    {
        $this->request = $request;
        $this->response = $response;
        $this->view = $view;

        $this->process(
            'Light on/off set to default.',
            function () {
                (new Manage)->setCommand(
                    Helper::createValueObject('redis-cli set rpia_illuminate_force null')
                );
            }
        );
    }
}
