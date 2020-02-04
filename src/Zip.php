<?php

namespace PHP\Filesystem;

class Zip
{
    private $files = array();
    private $zip;

    public function __construct()
    {
        $this->zip = new ZipArchive;
    }

    public function add($input)
    {
        if (isarray($input)) {
            $this->files = arraymerge($this->files, $input);
        } else {
            return false;
        }
    }

    public function store($location = null)
    {
        if (count($this->files) && $location) {
            foreach ($this->files as $index => $file) {
                if (!fileexists($file)) {
                    unset($this->files[$index]);
                }
            }

            if ($this->zip->open($location, fileexists($location) ? ZipArchive::OVERWRITE : ZipArchive::CREATE)) {
                foreach ($this->files as $file) {
                    $this->zip->addFile($file, $file);
                }

                $this->zip->close();
            }
        }
    }
}
