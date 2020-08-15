<?php
declare(strict_types=1);

namespace Plaisio\FlashMessage;

use Plaisio\PlaisioInterface;
use Plaisio\PlaisioObject;
use Plaisio\Session\Session;
use SetBased\Exception\LogicException;

/**
 *
 */
class CoreFlashMessageManager extends PlaisioObject implements FlashMessageManager
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The flash messages.
   *
   * @var FlashMessage[]|null
   */
  private $flashMessages;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param PlaisioInterface $object The parent PhpPlaisio object.
   */
  public function __construct(PlaisioInterface $object)
  {
    parent::__construct($object);

    $this->flashMessages = &$this->nub->session->getNamedSection(static::class, Session::SECTION_EXCLUSIVE);
    if ($this->flashMessages===null)
    {
      $this->flashMessages = [];
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function addAssets(): void
  {
    $this->nub->assets->jsAdmFunctionCall(__CLASS__, 'registerFlashMessage', ['.flash-message']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function addFlashMessage(FlashMessage $flashMessage): FlashMessageManager
  {
    $this->flashMessages[] = $flashMessage;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function create(string $type, string $message, bool $isHtml = false): FlashMessage
  {
    if (in_array($type, ['success', 'info', 'warning', 'error']))
    {
      $flashMessage = new CoreFlashMessage($message, $isHtml);
      $flashMessage->addClass('flash-message-'.$type)
                   ->setAutoDismiss($type==='success');
    }
    elseif (is_a($type, FlashMessage::class))
    {
      $flashMessage = new $type($message, $isHtml);
    }
    else
    {
      throw new LogicException("Type '%s' is not a flash message", $type);
    }

    $this->nub->session->setHasFlashMessage(true);

    $this->flashMessages[] = $flashMessage;

    return $flashMessage;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function getHtml(): string
  {
    $html = '<div class="flash-messages">';

    if (!empty($this->flashMessages))
    {
      foreach ($this->flashMessages as $key => $flashMessage)
      {
        if ($flashMessage->isOnce())
        {
          unset($this->flashMessages[$key]);
        }

        $html .= $flashMessage->getHtml();
      }

      $html .= '</div>';

      if (empty($this->flashMessages))
      {
        $this->flashMessages = null;
      }
    }

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
