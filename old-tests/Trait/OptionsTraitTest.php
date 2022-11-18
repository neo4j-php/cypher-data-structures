<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Trait;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Contract\HasOptionsInterface;
use Syndesi\CypherDataStructures\Trait\OptionsTrait;
use Syndesi\CypherDataStructures\Type\OptionName;
use Syndesi\CypherDataStructures\Type\OptionStorage;

class OptionsTraitTest extends TestCase
{
    private function getTrait(): HasOptionsInterface
    {
        return new class() implements HasOptionsInterface {
            use OptionsTrait;

            public function __construct()
            {
                $this->initOptionsTrait();
            }
        };
    }

    public function testOptions(): void
    {
        $trait = $this->getTrait();
        $trait->addOption(new OptionName('someOption'), 'some value');
        $this->assertSame(1, $trait->getOptions()->count());
        $this->assertTrue($trait->hasOption(new OptionName('someOption')));
        $this->assertFalse($trait->hasOption(new OptionName('notExistingOption')));
        $this->assertSame('some value', $trait->getOption(new OptionName('someOption')));

        $optionStorage = new OptionStorage();
        $optionStorage->attach(new OptionName('otherOption'), 'other value');
        $optionStorage->attach(new OptionName('anotherOption'), 'another value');

        $trait->addOptions($optionStorage);
        $this->assertSame(3, $trait->getOptions()->count());
        $trait->removeOption(new OptionName('otherOption'));
        $this->assertSame(2, $trait->getOptions()->count());
        $trait->clearOptions();
        $this->assertSame(0, $trait->getOptions()->count());
    }
}
