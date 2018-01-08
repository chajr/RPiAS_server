<?php

namespace Light;

use Command\Manage;

class Enable extends LightSwitcher
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
            'Light turned on.',
            function () {
                $manager = new Manage;

                $manager->setCommand(Helper::createValueObject('redis-cli set rpia_illuminate_force on'));
                $manager->setCommand(Helper::createValueObject('redis-cli set rpia_illuminate_status on'));
            }
        );
    }
}
