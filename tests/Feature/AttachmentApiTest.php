<?php

namespace Tests\Feature;

use App\Models\Attachment;
use App\Models\Folder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AttachmentApiTest extends TestCase
{
    public function testGetAllAttachmentFromFolderIdApi() {
        $folder = Folder::factory()->create();
        $response = $this->get('api/folder/'.$folder->id);
        $response->assertOk();
    }

    public function testUploadFiftyAttachmentApi() {
        $folder = Folder::factory()->create();
        for ($i = 1; $i <= 50; $i++ )
        {
            $attachment = [
                'name' => 'attachment_'.$i,
                'file' => UploadedFile::fake()->create('PdfFile', 4000, 'pdf')
            ];
            $response = $this->post('api/folder/'.$folder->id, $attachment);
            $response->assertCreated();
        }
    }

    public function testUploadPdfMoreThan5Mb() {
        $folder = Folder::factory()->create();
        $attachment = [
            'name' => 'attachment_5MB',
            'file' => UploadedFile::fake()->create('PdfFile', 5500, 'pdf')
        ];
        $response = $this->post('api/folder/'.$folder->id, $attachment);
        $response->assertStatus(422);
    }

    public function testCreateFolderAndUpload()
    {
        $folder = Folder::factory()->create();
        $response = $this->get('api/folder');
        $response->assertOk();

        $attachment = [
            'name' => 'attachment',
            'file' => UploadedFile::fake()->create('PdfFile', 4000, 'pdf')
        ];

        // upload api
        $response = $this->post('api/folder/'.$folder->id, $attachment);
        $response->assertCreated();

        // check folder api
        $response = $this->get('api/folder');
        $response->assertOk();
    }
}
