import {Cast} from "../Helper/Cast";

/**
 * Class for flash messages.
 */
export class CoreFlashMessageColection
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The number of milliseconds before a flash message will be removed.
   */
  public static sleep: number = 5000;

  /**
   * All registered tables.
   */
  protected static flashMessages: CoreFlashMessageColection[] = [];

  /**
   * The jQuery object of this flash message.
   */
  private $flashMessage: JQuery;

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

    this.$flashMessage.slideToggle();

    if (Cast.toManBool(this.$flashMessage.attr('data-auto-dismiss'), false))
    {
      setTimeout(function ()
      {
        that.close();
      }, CoreFlashMessageColection.sleep);
    }
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
        CoreFlashMessageColection.flashMessages.push(new that(flashMessage));
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
}

//----------------------------------------------------------------------------------------------------------------------
