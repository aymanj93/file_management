<?php

namespace Tests\Feature;

use App\Models\Attachment;
use App\Models\Folder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
            $attachment = Attachment::factory()->create();
            $response = $this->post('api/folder/'.$folder->id, $attachment->toArray());
            $response->assertCreated();
        }
    }

}
