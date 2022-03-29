<?php

namespace Xylemical\Code\Validator;

use Xylemical\Code\DefinitionInterface;

/**
 * Provide validation for code definitions.
 */
interface ValidatorInterface {

  /**
   * Check the validator applies to the definition.
   *
   * @param \Xylemical\Code\DefinitionInterface $definition
   *   The definition.
   *
   * @return bool
   *   The result.
   */
  public function applies(DefinitionInterface $definition): bool;

  /**
   * Performs validation on the definition.
   *
   * @param \Xylemical\Code\DefinitionInterface $definition
   *   The definition.
   *
   * @return $this
   */
  public function validate(DefinitionInterface $definition): static;

  /**
   * Check if there were errors during validation.
   *
   * @return bool
   *   The result.
   */
  public function hasErrors(): bool;

  /**
   * Get the errors during validation.
   *
   * @return \Xylemical\Code\Validator\Error[]
   *   Get the errors.
   */
  public function getErrors(): array;

  /**
   * Reset the errors.
   *
   * @return $this
   */
  public function resetErrors(): static;

}
