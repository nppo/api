<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Party;
use App\Models\Person;
use App\Models\Product;
use App\Models\Project;
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

    public function addLike($userId, string $likableType, string $likableId): User
    {
        /** @var User $user */
        $user = $this->findOrFail($userId);

        switch ($likableType) {
            case Product::class:
                $user->likedProducts()->toggle($likableId);
                break;
            case Project::class:
                $user->likedProjects()->toggle($likableId);
                break;
            case Party::class:
                $user->likedParties()->toggle($likableId);
                break;
            case Person::class:
                $user->likedPeople()->toggle($likableId);
                break;
        }

        return $user;
    }
}
