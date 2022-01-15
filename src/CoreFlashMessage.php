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
   * Whether this flash message must be dismissed automatically.
   *
   * @var bool
   */
  protected bool $autoDismiss = false;

  /**
   * The payload of the flash message.
   *
   * @var string
   */
  protected string $message;

  /**
   * Whether this flash message is shown once and removed automatically from the list of flash messages.
   *
   * @var bool
   */
  protected bool $once = true;

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
   * @param string $message The payload of the flash message.
   * @param bool   $isHtml  If set the message is a HTML snippet, otherwise special characters in the inner text will be
   *                        replaced with HTML entities.
   */
  public function __construct(string $message, bool $isHtml = false)
  {
    $this->message = ($isHtml) ? $message : Html::txt2Html($message);
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
    $this->setAttrData('auto-dismiss', ($this->autoDismiss) ? '1' : null);
    $this->addClasses($walker->getClasses('wrapper'));

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
   * Returns true if and only if this flash message must be dismissed automatically.
   *
   * @return bool
   */
  public function isAutoDismiss(): bool
  {
    return $this->autoDismiss;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function isOnce(): bool
  {
    return $this->once;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets whether this flash message must be dismissed automatically.
   *
   * @param bool $autoDismiss Whether this flash message must be dismissed automatically.
   *
   * @return CoreFlashMessage
   */
  public function setAutoDismiss(bool $autoDismiss): CoreFlashMessage
  {
    $this->autoDismiss = $autoDismiss;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets whether this flash message is shown once and removed automatically from the list of flash messages.
   *
   * @param bool $once Whether this flash message is shown once and removed automatically from the list of flash
   *                   messages.
   *
   * @return CoreFlashMessage
   */
  public function setOnce(bool $once): CoreFlashMessage
  {
    $this->once = $once;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------/**
  /**
   * @inheritDoc
   */
  public function setWeight1(int $weight1)
  {
    $this->weight1 = $weight1;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function setWeight2(int $weight2)
  {
    $this->weight2 = $weight2;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
