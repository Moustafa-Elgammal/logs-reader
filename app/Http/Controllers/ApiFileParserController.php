<?php

namespace App\Http\Controllers;

use App\Parser\Factories\ApiResponseFactory;
use App\Parser\Services\FileParserService;
use Illuminate\Http\Request;

class ApiFileParserController extends Controller
{
    private $fileService;

    public function __construct()
    {
        $this->fileService = new FileParserService( 10);
    }

    /** read file
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function read(Request $request): \Illuminate\Http\JsonResponse
    {
        $file_path = $request->get('file_path');
        $lines = [];
        $current_page = 0;


        if ($this->fileService->fileExists($file_path) && $this->fileService->fileCanBeOpen($file_path))
        {
            $this->fileService->openFile($file_path);
            $lines = $this->fileService->getFileHead();
            $this->fileService->closeFile();
            $current_page = $this->fileService->getCurrentPage();
            $errors = $this->fileService->getErrors();
        } else {
            $errors = $this->fileService->getErrors();
        }

        return ApiResponseFactory::response($lines, $errors,$current_page);
    }

    public function tail(Request $request): \Illuminate\Http\JsonResponse
    {
        $file_path = $request->get('file_path');
        $lines = [];
        $current_page = 0;

        if ($this->fileService->fileExists($file_path) && $this->fileService->fileCanBeOpen($file_path))
        {
            $this->fileService->openFile($file_path);
            $lines = $this->fileService->getFileTail();
            $this->fileService->closeFile();
            $errors = $this->fileService->getErrors();
            $current_page = $this->fileService->getCurrentPage();
        } else {
            $errors = $this->fileService->getErrors();
        }


        return ApiResponseFactory::response($lines, $errors,$current_page);
    }

    public function next(Request $request): \Illuminate\Http\JsonResponse
    {
        $file_path = $request->get('file_path');
        $current_page = (int)$request->get('current_page')  ?? 0;
        $lines = [];
        $eof = false;

        if ($this->fileService->fileExists($file_path) && $this->fileService->fileCanBeOpen($file_path))
        {
            $this->fileService->openFile($file_path);
            $lines = $this->fileService->getNextPage($current_page);
            $this->fileService->closeFile();

            $errors = $this->fileService->getErrors();
            $current_page = $this->fileService->getCurrentPage();
            $eof = $this->fileService->getEof();
        } else {
            $errors = $this->fileService->getErrors();
        }


        return ApiResponseFactory::response($lines, $errors,$current_page, $eof);
    }

    public function previous(Request $request): \Illuminate\Http\JsonResponse
    {
        $file_path = $request->get('file_path');
        $current_page = (int)$request->get('current_page')  ?? 0;
        $lines = [];

        if ($this->fileService->fileExists($file_path) && $this->fileService->fileCanBeOpen($file_path))
        {
            $this->fileService->openFile($file_path);
            $lines = $this->fileService->getPreviousPage($current_page);
            $this->fileService->closeFile();

            $errors = $this->fileService->getErrors();
            $current_page = $this->fileService->getCurrentPage();
        } else {
            $errors = $this->fileService->getErrors();
        }

        return ApiResponseFactory::response($lines, $errors,$current_page);

    }
}
