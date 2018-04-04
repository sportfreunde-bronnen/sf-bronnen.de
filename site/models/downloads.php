<?php

/**
 * Class DownloadsPage
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
class DownloadsPage extends Page
{
    /**
     * Converts the files selected by selector to file collection
     */
    public function convertFilesToCollection($selectedFiles)
    {
        $filenames = $selectedFiles->split(',');
        if(count($filenames) < 2) {
            $filenames = array_pad($filenames, 2, '');
        }
        return call_user_func_array(array($this->files(), 'find'), $filenames);
    }

    /**
     * Converts the file size in MB
     *
     * @param int $fileSizeInKb
     * @return string
     */
    public function convertFileSize($fileSizeInKb = 0)
    {
        return sprintf('%s MB', number_format((($fileSizeInKb / 1024)/1024), 2));
    }
}