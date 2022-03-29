<?php

namespace Xylemical\Code\Validator;

use Xylemical\Code\DefinitionInterface;

/**
 * A code validation error.
 */
class Error {

  /**
   * The definition causing the error.
   *
   * @var \Xylemical\Code\DefinitionInterface
   */
  protected DefinitionInterface $definition;

  /**
   * The error message.
   *
   * @var string
   */
  protected string $message;

  /**
   * Error constructor.
   *
   * @param \Xylemical\Code\DefinitionInterface $definition
   *   The definition.
   * @param string $message
   *   The message.
   */
  public function __construct(DefinitionInterface $definition, string $message) {
    $this->definition = $definition;
    $this->message = $message;
  }

  /**
   * Get the definition that caused the error.
   *
   * @return \Xylemical\Code\DefinitionInterface
   *   The definition.
   */
  public function getDefinition(): DefinitionInterface {
    return $this->definition;
  }

  /**
   * Get the error message.
   *
   * @return string
   *   The message.
   */
  public function getMessage(): string {
    return $this->message;
  }

}
