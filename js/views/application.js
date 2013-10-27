/**
 *
 */
App.ApplicationView = Ember.View.extend(
{
  /**
   *
   */
  didInsertElement: function()
  {
    Ember.$(window).scroll(function()
    {
      if ($(this).scrollTop() > 150)
      {
        Ember.$('#back-top').fadeIn();
      } else {
        Ember.$('#back-top').fadeOut();
      }
    });

    Ember.$('.backtotop').click(function()
    {
      Ember.$('html, body').animate({scrollTop:0}, 'slow');
    });

    Ember.$('.m-menu > span').click(function ()
    {
      Ember.$(this).toggleClass("active");
      Ember.$(this).parent().find("> ul").slideToggle('medium');
    });
  }
});