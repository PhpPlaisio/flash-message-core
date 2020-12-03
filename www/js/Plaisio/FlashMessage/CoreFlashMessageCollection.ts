import {Cast} from '../Helper/Cast';

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

    const that = this;

    this.$flashMessage.find('button.close').on('click', function ()
    {
      that.close();
    });
    this.$flashMessage.on('click', function ()
    {
      that.removeTimeout();
      that.timerHandle = -1;
    });
    this.$flashMessage.slideToggle();
    this.$flashMessage.on('mouseover', function ()
    {
      that.removeTimeout();
    });
    this.$flashMessage.on('mouseout', function ()
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
   */
  public static registerFlashMessage(selector: string)
  {
    const that = this;

    $(selector).each(function ()
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
  private close()
  {
    this.$flashMessage.slideUp();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Removes the timer for dismissing the flash message.
   */
  private removeTimeout()
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
  private setTimeout()
  {
    const that = this;

    if (Cast.toManBool(this.$flashMessage.attr('data-auto-dismiss'), false) && this.timerHandle === 0)
    {
      this.timerHandle = setTimeout(function ()
      {
        that.close();
      }, CoreFlashMessageCollection.sleep);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
