<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Trait;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Contract\HasOptionsInterface;
use Syndesi\CypherDataStructures\Trait\OptionsTrait;

class OptionsTraitTest extends TestCase
{
    private function getTrait(): HasOptionsInterface
    {
        return new class() implements HasOptionsInterface {
            use OptionsTrait;
        };
    }

    public function testOptions(): void
    {
        $trait = $this->getTrait();
        $trait->addOption('someOption', 'some value');
        $this->assertCount(1, $trait->getOptions());
        $this->assertTrue($trait->hasOption('someOption'));
        $this->assertFalse($trait->hasOption('notExistingOption'));
        $this->assertSame('some value', $trait->getOption('someOption'));

        $trait->addOptions([
            'otherOption' => 'other value',
            'anotherOption' => 'another value',
        ]);
        $this->assertCount(3, $trait->getOptions());
        $trait->removeOption('otherOption');
        $this->assertCount(2, $trait->getOptions());
        $trait->clearOptions();
        $this->assertCount(0, $trait->getOptions());
    }
}
