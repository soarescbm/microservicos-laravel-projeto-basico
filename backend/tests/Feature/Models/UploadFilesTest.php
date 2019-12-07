<?php

namespace Tests\Feature\Models;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Stubs\Model\UploadFileStub;
use Tests\TestCase;


class UploadFilesTest extends TestCase
{
    use DatabaseMigrations;

    private $obj;


    public function setUp(): void
    {

        $this->obj = new UploadFileStub();
        parent::setUp();
        UploadFileStub::dropTable();
        UploadFileStub::makeTable();
    }

    public function testMakeOldFilesOnSaving()
    {

        $this->obj =  UploadFileStub::create([
            'name' => 'test',
            'file1' => 'test1.mp4',
            'file2' => 'test2.mp4'
        ]);

        $this->assertCount(0, $this->obj->oldFiles);

        $valor = $this->obj->update([
            'name' => 'test_name',
            'file2' => 'test3.mp4'
        ]);


        $this->assertEqualsCanonicalizing(['test2.mp4'], $this->obj->oldFiles);
    }

    public function testMakeOldFilesNullOnSaving()
    {

        $this->obj = UploadFileStub::create([
            'name' => 'test',
        ]);


        $this->obj->update([
            'name' => 'test_name',
            'file1' => 'test2.mp4'
        ]);

        $this->assertEqualsCanonicalizing([], $this->obj->oldFiles);
    }

}
