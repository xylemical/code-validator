<?php

namespace Xylemical\Code\Validator;

use Xylemical\Code\DefinitionInterface;

/**
 * Provides validators with error handling.
 */
trait ErrorTrait {

  /**
   * The errors.
   *
   * @var \Xylemical\Code\Validator\Error[]
   */
  protected array $errors = [];

  /**
   * {@inheritdoc}
   */
  public function hasErrors(): bool {
    return count($this->errors) > 0;
  }

  /**
   * {@inheritdoc}
   */
  public function getErrors(): array {
    return $this->errors;
  }

  /**
   * Add an error to the validation.
   *
   * @param \Xylemical\Code\DefinitionInterface $definition
   *   The definition.
   * @param string $message
   *   The message.
   *
   * @return $this
   */
  protected function addError(DefinitionInterface $definition, string $message): static {
    $this->errors[] = new Error($definition, $message);
    return $this;
  }

}
