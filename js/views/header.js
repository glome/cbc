
/**
 *
 */
App.HeaderView = Ember.View.extend(
{
  tagName: 'div',
  role: 'navigation',
  templateName: 'header',
  attributeBindings: ['role'],
  classNames: ['navbar', 'navbar-fixed-top', 'header'],

  leftBox: Ember.View.extend(
  {
    tagName: 'li',
    classNames: ['leftbox'],
    templateName: 'leftBox',

    click: function(e)
    {
      var state = Ember.$('.header .leftbox .type').attr('data-state');
      var prevState = state;

      switch (parseInt(state))
      {
        case 1:
          // pairing screen
        case 2:
          // info screen
          ++state;
          Ember.$('.header .leftbox .type span').fadeOut('fast');
          break;
        case 3:
          // redeem screen
          state = 1;
          this.get('controller').get('controllers.sync').send('getSyncCode');
          Ember.$('.header .leftbox .type span').fadeIn('fast');
          break;
      }
      Ember.$('.header .centerbox .menu[data-elem="' + prevState + '"]').fadeToggle('medium', function()
      {
        Ember.$('.header .centerbox .menu[data-elem="' + state + '"]').fadeToggle('medium', function()
        {
          Ember.$('.header .leftbox .type').attr('data-state', state);
        }).css('display', 'inline-block');
      });

      return false;
    }
  }),
  centerBox: Ember.View.extend(
  {
    info: true,
    tagName: 'li',
    classNames: ['centerbox'],
    templateName: 'centerBox',
    actions:
    {
      'topToggle': function()
      {
        Ember.$('.content').toggleClass('openhdr');
        Ember.$('.header, .leftbox, .centerbox, .centerbox .toptoggle').toggleClass('open');

        if (Ember.$('.header').hasClass('open'))
        {
          var state = Ember.$('.header .leftbox .type').attr('data-state');
          if (state == '1')
          {
            this.get('controller').get('controllers.sync').send('getSyncCode');
          }
          Ember.$('.header .centerbox [data-elem="' + state + '"], .header .centerbox [data-elem="' + state + '"] *').fadeIn('fast').css('display', 'inline-block');
        }
        else
        {
          Ember.$('.header .centerbox .menu').hide();
        }

        return false;
      }
    }
  })
});
