<?php

namespace App\Service;

class SaveArrayContentToFile
{

    public function save(array $content, $path, $file, $mode)
    {
        $file = fopen(sprintf('%s/%s', $path, $file), $mode);

        foreach ($content as $item) {
            fwrite($file, $item);
        }

        return fclose($file);
    }
}
