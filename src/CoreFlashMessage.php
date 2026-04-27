<?php
declare(strict_types=1);

namespace Plaisio\FlashMessage;

use Plaisio\Helper\Html;
use Plaisio\Helper\HtmlElement;
use Plaisio\Helper\RenderWalker;

/**
 * Class for flash messages.
 */
class CoreFlashMessage implements FlashMessage
{
  //--------------------------------------------------------------------------------------------------------------------
  use HtmlElement;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Whether this flash message is automatically dismissed.
   *
   * @var bool
   */
  protected bool $autoDismiss;

  /**
   * The payload of the flash message.
   *
   * @var string
   */
  protected string $message;

  /**
   *Whether this flash message is a persistent flash message.
   *
   * @var bool
   */
  protected bool $persistent;

  /**
   * The first weight for sorting.
   *
   * @var int
   */
  protected int $weight1;

  /**
   * The second weight for sorting.
   *
   * @var int
   */
  protected int $weight2;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string $message     The payload of the flash message.
   * @param bool   $html        Whether the message is an HTML snippet or plain text. In plain text special characters
   *                            in the inner text will be replaced with HTML entities.
   * @param bool   $autoDismiss Whether the flash message is automatically dismissed.
   * @param bool   $persistent  Whether the flash message is a persistent flash message.
   *
   */
  public function __construct(string $message, bool $html, bool $autoDismiss, bool $persistent)
  {
    $this->message     = ($html) ? $message : Html::txt2Html($message);
    $this->autoDismiss = $autoDismiss;
    $this->persistent  = $persistent;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function getWeight1(): int
  {
    return $this->weight1;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function getWeight2(): int
  {
    return $this->weight2;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function htmlFlashMessage(): string
  {
    $walker = new RenderWalker('flash-message');
    $this->setAttrData('auto-dismiss', ($this->autoDismiss) ? '1' : null)
         ->setAttrData('persistent', ($this->persistent) ? '1' : null)
         ->addClasses($walker->getClasses('wrapper'));

    $struct = ['tag'   => 'div',
               'attr'  => $this->attributes,
               'inner' => [['html' => $this->message],
                           ['tag'  => 'button',
                            'attr' => ['class' => $walker->getClasses('close'),
                                       'type'  => 'button'],
                            'html' => '&times;']]];

    return Html::htmlNested($struct);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function isAutoDismiss(): bool
  {
    return $this->autoDismiss;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function isPersistent(): bool
  {
    return $this->persistent;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function setAutoDismiss(bool $autoDismiss): self
  {
    $this->autoDismiss = $autoDismiss;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function setPersistent(bool $persistent): self
  {
    $this->persistent = $persistent;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function setWeight1(int $weight1): self
  {
    $this->weight1 = $weight1;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function setWeight2(int $weight2): self
  {
    $this->weight2 = $weight2;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
