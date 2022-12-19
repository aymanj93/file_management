<?php

namespace Tests\Feature;

 use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Folder;
use Tests\TestCase;

class FolderApiTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateFolderWithoutNameApi(){
        $folder = Folder::create([
            'name' => ''
        ]);
        $response = $this->post('api/folder', $folder->toArray());
        $response->assertInvalid();
    }

    public function testGetTenThousandFolderApi(){
        Folder::factory()->count(10000)->create();
        $response = $this->get('api/folder');
        $response->assertOk();
    }

    public function testCreateFiftyFolderApi() {
        for ($i = 1; $i <= 50; $i++ )
        {
            $response = $this->post('api/folder', ['name' => 'folder_'.$i]);
            $response->assertCreated();
        }
    }

    public function testCreateFiftySubFolderApi() {
        $root = Folder::factory()->create();
        for ($i = 1; $i <= 50; $i++ )
        {
            $response = $this->post('api/folder/'.$root->id.'/createFolder', ['name' => 'subFolder_'.$i]);
            $response->assertCreated();
        }

        $response = $this->get('api/folder');
        $response->assertOk();
    }


}
