<?php

declare(strict_types=1);

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\Log;

final class Test1Directive extends BaseDirective implements FieldMiddleware
{
    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<'GRAPHQL'
"""
A simple directive to log the field name where it is applied.
"""
directive @test1 on FIELD_DEFINITION | INPUT_FIELD_DEFINITION
GRAPHQL;
    }

    public function handleField(FieldValue $fieldValue): void
    {
        $fieldValue->wrapResolver(
            fn(callable $resolver) => function ($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) use ($resolver) {
//               Log::info("Custom directive @hassan executed on field:", [
//                   'fieldName' => $resolveInfo->fieldName,
//               ]);

                return $resolver($root, $args, $context, $resolveInfo);
            }
        );
    }
}
