<?php
declare(strict_types=1);

namespace Plaisio\FlashMessage;

use Plaisio\PlaisioInterface;
use Plaisio\PlaisioObject;
use Plaisio\Session\Session;
use SetBased\Exception\LogicException;

/**
 * Manager of flash messages.
 */
class CoreFlashMessageCollection extends PlaisioObject implements FlashMessageCollection
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The weights of the basic types of flash messages.
   *
   * @var int[]
   */
  private static array $weight1 = ['error'   => 100,
                                   'warning' => 200,
                                   'info'    => 200,
                                   'success' => 100];

  /**
   * The flash messages.
   *
   * @var FlashMessage[]|null
   */
  private ?array $flashMessages;

  /**
   * The current second weight for sorting.
   *
   * @var int
   */
  private int $weight2 = 0;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param PlaisioInterface $object The parent PhpPlaisio object.
   */
  public function __construct(PlaisioInterface $object)
  {
    parent::__construct($object);

    $this->flashMessages = &$this->nub->session->getNamedSection(__CLASS__, Session::SECTION_EXCLUSIVE);
    if ($this->flashMessages===null)
    {
      $this->flashMessages = [];
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Compares two flash messages for sorting.
   *
   * @param FlashMessage $flashMessage1 The first flash message.
   * @param FlashMessage $flashMessage2 The second flash message.
   *
   * @return int
   */
  public static function compare(FlashMessage $flashMessage1, FlashMessage $flashMessage2): int
  {
    $cmp1 = $flashMessage1->getWeight1()<=>$flashMessage2->getWeight1();
    if ($cmp1!==0) return $cmp1;

    return $flashMessage1->getWeight2()<=>$flashMessage2->getWeight2();
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
  public function addFlashMessage(FlashMessage $flashMessage): FlashMessageCollection
  {
    $uuid = uuid_create();
    $flashMessage->setAttrId($uuid);
    $this->flashMessages[$uuid] = $flashMessage;

    $this->nub->session->setHasFlashMessage(true);

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
                   ->setWeight1(static::$weight1[$type])
                   ->setWeight2(++$this->weight2)
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

    $this->addFlashMessage($flashMessage);

    return $flashMessage;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function getHtml(): string
  {
    $this->sortFlashMessages();

    $html = '<div class="flash-messages">';

    if (!empty($this->flashMessages))
    {
      foreach ($this->flashMessages as $flashMessage)
      {
        $html .= $flashMessage->getHtml();
      }
    }

    $html .= '</div>';

    $this->cleanFlashMessages();

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function removeFlashMessage(string $id): void
  {
    unset($this->flashMessages[$id]);

    if (empty($this->flashMessages))
    {
      $this->flashMessages = null;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Removes flash messages that must be shown once only.
   */
  private function cleanFlashMessages(): void
  {
    foreach ($this->flashMessages as $id => $flashMessage)
    {
      if ($flashMessage->isOnce())
      {
        $this->removeFlashMessage($id);
      }
    }
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sorts the flash messages.
   */
  private function sortFlashMessages(): void
  {
    if ($this->flashMessages!==null && count($this->flashMessages)>1)
    {
      uasort($this->flashMessages, [__CLASS__, 'compare']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
