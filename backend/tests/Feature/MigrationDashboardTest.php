<?php

namespace Tests\Feature;

use App\Models\StaffUser;
use App\Services\MigrationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

final class MigrationDashboardTest extends TestCase
{
    use RefreshDatabase;

    private const LIST_URL = '/api/admin/migrations';
    private const RUN_URL = '/api/admin/migrations/run';
    private const PASSWORD = 'Synthetic-dashboard-password';
    private const FIXTURE = '2030_01_01_000000_create_dashboard_migration_fixture';

    protected function setUp(): void
    {
        parent::setUp();
        config(['phelon.migration_admins' => ['fixture-admin@example.test']]);
    }

    public function test_only_a_current_session_for_an_allowlisted_account_has_migration_access(): void
    {
        $this->getJson(self::LIST_URL)->assertUnauthorized();
        $this->postJson(self::RUN_URL)->assertUnauthorized();

        $this->signIn($this->staff('ordinary@example.test'));
        $this->getJson('/api/session')->assertOk()->assertJsonPath('user.canManageMigrations', false);
        $this->getJson(self::LIST_URL)->assertForbidden();
        $this->postJson(self::RUN_URL, ['password' => self::PASSWORD])->assertForbidden();

        $admin = $this->staff('fixture-admin@example.test');
        $this->signIn($admin);
        $this->withSession(['staff_signed_at' => time() - 28801])->getJson(self::LIST_URL)->assertUnauthorized();
        $admin->update(['active' => false]);
        $this->signIn($admin);
        $this->postJson(self::RUN_URL)->assertUnauthorized();
    }

    public function test_list_shows_applied_migrations_from_repository_and_no_op_is_safe(): void
    {
        $this->signIn($this->staff('fixture-admin@example.test'));
        $this->getJson('/api/session')->assertOk()->assertJsonPath('user.canManageMigrations', true);
        $this->getJson(self::LIST_URL)->assertOk()->assertJsonPath('hasHistory', true)
            ->assertJsonFragment(['name' => '2026_11_27_174935_create_personal_access_tokens_table', 'batch' => 1, 'status' => 'ran']);
        $this->postJson(self::RUN_URL, ['password' => self::PASSWORD])->assertOk()->assertJsonPath('applied', []);
    }

    public function test_admin_can_apply_pending_repository_files_only_after_password_confirmation(): void
    {
        $this->app->useDatabasePath(base_path('tests/Fixtures/migration-dashboard'));
        $this->signIn($this->staff('fixture-admin@example.test'));
        $this->getJson(self::LIST_URL)->assertOk()->assertJsonPath('migrations.0', [
            'name' => self::FIXTURE, 'batch' => null, 'status' => 'pending',
        ]);
        $this->postJson(self::RUN_URL)->assertUnprocessable()->assertJsonValidationErrors('password');
        $this->postJson(self::RUN_URL, ['password' => 'incorrect'])->assertUnprocessable()->assertJsonValidationErrors('password');
        $this->assertFalse(Schema::hasTable('dashboard_migration_fixture'));

        $lock = Cache::lock(MigrationService::LOCK, 60);
        $this->assertTrue($lock->get());
        try {
            $this->postJson(self::RUN_URL, ['password' => self::PASSWORD])->assertConflict();
            $this->assertFalse(Schema::hasTable('dashboard_migration_fixture'));
        } finally {
            $lock->release();
        }

        $this->postJson(self::RUN_URL, ['password' => self::PASSWORD, 'path' => 'outside-repository', 'database' => 'missing'])
            ->assertOk()->assertJsonPath('applied', [self::FIXTURE])
            ->assertJsonPath('migrations.0.status', 'ran')->assertJsonPath('migrations.0.batch', 2);
        $this->assertTrue(Schema::hasTable('dashboard_migration_fixture'));
        $this->assertDatabaseHas('migrations', ['migration' => self::FIXTURE, 'batch' => 2]);
        $this->postJson(self::RUN_URL, ['password' => self::PASSWORD])->assertOk()->assertJsonPath('applied', []);
    }

    private function staff(string $email): StaffUser
    {
        return StaffUser::create(['email' => $email, 'display_name' => 'Fixture Admin', 'active' => true,
            'password_hash' => Hash::make(self::PASSWORD)]);
    }

    private function signIn(StaffUser $staff): void
    {
        $this->actingAs($staff)->withSession(['staff_signed_at' => time()]);
    }
}
