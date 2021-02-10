<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthPersonalAccessClientsTable extends Migration
{
    /** @var \Illuminate\Database\Schema\Builder */
    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection($this->getConnection());
    }

    public function up(): void
    {
        $this->schema->create('oauth_personal_access_clients', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->uuid('client_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $this->schema->dropIfExists('oauth_personal_access_clients');
    }

    public function getConnection(): ?string
    {
        return config('passport.storage.database.connection');
    }
}
