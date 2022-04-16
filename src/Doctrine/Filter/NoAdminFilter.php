<?php

namespace App\Doctrine\Filter;

use App\Entity\User;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class NoAdminFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if ($targetEntity->getReflectionClass()->name != User::class) {
            return '';
        }

        return "CAST({$targetTableAlias}.roles as text) NOT LIKE '%ROLE_ADMIN%'";
    }
}
