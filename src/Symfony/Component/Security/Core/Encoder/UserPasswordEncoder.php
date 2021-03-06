<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Security\Core\Encoder;

trigger_deprecation('symfony/security-core', '5.3', sprintf('The "%s" class is deprecated, use "%s" instead.', UserPasswordEncoder::class, UserPasswordHasher::class));

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

/**
 * A generic password encoder.
 *
 * @author Ariel Ferrandini <arielferrandini@gmail.com>
 *
 * @deprecated since Symfony 5.3, use {@link UserPasswordHasher} instead
 */
class UserPasswordEncoder implements UserPasswordEncoderInterface
{
    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function encodePassword(UserInterface $user, string $plainPassword)
    {
        $encoder = $this->encoderFactory->getEncoder($user);

        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }

    /**
     * {@inheritdoc}
     */
    public function isPasswordValid(UserInterface $user, string $raw)
    {
        if (null === $user->getPassword()) {
            return false;
        }

        $encoder = $this->encoderFactory->getEncoder($user);

        return $encoder->isPasswordValid($user->getPassword(), $raw, $user->getSalt());
    }

    /**
     * {@inheritdoc}
     */
    public function needsRehash(UserInterface $user): bool
    {
        if (null === $user->getPassword()) {
            return false;
        }

        $encoder = $this->encoderFactory->getEncoder($user);

        return $encoder->needsRehash($user->getPassword());
    }
}
