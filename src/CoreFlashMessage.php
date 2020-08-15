<?php
declare(strict_types=1);

namespace Plaisio\FlashMessage;

use Plaisio\Helper\Html;
use Plaisio\Helper\HtmlElement;

/**
 * Class for flash messages.
 */
class CoreFlashMessage extends HtmlElement implements FlashMessage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If true and only true this flash message must be dismissed automatically.
   *
   * @var bool
   */
  protected $autoDismiss = false;

  /**
   * The payload of the flash message.
   *
   * @var string
   */
  protected $message;

  /**
   * If true and only true this flash message is shown isOnce and removed automatically from the list of flash
   * messages.
   *
   * @var bool
   */
  protected $once = true;

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

    $this->addClass('flash-message');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code of the flash message.
   *
   * @return string
   */
  public function getHtml(): string
  {
    $this->setAttrData('auto-dismiss', $this->autoDismiss ? '1' : null);

    $html = Html::generateTag('div', $this->attributes);
    $html .= $this->message;
    $html .= '<button type="button" class="close">&times;</button>';
    $html .= '</div>';

    return $html;
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
   * @param bool $autoDismiss If true if and only if this flash messages must dismissed automatically.
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
   * Sets whether this flash message must be shown once only.
   *
   * @param bool $once If true and only true this flash message is shown isOnce and removed automatically from the
   *                   list of flash messages.
   *
   * @return CoreFlashMessage
   */
  public function setOnce(bool $once): CoreFlashMessage
  {
    $this->once = $once;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
