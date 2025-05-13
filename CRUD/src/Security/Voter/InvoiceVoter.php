<?php

namespace App\Security\Voter;

use App\Entity\Invoice;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class InvoiceVoter extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])
            && $subject instanceof Invoice;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Invoice $invoice */
        $invoice = $subject;

        return match($attribute) {
            self::VIEW, self::EDIT, self::DELETE => $this->canAccess($invoice, $user),
            default => false,
        };
    }

    private function canAccess(Invoice $invoice, UserInterface $user): bool
    {
        if (!$user instanceof User) {
            return false;
        }

        // Les administrateurs peuvent tout faire
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        // Un utilisateur normal ne peut accéder qu'aux factures de ses clients
        return $invoice->getClient()->getUser() === $user;
    }
} 