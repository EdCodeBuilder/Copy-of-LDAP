<?php

namespace App\Traits;

use App\Helpers\BuilderWithPaginationHavingSupport;

trait PaginationWithHavings
{
    protected function newBaseQueryBuilder()
    {
        $connection = $this->getConnection();

        return new BuilderWithPaginationHavingSupport(
            $connection,
            $connection->getQueryGrammar(),
            $connection->getPostProcessor()
        );
    }
}
