<?php

namespace Tests\Feature;

use App\Parser\Services\FileParserService;
use Tests\TestCase;

class ParserServiceTest extends TestCase
{
    private $service;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {

        $this->service = new FileParserService(10);

        parent::__construct($name, $data, $dataName);
    }

    /**
     * A test exist method
     *
     * @return void
     */
    public function test_service_file_exist_with_true()
    {
        $path = public_path('test.log');
        $this->assertTrue($this->service->fileExists($path));
    }

    /** test exist with false
     * @return void
     */
    public function test_service_file_exist_with_false()
    {
        $path = public_path('notfound.log');
        $this->assertFalse($this->service->fileExists($path));
    }

    /** file can be existed and open
     * @return void
     */
    public function test_service_file_can_be_open()
    {
        $path = public_path('test.log');
        $this->assertTrue($this->service->fileCanBeOpen($path));
    }

    /** failed try to open not found file
     * @return void
     */
    public function test_service_file_can_not_be_open()
    {
        $path = public_path('notFound.log');
        $this->assertFalse($this->service->fileCanBeOpen($path));
    }

    /**
     * @return void
     */
    public function test_service_file_head_return_array()
    {
        $path = public_path('test.log');
        $this->service->openFile($path);
        $head = $this->service->getFileHead();
        $this->service->closeFile();
        $this->assertIsArray($head);
    }

    public function test_service_file_tail_return_array()
    {
        $path = public_path('test.log');
        $this->service->openFile($path);
        $tail = $this->service->getFileTail();
        $this->service->closeFile();
        $this->assertIsArray($tail);
    }

    public function test_service_file_head_contain_first_line()
    {
        $path = public_path('nums.log');
        $this->service->openFile($path);
        $head = $this->service->getFileHead();
        $this->service->closeFile();
        $this->assertStringContainsString('first line', $head[0]);
    }


    public function test_service_file_tail_contain_last_line()
    {
        $path = public_path('nums.log');
        $this->service->openFile($path);
        $head = $this->service->getFileTail();
        $this->service->closeFile();
        $this->assertStringContainsString('last line', $head[count($head) - 1]);
    }


    public function test_service_file_next_page_return_array()
    {
        $path = public_path('test.log');
        $this->service->openFile($path);
        $next = $this->service->getNextPage(1);
        $this->service->closeFile();
        $this->assertIsArray($next);
    }

    public function test_service_file_previous_page_return_array()
    {
        $path = public_path('test.log');
        $this->service->openFile($path);
        $prev = $this->service->getPreviousPage(2);
        $this->service->closeFile();
        $this->assertIsArray($prev);
    }
}
