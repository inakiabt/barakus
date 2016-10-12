<?php
    class FileHelper
    {
        function dirList ($directory) 
        {

            // create an array to hold directory list
            $results = array();

            // create a handler for the directory
            $handler = opendir($directory);

            // keep going until all files in directory have been read
            while ($file = readdir($handler)) {

                // if $file isn't this directory or its parent, 
                // add it to the results array
                if ($file != '.' && $file != '..')
                    $results[] = $file;
            }

            // tidy up: close the handler
            closedir($handler);

            // done!
            return $results;

        }        
    }
?>
