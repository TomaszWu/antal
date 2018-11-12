<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CreatePath
{

    /**
     * @var ParameterBagInterface $parameteBag
     */
    private $params;

    /**
     * CreatePath constructor.
     * @param ParameterBagInterface $params
     */
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * @param $path
     * @return string
     */
    public function create($path): string
    {
        $elements = explode('/', $path);

        $createdPath = $this->params->get('kernel.project_dir');

        foreach ($elements as $element) {

            $createdPath .= sprintf('/%s',  $element);

            if (!file_exists($createdPath)) {
                mkdir($createdPath, 0755);
            }
        }

        return $createdPath;
    }
}
