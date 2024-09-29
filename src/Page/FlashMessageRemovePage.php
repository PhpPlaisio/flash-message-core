<?php
declare(strict_types=1);

namespace Plaisio\FlashMessage\Page;

use Plaisio\C;
use Plaisio\Exception\BadRequestException;
use Plaisio\Kernel\Nub;
use Plaisio\Page\CorePage;
use Plaisio\Response\NullResponse;
use Plaisio\Response\Response;

/**
 * Page for removing a flash message.
 */
class FlashMessageRemovePage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of this page.
   *
   * @return string
   */
  public static function getUrl(): string
  {
    return Nub::$nub->cgi->putId('pag', C::PAG_ID_PLAISIO_FLASH_MESSAGE_REMOVE, 'pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function checkAuthorization(): void
  {
    if (!Nub::$nub->request->isPost)
    {
      throw new BadRequestException('POST expected');
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function handleRequest(): Response
  {
    Nub::$nub->flashMessage->removeFlashMessage($_POST['id']);

    return $this->response = new NullResponse();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
