<?php

namespace Tests\Feature;

 use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Folder;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateFolderWithoutName(){
        $folder = Folder::create([
            'name' => ''
        ]);
        $response = $this->post('api/folder', $folder->toArray());
        $response->assertInvalid();
    }

    public function testCreateTenThousandFolder(){
        Folder::factory()->count(10000)->create();
        $response = $this->get('api/folder');
        $response->assertOk();
    }

    public function testCreateFiftyFolderApi() {
        for ($i = 1; $i <= 50; $i++ )
        {
            $folder = Folder::factory()->create();
            $response = $this->post('api/folder', $folder->toArray());
            $response->assertCreated();
        }
    }

    public function testCreateFiftySubFolderApi() {
        $root = Folder::factory()->create();
        for ($i = 1; $i <= 50; $i++ )
        {
            $folder = Folder::factory()->create();
            $response = $this->post('api/folder/'.$root->id.'/createFolder', $folder->toArray());
            $response->assertCreated();
        }
    }


}
