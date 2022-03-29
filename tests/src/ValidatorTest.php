<?php

namespace Xylemical\Code\Validator;

use PHPUnit\Framework\TestCase;
use Xylemical\Code\DefinitionInterface;
use Xylemical\Code\Documentation;

class ValidatorTest extends TestCase {

  public function testValidator() {
    $defA = new Documentation();
    $defB = new Documentation();

    $testA = new TestValidator();
    $testB = new TestValidator($defA);

    $validator = new Validator([$testB]);
    $this->assertTrue($validator->applies($defA));
    $this->assertFalse($validator->applies($defB));
    $this->assertTrue($validator->hasInstance(TestValidator::class));
    $this->assertTrue($validator->hasValidator($testB));

    $validator = new Validator([]);
    $this->assertFalse($validator->applies($defA));
    $this->assertFalse($validator->hasInstance(TestValidator::class));
    $this->assertFalse($validator->hasValidator($testA));

    $validator->addValidator($testA);
    $this->assertEquals([$testA], $validator->getValidators());
    $this->assertTrue($validator->hasInstance(TestValidator::class));
    $this->assertFalse($validator->hasInstance(Validator::class));
    $this->assertTrue($validator->hasValidator($testA));
    $this->assertFalse($validator->hasValidator($testB));

    $validator->removeInstance(Validator::class);
    $this->assertEquals([$testA], $validator->getValidators());
    $this->assertTrue($validator->hasInstance(TestValidator::class));
    $this->assertTrue($validator->hasValidator($testA));

    $validator->removeInstance(TestValidator::class);
    $this->assertEquals([], $validator->getValidators());
    $this->assertFalse($validator->hasInstance(TestValidator::class));
    $this->assertFalse($validator->hasValidator($testA));

    $validator->addValidator($testA);
    $validator->removeValidator($testB);
    $this->assertTrue($validator->hasInstance(TestValidator::class));
    $this->assertTrue($validator->hasValidator($testA));

    $validator->removeValidator($testA);
    $this->assertFalse($validator->hasInstance(TestValidator::class));
    $this->assertFalse($validator->hasValidator($testA));

    $validator->addValidators([$testA, $testB]);
    $this->assertEquals([$testA, $testB], $validator->getValidators());

    $validator->validate($defA);
    $validator->validate($defB);

    $this->assertTrue(in_array($defA, $testA->validated, TRUE));
    $this->assertTrue(in_array($defB, $testA->validated, TRUE));
    $this->assertTrue(in_array($defA, $testB->validated, TRUE));
    $this->assertFalse(in_array($defB, $testB->validated, TRUE));

    $this->assertEquals([], $validator->getErrors());
    $this->assertFalse($validator->hasErrors());

    $defC = new Documentation('Not empty');
    $validator->validate($defC);

    $errors = $validator->getErrors();
    $this->assertNotEquals([], $errors);
    $this->assertTrue($validator->hasErrors());

    $this->assertEquals($defC, $errors[0]->getDefinition());
    $this->assertEquals('Definition not empty', $errors[0]->getMessage());

    $validator->resetErrors();
    $this->assertEquals([], $validator->getErrors());
    $this->assertFalse($validator->hasErrors());
  }

}

class TestValidator implements ValidatorInterface {

  use ErrorTrait;

  public ?DefinitionInterface $target = NULL;

  public array $validated = [];

  public function __construct($target = NULL) {
    $this->target = $target;
  }

  public function applies(DefinitionInterface $definition): bool {
    return !$this->target || ($this->target === $definition);
  }

  public function validate(DefinitionInterface $definition): static {
    $this->validated[] = $definition;
    if (!$definition->isEmpty()) {
      $this->addError($definition, 'Definition not empty');
    }
    return $this;
  }

}
