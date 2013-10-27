/**
 *
 */
App.PairingView = Ember.View.extend(
{
  templateName: 'pairing',
  /**
   *
   */
  didInsertElement: function()
  {
    Ember.$('div.pairing span').click(function()
    {
      Ember.$(this).parent().find('div.code').slideToggle('medium');
      Ember.$(this).parent().find('div.code .reply').empty();
    });
  }
});