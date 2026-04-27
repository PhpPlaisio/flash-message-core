import {Cast} from 'Plaisio/Helper/Cast';

/**
 * Class for flash messages.
 */
export class CoreFlashMessageCollection
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The number of milliseconds before a flash message will be removed.
   */
  public static sleep: number = 5000;

  /**
   * All registered tables.
   */
  protected static flashMessages: CoreFlashMessageCollection[] = [];

  /**
   * The URL to remove a persistent flash message.
   */
  private static urlRemove: string;

  /**
   * The jQuery object of this flash message.
   */
  private $flashMessage: JQuery;

  /**
   * The timer handle.
   */
  private timerHandle: number = 0;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param $flashMessage The jQuery object of the flash message.
   */
  public constructor($flashMessage: JQuery)
  {
    this.$flashMessage = $flashMessage;

    const that: this = this;

    this.$flashMessage.find('button.flash-message-close').on('click', function (): void
    {
      that.close();
    });
    this.$flashMessage.on('click', function (): void
    {
      that.removeTimeout();
      that.timerHandle = -1;
    });
    this.$flashMessage.slideToggle();
    this.$flashMessage.on('mouseover', function (): void
    {
      that.removeTimeout();
    });
    this.$flashMessage.on('mouseout', function (): void
    {
      that.setTimeout();
    });

    this.setTimeout();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Registers flash messages that matches a jQuery selector as a CoreFlashMessageCollection.
   *
   * @param selector The jQuery selector.
   * @param urlRemove The URL to remove a persistent flash message.
   */
  public static main(selector: string, urlRemove: string): void
  {
    const that: typeof CoreFlashMessageCollection = this;

    that.urlRemove = urlRemove;

    $(selector).each(function (): void
    {
      let flashMessage = $(this);

      if (!flashMessage.hasClass('is-registered'))
      {
        CoreFlashMessageCollection.flashMessages.push(new that(flashMessage));
        flashMessage.addClass('is-registered');
      }
    });
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Closes this flash message.
   */
  private close(): void
  {
    this.$flashMessage.slideUp();
    if (Cast.toManBool(this.$flashMessage.attr('data-persistent'), false))
    {
      $.post(CoreFlashMessageCollection.urlRemove, {'id': Cast.toManString(this.$flashMessage.attr('id'))});
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Removes the timer for dismissing the flash message.
   */
  private removeTimeout(): void
  {
    if (this.timerHandle > 0)
    {
      clearTimeout(this.timerHandle);
      this.timerHandle = 0;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the timer for dismissing the flash message.
   */
  private setTimeout(): void
  {
    const that: this = this;

    if (Cast.toManBool(this.$flashMessage.attr('data-auto-dismiss'), false) && this.timerHandle === 0)
    {
      this.timerHandle = setTimeout(function (): void
      {
        that.close();
      }, CoreFlashMessageCollection.sleep);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
// Plaisio\Console\TypeScript\Helper\MarkHelper::md5: b4ac74863f050386e7eaa9b663de1bf6
