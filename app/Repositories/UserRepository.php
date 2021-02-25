<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use SocialiteProviders\Manager\OAuth2\User as OAuth2User;
use Way2Web\Force\Repository\AbstractRepository;

class UserRepository extends AbstractRepository
{
    public function model(): string
    {
        return User::class;
    }

    public function fromSocialite(OAuth2User $user): ?User
    {
        return $this->makeQuery()
            ->where('email', $user->getEmail())
            ->first();
    }
}
