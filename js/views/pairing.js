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
    var self = this;
    Ember.$('div.pairing span').click(function()
    {
      var obj = this;
      // make a quick health check
      var success = function()
      {
        Ember.$(obj).parent().find('div.code').slideToggle('medium');
        Ember.$(obj).parent().find('div.code .reply').empty();
        Ember.$(obj).parent().find('input.code').val('').removeAttr('disabled');
      }

      self.get('controller').get('controllers.user').send('healthCheck', success);
    });
  }
});