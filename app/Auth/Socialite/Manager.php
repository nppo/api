<?php

declare(strict_types=1);

namespace App\Auth\Socialite;

use App\Models\User;
use App\Repositories\UserRepository;
use SocialiteProviders\Manager\OAuth2\User as OAuthUser;

class Manager
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function user(OAuthUser $oauthUser): User
    {
        $user = $this->userRepository->fromSocialite($oauthUser);

        if ($user) {
            return $user;
        }

        /** @var User */
        $user = $this->userRepository->create($this->attributes($oauthUser));

        return $user;
    }

    protected function attributes(OAuthUser $user): array
    {
        return [
            'email' => $user->getEmail(),
        ];
    }
}
