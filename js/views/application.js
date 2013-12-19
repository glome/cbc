/**
 *
 */
App.ApplicationView = Ember.View.extend(
{
  classNames: ['wrapper'],

  /**
   *
   */
  headerContentView: Ember.View.extend(
  {
    level: 1,
    info: true,
    templateName: 'headerContent'
  }),
  /**
   *
   */
  didInsertElement: function()
  {
    var self = this;

    Ember.$(window).scroll(function()
    {
      if (Ember.$(this).scrollTop() > 150)
      {
        Ember.$('.backtotop').fadeIn();
      }
      else
      {
        Ember.$('.backtotop').fadeOut();
      }
    });

    Ember.$('.backtotop').click(function()
    {
      Ember.$('html, body').animate({ scrollTop:0 }, 'slow');
    });
  }
});
