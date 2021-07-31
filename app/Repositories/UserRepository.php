<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Article;
use App\Models\Party;
use App\Models\Person;
use App\Models\Product;
use App\Models\Project;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use SocialiteProviders\Manager\OAuth2\User as OAuth2User;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Way2Web\Force\Repository\AbstractRepository;

class UserRepository extends AbstractRepository
{
    public function model(): string
    {
        return User::class;
    }

    public function index(): Paginator
    {
        return QueryBuilder::for($this->makeQuery())
            ->defaultSort('id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('email'),
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('email'),
            ])
            ->jsonPaginate();
    }

    public function show(string $id): User
    {
        /** @var User */
        $user = $this->with(['roles'])->findOrFail($id);

        return $user;
    }

    public function updateFull(string $id, array $data): User
    {
        /** @var User */
        $user = $this->findOrFail($id);

        $user->update($data);

        return $user;
    }

    public function syncRoles(string $id, array $data): User
    {
        /** @var User */
        $user = $this->findOrFail($id);

        $user->syncRoles($data);

        return $user;
    }

    public function deleteFull(string $id): User
    {
        /** @var User */
        $user = $this->findOrFail($id);

        $user->delete();

        return $user;
    }

    public function fromSocialite(OAuth2User $user): ?User
    {
        return $this->makeQuery()
            ->where('email', $user->getEmail())
            ->first();
    }

    /** @param mixed $userId */
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
            case Article::class:
                $user->likedArticles()->toggle($likableId);
                break;
        }

        return $user;
    }
}
