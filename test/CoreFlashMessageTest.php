<?php
declare(strict_types=1);

namespace Plaisio\FlashMessage\Test;

use PHPUnit\Framework\TestCase;
use Plaisio\FlashMessage\CoreFlashMessage;

/**
 * Test cases for class CoreFlashMessage.
 */
class CoreFlashMessageTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test getter and setter of auto dismiss.
   */
  public function testAutoDismiss(): void
  {
    $flashMessage = new CoreFlashMessage('Hello, world!');
    foreach ([false, true] as $autoDismiss)
    {
      $object = $flashMessage->setAutoDismiss($autoDismiss);
      $bool   = $flashMessage->isAutoDismiss();

      self::assertSame($flashMessage, $object);
      self::assertSame($autoDismiss, $bool);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test method getHtml() with pain text and special characters.
   */
  public function testGetHtml1(): void
  {
    $flashMessage = new CoreFlashMessage('Hello & world!');
    $html         = $flashMessage->setAttrId('123456')
                                 ->addClass('first')
                                 ->getHtml();
    $expected     = '<div id="123456" class="first flash-message-wrapper">Hello &amp; world!<button class="flash-message-close" type="button">&times;</button></div>';
    self::assertSame($expected, $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test method getHtml() with HTML snippet.
   */
  public function testGetHtml2(): void
  {
    $flashMessage = new CoreFlashMessage('Hello<br/>world!', true);
    $html         = $flashMessage->setAttrId('123456')
                                 ->addClass('first')
                                 ->getHtml();
    $expected     = '<div id="123456" class="first flash-message-wrapper">Hello<br/>world!<button class="flash-message-close" type="button">&times;</button></div>';
    self::assertSame($expected, $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test getter and setter of once.
   */
  public function testOnce(): void
  {
    $flashMessage = new CoreFlashMessage('Hello, world!');
    foreach ([false, true] as $once)
    {
      $object = $flashMessage->setOnce($once);
      $bool   = $flashMessage->isOnce();

      self::assertSame($flashMessage, $object);
      self::assertSame($once, $bool);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test getter and setter of first weight.
   */
  public function testWeight1(): void
  {
    $flashMessage = new CoreFlashMessage('Hello, world!');
    $weight1      = rand();
    $object       = $flashMessage->setWeight1($weight1);
    $weight       = $flashMessage->getWeight1();

    self::assertSame($flashMessage, $object);
    self::assertSame($weight1, $weight);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test getter and setter of second weight.
   */
  public function testWeight2(): void
  {
    $flashMessage = new CoreFlashMessage('Hello, world!');
    $weight2      = rand();
    $object       = $flashMessage->setWeight2($weight2);
    $weight       = $flashMessage->getWeight2();

    self::assertSame($flashMessage, $object);
    self::assertSame($weight2, $weight);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
