<?php

class Images
{
    public function saveImage($inPath, $outPath)
    {
        //Download images from remote server
        $in  = fopen($inPath, "rb");
        $out = fopen($outPath, "w+");

        while ($chunk = fread($in, 8192)) {
            fwrite($out, $chunk, 8192);
        }
        fclose($in);
        fclose($out);

        return $outPath;
    }

    public function makeFolder($targetFolder)
    {
        return mkdir($targetFolder, 0755);
    }

    public function deleteFile($path)
    {
        return File::delete($path);
    }

    public function deleteFileAll($path, $name)
    {
        $filelist = array();
        if ($handle = opendir($path)) {
            while ($entry = readdir($handle)) {
                if (strpos($entry, $name) === 0) {
                    $image_path = $path. '/' . $entry;
                    $image_delete = $this->deleteFile($image_path);
                    $filelist[] = $entry;

                    if (!$image_delete) {
                        return false;
                    }
                }
            }
            closedir($handle);
        }

        return $filelist;
    }
}
