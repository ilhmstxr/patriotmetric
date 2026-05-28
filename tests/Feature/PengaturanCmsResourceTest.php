<?php

namespace Tests\Feature;

use App\Models\PengaturanCms;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PengaturanCmsResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(database_path('migrations'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('pengaturan_cms')) {
            Schema::create('pengaturan_cms', function ($table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }
    }

    public function test_can_create_via_service(): void
    {
        $service = app(\App\Services\PengaturanCmsService::class);
        $dto = new \App\DTO\PengaturanCmsDTO(['key' => 'test_create', 'value' => '<p>Created</p>']);
        $record = $service->store($dto);

        $this->assertDatabaseHas('pengaturan_cms', [
            'key' => 'test_create',
            'value' => '<p>Created</p>',
        ]);
    }

    public function test_can_update_via_service(): void
    {
        $record = PengaturanCms::create([
            'key' => 'existing_key',
            'value' => '<p>Old value</p>',
        ]);

        $service = app(\App\Services\PengaturanCmsService::class);
        $dto = new \App\DTO\PengaturanCmsDTO(['key' => 'existing_key', 'value' => '<p>Updated value</p>']);
        $result = $service->update($record->id, $dto);

        $this->assertTrue($result);
        $this->assertDatabaseHas('pengaturan_cms', [
            'id' => $record->id,
            'key' => 'existing_key',
            'value' => '<p>Updated value</p>',
        ]);
    }

    public function test_update_preserves_key_when_only_value_changes(): void
    {
        $record = PengaturanCms::create([
            'key' => 'preserve_key',
            'value' => '<p>Original</p>',
        ]);

        $service = app(\App\Services\PengaturanCmsService::class);
        $dto = new \App\DTO\PengaturanCmsDTO(['key' => 'preserve_key', 'value' => '<p>New content</p>']);
        $service->update($record->id, $dto);

        $record->refresh();
        $this->assertEquals('preserve_key', $record->key);
        $this->assertEquals('<p>New content</p>', $record->value);
    }

    public function test_repository_find_returns_correct_record(): void
    {
        $record = PengaturanCms::create([
            'key' => 'find_test',
            'value' => '<p>Find me</p>',
        ]);

        $repo = app(\App\Repositories\PengaturanCmsRepository::class);
        $found = $repo->getByKey('find_test');

        $this->assertNotNull($found);
        $this->assertEquals($record->id, $found->id);
        $this->assertEquals('<p>Find me</p>', $found->value);
    }
}
