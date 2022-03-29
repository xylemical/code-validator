<?php

namespace Xylemical\Code\Validator;

use Xylemical\Code\DefinitionInterface;

/**
 * Provides validation using multiple validators.
 */
class Validator implements ValidatorInterface {

  /**
   * The validators.
   *
   * @var \Xylemical\Code\Validator\ValidatorInterface[]
   */
  protected array $validators = [];

  /**
   * Validator constructor.
   *
   * @param array $validators
   *   The validators.
   */
  public function __construct(array $validators) {
    foreach ($validators as $validator) {
      $this->addValidator($validator);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function applies(DefinitionInterface $definition): bool {
    foreach ($this->validators as $validator) {
      if ($validator->applies($definition)) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function validate(DefinitionInterface $definition): static {
    foreach ($this->validators as $validator) {
      if ($validator->applies($definition)) {
        $validator->validate($definition);
      }
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function hasErrors(): bool {
    foreach ($this->validators as $validator) {
      if ($validator->hasErrors()) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getErrors(): array {
    $errors = [];
    foreach ($this->validators as $validator) {
      if ($validator->hasErrors()) {
        $errors = array_merge($errors, $validator->getErrors());
      }
    }
    return $errors;
  }

  /**
   * Reset the errors for the validator.
   *
   * @return $this
   */
  public function resetErrors(): static {
    foreach ($this->validators as $validator) {
      $validator->resetErrors();
    }
    return $this;
  }

  /**
   * Check there is a validator in the validator list.
   *
   * @param \Xylemical\Code\Validator\ValidatorInterface $validator
   *   The validator.
   *
   * @return bool
   *   The result.
   */
  public function hasValidator(ValidatorInterface $validator): bool {
    foreach ($this->validators as $item) {
      if ($item === $validator) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * Check there is an instance of a validator in the validator list.
   *
   * @param string $instanceof
   *   The instanceof class name.
   *
   * @return bool
   *   The result.
   */
  public function hasInstance(string $instanceof): bool {
    foreach ($this->validators as $item) {
      if ($item instanceof $instanceof) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * Get the validators.
   *
   * @return \Xylemical\Code\Validator\ValidatorInterface[]
   *   The validators.
   */
  public function getValidators(): array {
    return $this->validators;
  }

  /**
   * Add a validator to the validators.
   *
   * @param \Xylemical\Code\Validator\ValidatorInterface $validator
   *   The validator.
   *
   * @return $this
   */
  public function addValidator(ValidatorInterface $validator): static {
    if (!in_array($validator, $this->validators)) {
      $this->validators[] = $validator;
    }
    return $this;
  }

  /**
   * Add multiple validators to the list.
   *
   * @param \Xylemical\Code\Validator\Validator[] $validators
   *   The validators.
   *
   * @return $this
   */
  public function addValidators(array $validators): static {
    foreach ($validators as $validator) {
      $this->addValidator($validator);
    }
    return $this;
  }

  /**
   * Remove the validator.
   *
   * @param \Xylemical\Code\Validator\ValidatorInterface $validator
   *   The validator.
   *
   * @return $this
   */
  public function removeValidator(ValidatorInterface $validator): static {
    $this->validators = array_filter($this->validators, function ($item) use ($validator) {
      return $item !== $validator;
    });
    return $this;
  }

  /**
   * Remove validators by an instanceof check.
   *
   * @param string $instanceof
   *   The instanceof class/interface.
   *
   * @return $this
   */
  public function removeInstance(string $instanceof): static {
    $this->validators = array_filter($this->validators, function ($item) use ($instanceof) {
      return !($item instanceof $instanceof);
    });
    return $this;
  }

}
