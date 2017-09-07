<?php

namespace Alert;

use Log\Log;
use Symfony\Component\Filesystem\Filesystem;
use Config\Config;

class Uploader
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
        $status = 'success';
        $message = 'Image saved successfully.';

        $image = $request->files->get(
            'file',
            ['error' => 1]
        );

        $secureToken = (new Config)->getConfig()['secure_token'];
        $retrievedSecureToken = $request->query->get('key', '');

        if ($secureToken !== $retrievedSecureToken) {
            $status  = 'error';
            $message = 'Incorrect secure token';
        } else {
            if ($image['error'] === 0) {
                $fileSystem = new Filesystem();
                $tmpName = $image['tmp_name'];
                $path = '../storage/';

                try {
                    $fileSystem->copy($tmpName, $path . $image['name']);
                } catch (\Exception $e) {
                    Log::addError('Image upload error: ' . $e->getMessage());
                    $status = 'error';
                    $message = 'Move image exception: ' . $e->getMessage();
                }
            } else {
                Log::addError('Image upload error: ' . serialize($image));
                $status = 'error';
                $message = 'Image upload error';
            }
        }

        $view->setData([
            'status' => $status,
            'message' => $message,
        ]);

        $response->content->set($view());
    }
}
