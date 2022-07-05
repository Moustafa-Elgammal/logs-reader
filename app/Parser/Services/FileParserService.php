<?php

namespace App\Parser\Services;

class FileParserService extends Service
{
    protected $fileHandler;
    protected $linesPerChunk;
    protected $currentPage;
    protected $eof = false;


    public function __construct( $linesPerChunk)
    {
        $this->linesPerChunk = $linesPerChunk;
        $this->currentPage = 1;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }


    public function fileExists($filePath = ''): bool
    {
        if (file_exists($filePath))
            return true;

        $this->addError('file does not exist');
        return false;
    }

    public function fileCanBeOpen($filePath = ''): bool
    {
        if(!$this->fileExists($filePath))
        {
            $this->addError('file does not exist');
            return false;
        }

        $this->fileHandler = fopen($filePath,'r');
        if (!$this->fileHandler)
        {
            $this->addError('file can not be open');
            return false;
        }

        fclose($this->fileHandler);
        return  true;
    }

    /** init open file with handler
     * @param $filePath
     * @return void
     */
    public function openFile($filePath): void
    {
        $this->fileHandler = fopen($filePath, 'r');
    }

    /** release the file resource
     * @return void
     */
    public function closeFile()
    {
        $this->fileHandler = fclose($this->fileHandler);
    }

    /** retrieve the first line  / chunk
     * @return array
     */
    public function getFileHead(): array
    {
        $lines = [];

        while (!feof($this->fileHandler) && count($lines) < $this->linesPerChunk)
        {
            $lines [] = fgets($this->fileHandler);
        }

        return $lines;
    }

    /** get last lines of the files
     * @return array
     */
    public function getFileTail(): array
    {
        $lines = [];

        while (!feof($this->fileHandler) && $line = fgets($this->fileHandler))
        {
            // to skip not needed pages
            if (count($lines) >= $this->linesPerChunk)
            {
                $this->currentPage += 1;
                $lines = [];
            }

            $lines [] = $line;
        }

        return $lines;
    }

    /** get next chunck of lines
     * @param $current_page
     * @return array
     */
    public function getNextPage($current_page): array
    {
        $this->currentPage = (int)$current_page;
        $lines = [];
        $count = 1; // to track the handler pointer

        while (!feof($this->fileHandler) && count($lines) < $this->linesPerChunk && $line = fgets($this->fileHandler))
        {
            //add page lines
            if ($count > $this->currentPage * $this->linesPerChunk)
                $lines [] = $line;

            $count +=1;
        }

        // set eof flag to disable front end tail and previous
        if (!fgets($this->fileHandler))
            $this->eof = true;

        $this->currentPage = (int)$current_page +  1;

        // no pages for next retrieve
        if (count($lines) == 0)
            $this->addError('end of file');

        return $lines;
    }

    /**
     * @param $current_page
     * @return array
     */
    public function getPreviousPage($current_page): array
    {
        // ship handling pages if it was first page
        if ((int)$current_page <= 1)
            return $this->getFileHead();

        $this->currentPage = (int)$current_page;
        $lines = [];
        $count = 1;

        while (!feof($this->fileHandler) && count($lines) < $this->linesPerChunk && $line = fgets($this->fileHandler))
        {
            if ($count > $this->currentPage * $this->linesPerChunk - 20)
                $lines [] = $line;

            $count +=1;
        }

        // update current page
        $this->currentPage = (int)$current_page -  1;

        // set error when over range pages
        if (count($lines) == 0)
            $this->addError('end of file');

        return $lines;
    }

    /** this flog helps to check the end of file
     * @return bool
     */
    public function getEof()
    {
        return $this->eof;
    }


}
