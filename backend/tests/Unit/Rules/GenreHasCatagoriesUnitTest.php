<?php

namespace Tests\Unit\Rules;

use App\Rules\GenreHasCategoriesRule;
use Mockery\MockInterface;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenreHasCatagoriesUnitTest extends TestCase
{
   public function testCategoriesIdField()
   {
       $rule = new GenreHasCategoriesRule(
           [1,1,2,2]
       );

       $reflectionClass = new \ReflectionClass(GenreHasCategoriesRule::class);
       $reflectionProperty  = $reflectionClass->getProperty('categoriesId');
       $reflectionProperty->setAccessible(true);

       $categoriesId = $reflectionProperty->getValue($rule);
       $this->assertEqualsCanonicalizing([1,2], $categoriesId);
   }
    public function testGenresIdField()
    {
        $rule = $this->createRuleMock([]);

        $rule->shouldReceive('getRow')
            ->withAnyArgs()
            ->andReturnNull();

        $rule->passes('', [1,1,2,2]);

        $reflectionClass = new \ReflectionClass(GenreHasCategoriesRule::class);
        $reflectionProperty  = $reflectionClass->getProperty('genresId');
        $reflectionProperty->setAccessible(true);

        $genresId = $reflectionProperty->getValue($rule);
        $this->assertEqualsCanonicalizing([1,2], $genresId);
    }

    public function testPassesReturnsFalseWhenCategoriesOrGenresIsArrayEmpty()
    {
        $rule = $this->createRuleMock([1]);
        $this->assertFalse($rule->passes('', []));

        $rule = $this->createRuleMock([]);
        $this->assertFalse($rule->passes('', [1]));
    }

    public function testPassesReturnsFalseWhenGetRowsIsEmpty()
    {
        $rule = $this->createRuleMock([]);
        $rule->shouldReceive('getRow')
            ->withAnyArgs()
            ->andReturn(collect());

        $this->assertFalse($rule->passes('', [1]));
    }

    public function testPassesReturnsFalseWhenHasCategoriesWithoutGenres()
    {
        $rule = $this->createRuleMock([1,2]);

        $rule->shouldReceive('getRow')
            ->withAnyArgs()
            ->andReturn(collect(['category_id' => 1]));

        $this->assertFalse($rule->passes('', [1]));
    }

    protected function createRuleMock( array $categoriesId) : MockInterface
    {
        return \Mockery::mock(GenreHasCategoriesRule::class, [$categoriesId] )
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
    }
}
