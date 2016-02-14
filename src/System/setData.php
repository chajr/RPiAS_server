<?php

namespace System;

class setData
{
    public function __construct($request, $response, $view)
    {
        $view->setData([
            'status' => 'success',
            'message' => 'message from system',
        ]);
        $request->query->get('key', 'default');
        $response->content->set($view->__invoke());
    }
}
